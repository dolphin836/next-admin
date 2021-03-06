<?php

namespace Dolphin\Ting\Controller\Pic;

use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Common;
use OSS\OssClient;
use OSS\Core\OssException;

class GetRecord extends Pic
{
    public function __invoke(Request $request, Response $response, $args)
    { 
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $hash = $querys['hash'];

        $record = $this->pic_model->record(["hash" => $hash]);

        if ($record['is_oss']) {
            $valid = 3600;

            try {
                $path = $this->oss_client->signUrl(getenv('OSS_BUCKET_NAME'), $record['path'], $valid, "GET");
            } catch (OssException $e) {
                $path = getenv('WEB_URL') . '/' . $record['path'];
            }
        } else {
            $path = getenv('WEB_URL') . '/' .$record['path'];
        }

        $data = [
                     'hash' => $record['hash'],
                    'width' => $record['width'],
                   'height' => $record['height'],
                     'size' => $this->size($record['size']),
               'gmt_create' => $record['gmt_create'],
                     'path' => $path,
                   'is_oss' => $record['is_oss'] ? true : false,
            'category_code' => $record['code'],
            'category_name' => $record['name'],
                     'uuid' => $record['uuid'],
                 'username' => $record['username']
        ];

        var_dump($data);

        // $this->respond('Pic/Record', $data);
    }
}