<?php

namespace Src\Validator;

use Src\Service\LogTrait;

class EmailValidator implements InterfaceValidator
{
    use LogTrait;
    public function validate(array $data): bool
    {
        $email = $data['email'] ?? null;

        $this->getLogger()->warning($email);

        return  !(empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL));
    }
}
