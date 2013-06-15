<?php

namespace DG\JiraAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DGJiraAuthBundle:Default:index.html.twig', array('name' => $name));
    }

    public function showPublicPageAction($alias){
        return $this->render('DGJiraAuthBundle:Public:' . $alias . '.html.twig');
    }

    public function showPrivatePageAction($alias){
        return $this->render('DGJiraAuthBundle:Private:' . $alias . '.html.twig');
    }
}
