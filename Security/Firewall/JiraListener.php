<?php

namespace DG\JiraAuthBundle\Security\Firewall;

use DG\JiraAuthBundle\Security\Authentication\Token\JiraToken;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

class JiraListener extends AbstractAuthenticationListener {
    private $csrfProvider;

    /**
     * {@inheritdoc}
     */
    public function __construct(SecurityContextInterface $securityContext,
                                AuthenticationManagerInterface $authenticationManager,
                                SessionAuthenticationStrategyInterface $sessionStrategy,
                                HttpUtils $httpUtils, $providerKey,
                                AuthenticationSuccessHandlerInterface $successHandler,
                                AuthenticationFailureHandlerInterface $failureHandler,
                                array $options = array(),
                                LoggerInterface $logger = null,
                                EventDispatcherInterface $dispatcher = null,
                                CsrfProviderInterface $csrfProvider = null){
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, array_merge(array(
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'csrf_parameter'     => '_csrf_token',
            'intention'          => 'authenticate',
            'post_only'          => true,
        ), $options), $logger, $dispatcher);

        $this->csrfProvider = $csrfProvider;
    }

    /**
     * {@inheritdoc}
     */
    protected function requiresAuthentication(Request $request){
        if ($this->options['post_only'] && !$request->isMethod('post')) {
            return false;
        }

        return parent::requiresAuthentication($request);
    }

    /**
     * Performs authentication.
     *
     * @param Request $request A Request instance
     *
     * @return TokenInterface|Response|null The authenticated token, null if full authentication is not possible, or a Response
     *
     * @throws AuthenticationException if the authentication fails
     */
    protected function attemptAuthentication(Request $request){
        if ($this->options['post_only'] && 'post' !== strtolower($request->getMethod())) {
            if (null !== $this->logger) {
                $this->logger->debug(sprintf('Authentication method not supported: %s.', $request->getMethod()));
            }

            return null;
        }

        if (null !== $this->csrfProvider) {
            $csrfToken = $request->get($this->options['csrf_parameter'], null, true);

            if (false === $this->csrfProvider->isCsrfTokenValid($this->options['intention'], $csrfToken)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }
        }

        $username = trim($request->get($this->options['username_parameter'], null, true));
        $password = $request->get($this->options['password_parameter'], null, true);

        $request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);

        $token = new JiraToken();
        $token->setJiraLogin($username);
        $token->setJiraPassword($password);

        return $this->authenticationManager->authenticate($token);
    }
}