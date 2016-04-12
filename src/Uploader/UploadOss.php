<?php namespace Stevenyangecho\UEditor\Uploader;
use OSS\Core\OssException;


/**
 *
 *
 * trait UploadOss
 *
 * 阿里云 上传 类
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
class UploadOss
{
    /**
     * 获取文件路径
     * @return string
     */
    public function getFilePath($fullName)
    {
        $fullName = ltrim($fullName, '/');
        return $fullName;
    }

    public function uploadOss($key, $content, $extra_data)
    {
        $accessKeyId = config('UEditorUpload.core.oss.accessKey');
        $secretKey = config('UEditorUpload.core.oss.secretKey');
        $endpoint = config('UEditorUpload.core.oss.endpoint');
        $bucket = config('UEditorUpload.core.oss.bucket');


        $object = $extra_data['fileName'];
        $child_dir = 'ueditor';

        try {
            $ossClient = new \OSS\OssClient($accessKeyId, $secretKey, $endpoint);
            $ossClient->createObjectDir($bucket, $child_dir);
            $ossClient->putObject($bucket, $child_dir.'/'.$object, $content);

            $url=rtrim(strtolower(config('UEditorUpload.core.oss.url')),'/');
            $fullName = ltrim($extra_data['fullName'], '/');

            $extra_data['fullName'] =$url.'/'.$child_dir.'/'.$object;
            $extra_data['stateInfo'] = $extra_data['stateMap'][0];

        } catch (\OSS\Core\OssException $e) {
            $extra_data['stateInfo'] = $e->getMessage();
        }

        return $extra_data;
    }
}