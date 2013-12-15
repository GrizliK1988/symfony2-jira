<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dima
 * Date: 01.09.13
 * Time: 23:08
 * To change this template use File | Settings | File Templates.
 */

namespace DG\JiraAuthBundle\User;


use DG\JiraAuthBundle\Entity\User;
use DG\JiraAuthBundle\Jira\JiraRest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class JiraUserProvider implements UserProviderInterface {

    private $jiraRest;

    public function __construct(JiraRest $jiraRest){
        $this->jiraRest = $jiraRest;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $decodedUserData = base64_decode($user->getBase64Hash());
        list($username, $password) = explode(':', $decodedUserData);
        $userInfoResponse = $this->jiraRest->getUserInfo($username, $password);
        $userInfo = json_decode($userInfoResponse->getContent());

        $user = new User();
        $user->setUsername($user->getUsername());
        $user->setEmail($userInfo->emailAddress);
        $user->addRole('ROLE_USER');
        return $user;
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === 'DG\JiraAuthBundle\Entity\User';
    }
}