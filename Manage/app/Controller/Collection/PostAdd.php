<?php

namespace Dolphin\Ting\Controller\Collection;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class PostAdd extends Collection
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $body = $request->getParsedBody();

        if (! $this->validate($body)) {
            return $response->withRedirect('/collection/add', 302);
        }

        $data = [
                 'code' => $this->random_code(),
                 'uuid' => $this->app->session->get('uuid'),
                 'name' => trim($body['name']),
              'content' => trim($body['content']),
            'is_public' => (int) $body['is_public'],
            'link_name' => trim($body['link_name']),
                 'link' => trim($body['link'])
        ];

        $db = $this->collection_model->add($data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加专题失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加专题成功'
            ]);  
        }

        return $response->withRedirect('/collection/records', 302);
    }

    private function validate($body)
    {
        $form_v_error = [];
        // 验证名称
        $error = [];

        if (! isset($body['name']) || $body['name'] === '') {
            $error[] = '名称不得为空.';
        } else {
            if (! v::stringType()->length(2, 64)->validate($body['name'])) {
                $error[] = '请输入 2 ~ 64 个字符的名称.';
            } else {
                if (v::stringType()->callback(function($name) {
                    // 名称是否已经存在
                    return $this->collection_model->is_has('name', $name);
                })->validate($body['name'])) {
                    $error[] = '名称已存在.';
                }
            }
        }

        if (! empty($error)) {
            $form_v_error['name'] = $error;
        }
        // 验证描述
        $error = [];

        if (isset($body['content']) && strlen($body['content'])) {
            if (! v::stringType()->length(10, 512)->validate($body['content'])) {
                $error[] = '请输入 10 ~ 512 个字符的描述信息.';
            }
        }

        if (! empty($error)) {
            $form_v_error['content'] = $error;
        }
        // 推广文案
        $error = [];

        if (isset($body['link_name']) && strlen($body['link_name'])) {
            if (! v::stringType()->length(4, 64)->validate($body['link_name'])) {
                $error[] = '请输入 4 ~ 64 个字符的推广文案.';
            }
        }

        if (! empty($error)) {
            $form_v_error['link_name'] = $error;
        }
        // 推广链接
        $error = [];

        if (isset($body['link']) && strlen($body['link'])) {
            if (! v::url()->validate($body['link'])) {
                $error[] = '请输入正确的 URL 地址.';
            }
        }

        if (! empty($error)) {
            $form_v_error['link'] = $error;
        }

        if (! empty($form_v_error)) {
            $this->app->flash->addMessage('form_v_error', $form_v_error);

            $this->app->flash->addMessage('form_data', $body);

            return false;
        }

        return true;
    }
}