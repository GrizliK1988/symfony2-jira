<?php

namespace DG\JiraAuthBundle;

use DG\JiraAuthBundle\DependencyInjection\Security\Factory\JiraFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DGJiraAuthBundle extends Bundle
{
    public function build(ContainerBuilder $container){
        parent::build($container);

        $extension = $container->getExtension('security');

        $extension->addSecurityListenerFactory(new JiraFactory());
    }
}
