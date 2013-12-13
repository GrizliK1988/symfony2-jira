<?php

namespace DG\JiraAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class JiraToken extends AbstractToken
{
    protected $jira_login;
    protected $jira_password;

    public function __construct(array $roles = array('ROLE_USER')){
        parent::__construct($roles);
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getJiraLogin(){
        return $this->jira_login;
    }

    public function setJiraLogin($jira_login){
        $this->jira_login = $jira_login;
    }

    public function getJiraPassword(){
        return $this->jira_password;
    }

    public function setJiraPassword($jira_password){
        $this->jira_password = $jira_password;
    }

    public function serialize()
    {
        return serialize(array($this->jira_login, $this->jira_password, parent::serialize()));
    }

    public function unserialize($serialized)
    {
        list($this->jira_login, $this->jira_password, $parent_data) = unserialize($serialized);
        parent::unserialize($parent_data);
    }

    public  function getCredentials(){
        return '';
    }
}
