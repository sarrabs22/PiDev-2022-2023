<?php
// src/Security/LoginFailureHandler.php

// src/Security/LoginFailureHandler.php

// src/Security/LoginFailureHandler.php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $maxAttempts=3;
    private $lockoutTime=5;

    

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $session = $request->getSession();

        $loginAttempts = $session->get('login_attempts', 0);
        $session->set('login_attempts', ++$loginAttempts);

        if ($loginAttempts >= $this->maxAttempts) {
            $lockedUntil = time() + $this->lockoutTime;
            $session->set('login_locked_until', $lockedUntil);

            $remainingTime = $this->lockoutTime - (time() - $lockedUntil);
            $errorMessage = sprintf('Too many login attempts. Please try again in %d seconds.', $remainingTime);

            return new Response($errorMessage, 429);
        }

        $errorMessage = $exception->getMessage();

        return new Response($errorMessage, 401);
    }
}









?>