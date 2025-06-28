<?php

namespace Src\Service;

use InvalidArgumentException;
use Src\Entity\Alternative;
use Src\Entity\Question;
use Src\Entity\Quiz;
use Src\Entity\User;
use Src\LoggerFactory;
use Src\Repository\RepositoryInterface;
use Src\Validator\QuizValidatorInterface;





class AlternativeService
{

    public function __construct(private RepositoryInterface $repository) {}

    public function save(int $questionId, array $options){

        foreach($options as $idx => $option){
            if(empty($option)){
                continue;
            }

            $option = new Alternative($questionId, $option, $idx);
            $this->repository->save($option);

        }
    }

    
}
