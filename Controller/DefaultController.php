<?php

namespace DG\JiraAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
        if($this->get('security.context')->isGranted('ROLE_USER'))
            return $this->redirect($this->generateUrl('dg_jira_auth_private'));

        $error = $this->get('session')->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        $authenticationError = $error instanceof AuthenticationException ? $error->getMessage() : '';
        return $this->render('DGJiraAuthBundle:Public:login.html.twig', array('error' => $authenticationError));
    }

    public function showPrivatePageAction(){
        return $this->render('DGJiraAuthBundle:Private:for_members.html.twig', array(
            'userEmail' => $this->get('security.context')->getToken()->getUser()->getEmail()
        ));
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
