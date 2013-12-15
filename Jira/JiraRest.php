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

class JiraRest {
    private $jiraUrl = '';

    public function __construct($jiraUrl){
        $this->jiraUrl = $jiraUrl;
    }

    public function getUserInfo($username, $password){
        $request = new Message\Request(
            'GET',
            '/rest/api/2/user?username=' . $username,
            $this->jiraUrl
        );

        $request->addHeader('Authorization: Basic ' . base64_encode($username . ':' . $password) );
        $request->addHeader('Content-Type: application/json');

        $response = new Message\Response();

        $client = new Curl();
        $client->setTimeout(10);
        $client->send($request, $response);

        return $response;
    }
}