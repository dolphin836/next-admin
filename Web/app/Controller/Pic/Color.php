<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class Color extends Pic
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::COLOR;
    }
    /**
     * 按颜色查询图片记录
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $color = $args['color'];

        $page  = isset($args['page']) ? (int) $args['page'] : 1;
        // 总数量
        $total = $this->pic_model->color_hash_total($color);

        $fifter = [
            'color' => $color,
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT],
            'ORDER' => ['id' => 'DESC']
        ];
        // 当前页 Hash
        $hash    = $this->pic_model->color_hash($fifter);

        $records = $this->pic_model->records(['hash' => array_column($hash, 'picture_hash')]);

        if (empty($records)) {
            throw new NotFoundException($request, $response);
        }
        // 转换数据格式
        $photos = $this->convert($records);
        // 上下页
        $next   = $this->next($total, $page);
        $prev   = $this->prev($total, $page);

        $common = '/color/' . $color . '/';

        $data   = [
             'photos' => $photos,
               'next' => $next ? $common . $next : 'javascript:void(0)',
               'prev' => $prev ? $common . $prev : 'javascript:void(0)',
            'is_show' => true
        ];

        $this->respond('Pic/Records', $data);
    }
}
