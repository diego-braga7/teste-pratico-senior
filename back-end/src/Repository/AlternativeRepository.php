<?php
namespace Src\Repository;

use Src\Entity\Alternative;

class AlternativeRepository extends AbstractRepository
{
    protected string $table = 'alternatives';
    
    protected string $entityClass = Alternative::class;

}
