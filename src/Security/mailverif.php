<?php
// src/Security/VerificationListener.php

namespace App\Security;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class VerificationListener implements AuthenticationSuccessHandlerInterface
{
    private $httpUtils;
    private $session;

    public function __construct(HttpUtils $httpUtils, SessionInterface $session)
    {
        $this->httpUtils = $httpUtils;
        $this->session = $session;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // Get the user
        $user = $token->getUser();

        // Check if the user is verified
        if (!$user->isVerified()) {
            // Set a flash message and redirect to the homepage
            $this->session->getFlashBag()->add('error', 'Please verify your email address before logging in.');
            return new RedirectResponse($this->httpUtils->generateUri($request, 'app_homepage'));
        }

        // Start the session and redirect to the target path
        $this->session->start();
        return new RedirectResponse($this->httpUtils->generateUri($request, $request->get('_target_path', '/')));
    }
}
