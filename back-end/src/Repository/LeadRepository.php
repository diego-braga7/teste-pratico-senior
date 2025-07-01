<?php
namespace Src\Repository;

use Src\Entity\Lead;

class LeadRepository extends AbstractRepository
{
    protected string $table = 'leads';
    
    protected string $entityClass = Lead::class;

}
