<?php

namespace DG\JiraAuthBundle\Security\Authentication\Provider;

use DG\JiraAuthBundle\Entity\User;
use DG\JiraAuthBundle\Jira\JiraRest;
use DG\JiraAuthBundle\Security\Authentication\Token\JiraToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JiraProvider implements AuthenticationProviderInterface {

    private $userProvider;
    private $jiraRest;

    public function __construct(UserProviderInterface $userProvider, $providerKey, JiraRest $jiraRest)
    {
        $this->userProvider = $userProvider;
        $this->jiraRest = $jiraRest;
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
        $response = $this->jiraRest->getUserInfo($token->getJiraLogin(), $token->getJiraPassword());
        if(!in_array('HTTP/1.1 200 OK', $response->getHeaders())){
            throw new AuthenticationException( 'Incorrect email and/or password' );
        }
        $userInfo = json_decode($response->getContent());
        $user = new User();
        $user->setUsername($userInfo->name);
        $user->setBase64Hash(base64_encode($token->getJiraLogin() . ':' . $token->getJiraPassword()));
        $user->setEmail($userInfo->emailAddress);
        $user->addRole('ROLE_USER');
        return $user;
    }
}