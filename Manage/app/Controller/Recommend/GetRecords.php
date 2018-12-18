<?php

namespace Dolphin\Ting\Controller\Recommend;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Pic_model;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class GetRecords extends Recommend
{
    private $columns = [
        '缩略图',
        '推荐时间'
    ];

    private $category_model;

    private $pic_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->pic_model = new Pic_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        // 分页
        $page = $request->getAttribute('page');

        $search  = [
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ];

        $records = $this->recommend_model->records($search);

        $images  = [];

        foreach ($records as $record) {
            $photo = $this->pic_model->record(["hash" => $record['picture_hash']]);

            if ($photo['is_oss']) {
                $valid = 3600;

                try {
                    $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $photo['path'], $valid, "GET", [
                        OssClient::OSS_PROCESS => "image/resize,m_fill,h_160,w_160"
                    ]);
                } catch (OssException $e) {
                    $path = getenv('WEB_URL') . '/' . $photo['path'];
                }
            } else {
                $path = getenv('WEB_URL') . '/' .$photo['path'];
            }

            $images[] = [
                      'hash' => $photo['hash'],
                      'path' => $path,
                    'is_oss' => $photo['is_oss'] ? true : false,
                'gmt_create' => $record['gmt_create']
            ];
        }

        $data = [
             "records" => $images,
             "columns" => $this->columns,
                "page" => Page::reder('/recommend/records', $this->recommend_model->total([]), $page, Common::PAGE_COUNT, '')
        ];

        $this->respond('Recommend/Records', $data);
    }
}