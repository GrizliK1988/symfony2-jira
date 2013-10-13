<?php

namespace DG\JiraAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DGJiraAuthBundle:Default:index.html.twig', array('name' => $name));
    }

    public function showPublicPageAction(){
        return $this->render('DGJiraAuthBundle:Public:welcome.html.twig');
    }

    public function showLoginPageAction(){
        return $this->render('DGJiraAuthBundle:Public:login.html.twig');
    }

    public function showPrivatePageAction(){
        return $this->render('DGJiraAuthBundle:Private:for_members.html.twig');
    }

    /**
     * Firewall перехватит выполнение метода
     */
    public function checkAuthenticationAction(){
    }

    /**
     * Firewall перехватит выполнение метода
     */
    public function logoutAction(){
    }
}
