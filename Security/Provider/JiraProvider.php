<?php

namespace DG\JiraAuthBundle\Security\Provider;

use DG\JiraAuthBundle\Entity\User;
use DG\JiraAuthBundle\Security\Authenticatin\Token\JiraToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JiraProvider implements AuthenticationProviderInterface {

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
        $this->checkUserAuthentication($token);

        $user = new User();
        $user->setRoles(array('ROLE_USER'));
        $user->setEmail('test@mail.ru');
        $user->setUsername('test');

        $token->setUser($user);
    }

    public function checkUserAuthentication(JiraToken $token){
        if($token->getJiraLogin() != 'test' || $token->getJiraPassword() != '123456'){
            throw new AuthenticationException('Incorrect login/password!');
        }
    }
}