<?php

namespace Dolphin\Ting\Controller\Recommend;

use Slim\Http\Request;
use Slim\Http\Response;

class GetAdd extends Recommend
{
    public function __invoke(Request $request, Response $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $hash = $querys['hash'];

        $db = $this->recommend_model->add(["picture_hash" => $hash]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '图片加入推荐失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '图片加入推荐成功'
            ]);  
        }

        return $response->withRedirect('/recommend/records', 302);
    }
}