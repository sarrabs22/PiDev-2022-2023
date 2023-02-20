<?php

// src/Security/CustomInvalidCredentialsException.php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class MyAuthenticator extends AuthenticationException
{
    // ...

    public function checkCredentials($credentials, User $user)
    {
        $password = $credentials['password'];

        
            throw new AuthenticationException('Invalid credentials.');
        

        return true;
    }
}


?>