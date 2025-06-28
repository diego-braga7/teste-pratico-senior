<?php

namespace Src\Service;

use InvalidArgumentException;
use Src\Entity\User;
use Src\Repository\RepositoryInterface;
use Src\Validator\InterfaceValidator;

class AuthService 
{

    
    public function __construct(private InterfaceValidator $validator, private RepositoryInterface $repository)
    {
    }

    /**
     * @param string $email 
     * @param string $senha 
     * @return string Basic 
     *
     * @throws InvalidArgumentException 
     */
    public function login(string $email, string $senha): string
    {
        if (! $this->validator->validate(['email' => $email])) {
            throw new InvalidArgumentException('e-mail inválido.');
        }

        $storedHash = $this->getSavedPasswordHash($email);
        if (empty($storedHash)) {
            throw new InvalidArgumentException('e-mail não encontrado no banco');
        }

        if ($senha !== $storedHash) {
            throw new InvalidArgumentException('senha inválida');
        }

        $credentials = sprintf('%s:%s', $email, $senha);
        $token = base64_encode($credentials);

        return sprintf('Basic %s', $token);
    }

    /**
     *
     * @param string $email
     * @return string|null The MD5-hashed password or null if not found.
     */
    protected function getSavedPasswordHash(string $email): ?string
    {
        /** @var User $user */
        $user = $this->repository->getByCollumn('email', $email);
        if(!$user){
            return null;
        }
        return $user->getPasswordHash();

    }
}