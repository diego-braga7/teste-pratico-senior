<?php
namespace Src\Repository;

use Src\Entity\Lead;
use Src\Entity\LeadResponse;

class LeadResponseRepository extends AbstractRepository
{
    protected string $table = 'lead_responses';
    
    protected string $entityClass = LeadResponse::class;

}
