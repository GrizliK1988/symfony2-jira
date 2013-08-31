<?php

namespace DG\JiraAuthBundle\Security\Provider;

use DG\JiraAuthBundle\Security\Authenticatin\Token\JiraToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
    }
}