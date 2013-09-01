<?php

namespace DG\JiraAuthBundle\Security\Authentication\Handler;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class JiraAuthenticationHandler implements AuthenticationSuccessHandlerInterface,
    AuthenticationFailureHandlerInterface,
    LogoutSuccessHandlerInterface{

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        // TODO: Implement onLogoutSuccess() method.
    }
}