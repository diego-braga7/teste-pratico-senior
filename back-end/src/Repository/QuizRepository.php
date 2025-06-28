<?php
namespace Src\Repository;

use Src\Entity\Quiz;

class QuizRepository extends AbstractRepository
{
    protected string $table = 'quizzes';
    
    protected string $entityClass = Quiz::class;

}
