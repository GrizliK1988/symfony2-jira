<?php

namespace DG\JiraAuthBundle\Security\Authentication\Provider;

use DG\JiraAuthBundle\Entity\User;
use DG\JiraAuthBundle\Security\Authentication\Token\JiraToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JiraProvider implements AuthenticationProviderInterface {

    private $userProvider;

    public function __construct(UserProviderInterface $userProvider, $providerKey)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof JiraToken;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->checkUserAuthentication($token);
        $token->setUser($user);

        return $token;
    }

    public function checkUserAuthentication(JiraToken $token){
        if($token->getJiraLogin() != 'test' || $token->getJiraPassword() != '123456'){
            throw new AuthenticationException('Incorrect login/password!');
        }

        return $this->userProvider->loadUserByUsername($token->getJiraLogin());
    }
}