<?php
/**
 * User: yezhilie
 * Date: 2020-03-10
 * Time: 17:04
 */

namespace AliyunOss\Service;

use AliyunOss\Model\AliyunOssConfModel;
use OSS\Core\OssException;
use OSS\OssClient;
use System\Service\BaseService;

require_once APP_PATH.'AliyunOss/Libs/AliyunOss/autoload.php';

/**
 *
 * @package Upload\AliOssService
 */
class VideoUploadService extends BaseService{

    /**
     * 上传至oss
     *
     * @param String $url oss文件路径，例如http://oobb-online.oss-cn-shenzhen.aliyuncs.com/d/file/module_upload_videos/2019/09/5d85bd2234754.mp4
     * @return array => video_id: 视频唯一id
     */
    static function uploadToOss($url){
        $data = M('AliyunVideo')->where(['url' => $url])->find();
        if($data){
            //防止重复上传
            return self::createReturn(true, $data, 'OK');
        }

        do{
            //视频唯一id
            $video_id = date('YmdHis').rand(10000,99999);
        }while(M('AliyunVideo')->where(['video_id' => $video_id])->count());
        $filename = $video_id.'.jpg';
        $tmp_cover_url = $url.'?x-oss-process=video/snapshot,t_0000,m_fast,ar_auto';
        $pre = cache('Config.sitefileurl');
        $filePath = str_replace($pre, 'd/file/', $url);

        $AliyunOssConfModel = new AliyunOssConfModel();
        $AliyunOssConf = $AliyunOssConfModel->find();

        $accessKeyId = $AliyunOssConf['accesskey_id'];
        $accessKeySecret = $AliyunOssConf['accesskey_secret'];
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $bucketList = unserialize($AliyunOssConf['bucket'])[0];
        $endpoint = $bucketList['endpoint'];
        $bucket = $bucketList['bucket'];
        // 文件名称
        $object = $filePath;
        $allPath = SITE_PATH.$filePath;

        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $allPath);

            $cover_url = self::picturesDownload($tmp_cover_url, $filename);

            $data = [
                'video_id' => $video_id,
                'cover_url' => $cover_url,
                'url' => $url,
                'name' => $filename,
            ];
            M('AliyunVideo')->add($data);
            return self::createReturn(true, $data, 'OK');
        } catch(OssException $e) {
            return self::createReturn(false, null, $e->getMessage());
        }
    }

    /**
     * 获取视频封面图与视频
     * @param $video_id
     * @return array
     */
    static function getVideo($video_id){
        $data = M('AliyunVideo')->where(['video_id' => $video_id])->find();
        if($data){
            //封面图:data.cover_url
            //视频:data.url
            return self::createReturn(true, $data, '获取成功');
        }else{
            return self::createReturn(false, null, '获取失败');
        }
    }

    /**
     * 下载封面图
     * @param $url
     * @param $filename
     * @param string $catalogue
     * @return string
     */
    static function picturesDownload($url, $filename, $catalogue = 'CoverUrl'){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        ob_start ();
        curl_exec ( $ch );
        $return_content = ob_get_contents ();
        ob_end_clean ();
        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        $date = date("Y-m-d",time());
        $path = dirname(dirname(APP_PATH)).'/d/'.$catalogue.'/'.$date;
        if(!is_dir($path)){
            //不存在该目录的时候创建该目录
            $is_path = mkdir($path,0777,true);
            if(!$is_path) {
                echo '您没有操作的权限:创建目录 '.$path;
                exit;
            }
        }
        $file = $path.'/'.$filename;
        $fp= @fopen($file,"a"); //将文件绑定到流
        fwrite($fp,$return_content); //写入文件
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $file_url = $sys_protocal . $_SERVER['HTTP_HOST']  . '/d/'.$catalogue.'/'.$date.'/'.$filename;
        return $file_url;
    }
}