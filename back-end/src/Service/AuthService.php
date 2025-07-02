<?php

namespace Src\Service;

use InvalidArgumentException;
use Src\Entity\User;
use Src\Repository\RepositoryInterface;
use Src\Validator\InterfaceValidator;

class AuthService
{

    public function __construct(private InterfaceValidator $validator, private RepositoryInterface $repository) {}

    /**
     * @param string $email 
     * @param string $password 
     * @return string Basic 
     *
     * @throws InvalidArgumentException 
     */
    public function login(string $email, string $password): string
    {
        if (! $this->validator->validate(['email' => $email])) {
            throw new InvalidArgumentException('e-mail inválido.', 422);
        }

        $user = $this->getUSer($email);
        if (empty($user)) {
            throw new InvalidArgumentException('e-mail não encontrado no banco', 404);
        }

        if ($password !== $user->getPasswordHash()) {
            throw new InvalidArgumentException('senha inválida', 422);
        }

        $credentials = sprintf('%s:%s', $email, $password);
        $token = base64_encode($credentials);

        return sprintf('Basic %s', $token);
    }

    public function getUSer(string $email): ?User
    {
        return $this->repository->getByCollumn('email', $email);
    }

   
}
