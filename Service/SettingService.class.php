<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 17:13
 */

namespace AliyunOss\Service;

use AliyunOss\Model\AliyunOssConfModel;
use OSS\OssClient;
use OSS\Core\OssException;

class SettingService extends BaseService
{

    /**
     * 获取配置
     * @return array
     */
    static function getSetting()
    {
        $AliyunOssConfModel = new AliyunOssConfModel();
        $res = $AliyunOssConfModel->find();
        if(!$res) {
            $add['accesskey_id'] = '';
            $add['accesskey_secret'] = '';
            $add['edit_time'] = time();
            $add['bucket'] =  serialize([]);
            $AliyunOssConfModel->add($add);
            $res = $AliyunOssConfModel->find();
        }
        $res['bucket'] = unserialize($res['bucket']);
        if(!$res['bucket']) $res['bucket'] = [];
        return self::createReturn(true,$res);
    }

    /**
     * 保存内容
     * @param $post
     * @return array
     */
    static function addEditSetting($post){
        $AliyunOssConfModel = new AliyunOssConfModel();
        $checkRes = $AliyunOssConfModel->checkData($post);
        if(!$checkRes['status']) return $checkRes;
        $content = $checkRes['data'];

        $id = $AliyunOssConfModel->getField('id');
        $AliyunOssConfModel->where(['id'=>$id])->save($content);
        cache('aliyunOssConf','');
        return self::createReturn(true,'','操作成功');
    }

    /**
     * 获取解密url
     * @param string $url
     * @param int $style_id
     * @return string
     */
    static function urlDecryption($url = '',$style_id = 0){
        require_once APP_PATH.'AliyunOss/Libs/AliyunOss/autoload.php';
        if(cache('aliyunOssConf')){
            $AliyunOssConf = cache('aliyunOssConf');
        } else {
            $AliyunOssConfModel = new AliyunOssConfModel();
            $AliyunOssConf = $AliyunOssConfModel->find();
            cache('aliyunOssConf',$AliyunOssConf);
        }

        $parseUrl = parse_url($url); //url 分割
        $extension = pathinfo($parseUrl['path'])['extension']; //文件类型

        $accessKeyId = $AliyunOssConf['accesskey_id'];    //accessKeyId
        $accessKeySecret = $AliyunOssConf['accesskey_secret']; //accessKeySecret

        $host = $parseUrl['host'];  //文件host

        $object = $parseUrl['path'];
        $object = substr($object, 1); //文件路径

        $timeout = $AliyunOssConf['validity']; //图片有效期

        $endpoint = '';
        $bucket = '';
        $bucketList = unserialize($AliyunOssConf['bucket']);

        foreach ($bucketList as $k => $v){
            if($host == $v['host']){
                $endpoint = $v['endpoint'];
                $bucket = $v['bucket'];
            }
        }

        if(!$endpoint || !$bucket){
            return $url;
        }

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
            // 生成GetObject的签名URL。
            if(!$style_id) {
                //不使用样式单直接返回原图
                $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
            } else {
                $style = StyleService::getFileProcess($style_id);
                if(!$style) {
                    $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
                    return $signedUrl;
                }
                if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
                    $options = array(OssClient::OSS_PROCESS => $style);
                    $signedUrl = $ossClient->signUrl($bucket, $object, $timeout, "GET", $options);
                } else {
                    $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
                    return $signedUrl;
                }
            }
            return $signedUrl;
        } catch (OssException $e) {
            return $url;
        }
    }

}