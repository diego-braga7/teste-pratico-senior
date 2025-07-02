<?php

namespace Src\Repository;

use Monolog\Logger;
use PDO;
use Src\Database\DatabaseConnection;
use Src\Entity\EntityInterface;
use Src\LoggerFactory;

abstract class AbstractRepository implements RepositoryInterface
{
    protected PDO $pdo;
    /**
     * @var string
     */
    protected string $table;
    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    protected string $entityClass;

    public function __construct()
    {
        $this->pdo = (new DatabaseConnection())->getConnection();

        if (empty($this->table)) {
            throw new \LogicException('O nome da tabela deve ser definido na propriedade $table da subclasse.');
        }
    }

    public function getById(int $id): ?EntityInterface
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->map($data) : null;
    }

    /**
     *
     * @param string $column
     * @param integer|string|array $value
     * @return EntityInterface|EntityInterface[]|null
     */
    public function getByCollumn(string $column, int|string|array $value): EntityInterface|array|null
    {
        $column = "`" . str_replace("`", "``", $column) . "`";

        if (is_array($value) && count($value) > 0) {
            return $this->getValuesInArray($column, $value);
        }

        $sql  = "SELECT * FROM `{$this->table}` WHERE {$column} = :value";
        $stmt = $this->pdo->prepare($sql);

        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue(':value', $value, $type);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (! $rows) {
            return null;
        }
        if (count($rows) > 1) {
            return array_map(fn(array $r) => $this->map($r), $rows);
        }

        return $this->map($rows[0]);
    }

    private function getValuesInArray(string $column, int|string|array $value)
    {
        $placeholders = [];
        $params = [];
        foreach ($value as $i => $item) {
            $ph = ":v{$i}";
            $placeholders[] = $ph;
            $type = is_int($item) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $params[$ph] = ['value' => $item, 'type' => $type];
        }

        $inList = implode(', ', $placeholders);
        $sql    = "SELECT * FROM `{$this->table}` WHERE {$column} IN ({$inList})";
        $stmt   = $this->pdo->prepare($sql);

        foreach ($params as $ph => $info) {
            $stmt->bindValue($ph, $info['value'], $info['type']);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        if (! $rows) {
            return [];
        }

        return array_map(fn(array $row) => $this->map($row), $rows);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();
        $entities = [];
        foreach ($rows as $row) {
            $entities[] = $this->map($row);
        }
        return $entities;
    }

    /**
     *
     * @param array $data
     * @return EntityInterface
     */
    protected function map(array $data): EntityInterface
    {
        $class  = $this->entityClass;
        $ref    = new \ReflectionClass($class);
        $entity = $ref->newInstanceWithoutConstructor();

        foreach ($data as $column => $value) {
            $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $column)));
            if (! $ref->hasMethod($methodName)) {
                continue;
            }

            $method   = $ref->getMethod($methodName);
            $params   = $method->getParameters();
            $arg      = $value;

            if (count($params) === 1) {
                $paramType = $params[0]->getType();
                if ($paramType && ! $paramType->isBuiltin()) {
                    $typeName = $paramType->getName();
                    if (is_subclass_of($typeName, \DateTimeInterface::class) || $typeName === \DateTimeInterface::class) {
                        $arg = new $typeName($value);
                    }
                }
            }

            $entity->{$methodName}($arg);
        }

        return $entity;
    }

    public function save(EntityInterface $entity): EntityInterface
    {
        $ref = new \ReflectionClass($entity);
        $params = [];

        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();
            if (str_starts_with($name, 'get') && $method->getNumberOfRequiredParameters() === 0) {
                $prop = lcfirst(substr($name, 3));
                $column = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $prop));
                $value  = $entity->{$name}();

                if ($value instanceof \DateTimeInterface) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                $params[$column] = $value;
            }
        }

        $id = $params[$this->primaryKey] ?? null;

        if ($id === null) {
            $cols = array_keys($params);
            $placeholders = array_map(fn($c) => ':' . $c, $cols);
            $sql = sprintf(
                'INSERT INTO `%s` (`%s`) VALUES (%s)',
                $this->table,
                implode('`,`', $cols),
                implode(', ', $placeholders)
            );
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $newId = (int)$this->pdo->lastInsertId();
            $entity->{'set' . ucfirst($this->primaryKey)}($newId);
            return $entity;
        }

        $sets = [];
        foreach ($params as $col => $val) {
            if ($col === $this->primaryKey) continue;
            $sets[] = sprintf('`%s` = :%s', $col, $col);
        }
        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE `%s` = :%s',
            $this->table,
            implode(', ', $sets),
            $this->primaryKey,
            $this->primaryKey
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->getById($id);
    }
    public function delete(int $id): void
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
