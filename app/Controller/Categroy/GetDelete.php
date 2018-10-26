<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetDelete extends Categroy
{
    public function __invoke($request, $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $categroy_id = $querys['id'];

        $db = $this->common_model->delete(["id" => $categroy_id]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除分类失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除分类成功'
            ]);  
        }

        return $response->withRedirect('/categroy/records', 302);
    }
}