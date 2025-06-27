<?php
namespace Src\Repository;

use PDO;
use Src\Database\DatabaseConnection;
use Src\Entity\EntityInterface;

/**
 * Interface genérica para repositórios CRUD.
 */
interface RepositoryInterface
{
    /**
     * Busca um registro pelo ID.
     *
     * @param int $id
     * @return EntityInterface|null
     */
    public function getById(int $id): ?EntityInterface;

    public function getByCollumn(string $column, int|string|array $value): EntityInterface|array|null;

    /**
     * Retorna todos os registros.
     *
     * @return array<EntityInterface>
     */
    public function getAll(): array;

    /**
     * Persiste ou atualiza uma entidade.
     *
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity): EntityInterface;

    /**
     * Remove uma entidade.
     *
     * @param int $id
     */
    public function delete(int $id): void;
}