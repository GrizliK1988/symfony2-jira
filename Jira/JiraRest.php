<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Программист
 * Date: 13.10.13
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

namespace DG\JiraAuthBundle\Jira;
use Buzz\Message;
use Buzz\Client\Curl;
use DG\JiraAuthBundle\Security\Authentication\Token\JiraToken;

class JiraRest {
    public static function authenticate(JiraToken $token){
        $request = new Message\Request(
            'GET',
            '/rest/api/2/user?username=' . $token->getJiraLogin(),
            'http://jira.rkdev.ru'
        );

        $request->addHeader('Authorization: Basic ' .
            base64_encode(
                $token->getJiraLogin().':'.$token->getJiraPassword()
            )
        );
        $request->addHeader('Content-Type: application/json');

        $response = new Message\Response();

        $client = new Curl();
        $client->setTimeout(10);
        $client->send($request, $response);

        return $response;
    }

    public static function getUserInfo($authHash){
        list($user, $password) = explode(':', base64_decode($authHash));
        $request = new Message\Request(
            'GET',
            '/rest/api/2/user?username=' . $user,
            'http://jira.rkdev.ru'
        );

        $request->addHeader('Authorization: Basic ' . $authHash );
        $request->addHeader('Content-Type: application/json');

        $response = new Message\Response();

        $client = new Curl();
        $client->setTimeout(10);
        $client->send($request, $response);

        return json_decode($response->getContent());
    }
}