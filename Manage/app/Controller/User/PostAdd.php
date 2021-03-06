<?php

namespace Dolphin\Ting\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;

class PostAdd extends \Dolphin\Ting\Controller\Base
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $body = $request->getParsedBody();

        $avatar = 'assets/images/faces/male/1.jpg';

        $data = [
                'uuid' => $this->random_code(),
            'username' => trim($body['username']),
                'name' => trim($body['name']),
            'nickname' => trim($body['nickname']),
               'phone' => trim($body['phone']),
               'email' => trim($body['email']),
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
              'avatar' => $avatar,
                'sign' => trim($body['sign']),
               'group' => $body['group']
        ];

        $db = $this->app->db->insert("user", $data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加用户失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加用户成功'
            ]);  
        }

        return $response->withRedirect('/user/records', 302);
        
    }
}