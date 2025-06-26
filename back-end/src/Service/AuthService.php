<?php

namespace Src\Service;

use InvalidArgumentException;
use Src\Validator\InterfaceValidator;

class AuthService 
{

    /**
     * Constructor with email validator injection.
     *
     * @param InterfaceValidator $validator
     */
    public function __construct(private InterfaceValidator $validator)
    {
    }

    /**
     * Attempts to log in a user with the provided credentials.
     *
     * @param string $email The user email (must be valid format).
     * @param string $senha The user password (plaintext, will be MD5 hashed).
     * @return string Basic Auth token to be used in subsequent requests.
     *
     * @throws InvalidArgumentException if email or password is invalid.
     */
    public function login(string $email, string $senha): string
    {
        // Validate email format
        if (! $this->validator->validate(['email' => $email])) {
            throw new InvalidArgumentException('e-mail inválido.');
        }

        // Retrieve the stored password hash for the given email (to implement)
        $storedHash = $this->getSavedPasswordHash($email);
        if (empty($storedHash)) {
            // No user or password found for this email
            throw new InvalidArgumentException('e-mail não encontrado no banco');
        }

        // Compare provided password (MD5) with stored hash
        if (md5($senha) !== $storedHash) {
            throw new InvalidArgumentException('senha inválida');
        }

        // Build Basic Auth token
        $credentials = sprintf('%s:%s', $email, $senha);
        $token = base64_encode($credentials);

        return sprintf('Basic %s', $token);
    }

    /**
     * Stub for retrieving the saved MD5 password hash for a user.
     * To be implemented: database lookup or external repository call.
     *
     * @param string $email
     * @return string|null The MD5-hashed password or null if not found.
     */
    protected function getSavedPasswordHash(string $email): ?string
    {
        // TODO: Implement user lookup against a data source.

        // Temporary stub: no user found
        return null;
    }
}