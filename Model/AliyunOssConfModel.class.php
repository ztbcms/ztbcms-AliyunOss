<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 17:15
 */
namespace AliyunOss\Model;

use Common\Model\RelationModel;

class AliyunOssConfModel extends RelationModel
{

    protected $tableName = 'aliyun_oss_conf';

    public function checkData($post){
        if(!$post['accesskey_id']) return createReturn(false,'','accesskey_id不能为空');
        if(!$post['accesskey_secret']) return createReturn(false,'','accesskey_secret不能为空');
        if(!$post['validity']) return createReturn(false,'','有效期不能为空');

        $content['accesskey_id'] = $post['accesskey_id'];
        $content['accesskey_secret'] = $post['accesskey_secret'];
        $content['edit_time'] = time();
        $content['bucket'] = serialize($post['bucket']);
        $content['validity'] = $post['validity'];

        return createReturn(true,$content,'校验成功');
    }

}