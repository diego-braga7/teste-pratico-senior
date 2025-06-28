<?php
namespace Src\Repository;

use Src\Entity\Question;
use Src\Entity\Quiz;

class QuestionRepository extends AbstractRepository
{
    protected string $table = 'questions';
    
    protected string $entityClass = Question::class;

}
