<?php

namespace DG\JiraAuthBundle\DependencyInjection\Security\Factory;


use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class JiraFactory extends AbstractFactory {

    public function __construct(){
        $this->addOption('username_parameter', '_username');
        $this->addOption('password_parameter', '_password');
        $this->addOption('intention', 'authenticate');
        $this->addOption('post_only', true);
    }

    /**
     * Subclasses must return the id of a service which implements the
     * AuthenticationProviderInterface.
     *
     * @param ContainerBuilder $container
     * @param string $id             The unique id of the firewall
     * @param array $config         The options array for this listener
     * @param string $userProviderId The id of the user provider
     *
     * @return string never null, the id of the authentication provider
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'dg_jira_auth.authentication_provider.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('dg_jira_auth.authentication_provider'))
            ->replaceArgument(1, $id)
        ;

        return $provider;
    }

    /**
     * Subclasses must return the id of the listener template.
     *
     * Listener definitions should inherit from the AbstractAuthenticationListener
     * like this:
     *
     *    <service id="my.listener.id"
     *             class="My\Concrete\Classname"
     *             parent="security.authentication.listener.abstract"
     *             abstract="true" />
     *
     * In the above case, this method would return "my.listener.id".
     *
     * @return string
     */
    protected function getListenerId()
    {
        return 'dg_jira_auth.authentication_listener';
    }

    /**
     * Указывает, когда должен быть вызван провайдер
     * @return string
     */
    public function getPosition()
    {
        return 'form';
    }

    /**
     * Id конфигурации для провайдера
     * @return string
     */
    public function getKey()
    {
        return 'jira-form';
    }

    /**
     * @param ContainerBuilder $container
     * @param $id
     * @param $config
     * @param $userProvider
     * @return string
     */
    protected function createListener($container, $id, $config, $userProvider)
    {
        $listenerId = parent::createListener($container, $id, $config, $userProvider);

        if (isset($config['csrf_provider'])) {
            $container
                ->getDefinition($listenerId)
                ->addArgument(new Reference($config['csrf_provider']))
            ;
        }

        return $listenerId;
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'security.authentication.form_entry_point.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.form_entry_point'))
            ->addArgument(new Reference('security.http_utils'))
            ->addArgument($config['login_path'])
            ->addArgument($config['use_forward'])
        ;

        return $entryPointId;
    }
}