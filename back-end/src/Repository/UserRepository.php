<?php
namespace Src\Repository;

use PDO;
use Src\Database\DatabaseConnection;
use Src\Entity\EntityInterface;
use Src\Entity\User;

class UserRepository extends AbstractRepository
{
    protected string $table = 'users';

}
