<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 15:03
 */

namespace AliyunOss\Service;

use AliyunOss\Model\AliyunOssStyleModel;

class StyleService extends BaseService
{

    /**
     * 添加或者编辑样式
     * @param $post
     * @return array
     */
    static function addEditStyle($post)
    {
        $AliyunOssStyleModel = new AliyunOssStyleModel();
        $checkRes = $AliyunOssStyleModel->checkData($post);
        if(!$checkRes['status']) return $checkRes;
        $content = $checkRes['data'];
        if($post['id']){
            $id = $post['id'];
            $AliyunOssStyleModel->where(['id'=>$id])->save($content);
        } else {
            $id = $AliyunOssStyleModel->add($content);
        }
        session('aliyunOssStyle_'.$id,[]);
        return self::createReturn(true,[
            'id'=>$id
        ],'操作成功');
    }

    /**
     * 获取列表
     * @param array $where
     * @param string $order
     * @param int $page
     * @param int $limit
     * @return array
     */
    static function getStyleList($where = [],$order = '',$page = 1,$limit = 20){
        $res = self::select('aliyun_oss_style',$where,$order,$page,$limit);
        $items = $res['data']['items'];
        foreach ($items as $k => $v){

        }
        $res['data']['items'] = $items;
        return $res;
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     */
    static function getStyleDetails($id){
        $AliyunOssStyleModel = new AliyunOssStyleModel();
        $res = $AliyunOssStyleModel->where(['id'=>$id])->find();
        return self::createReturn(true, $res);
    }

    /**
     * 获取style
     * @param $style_id
     * @return string
     */
    static function getFileProcess($style_id){
        if(session('aliyunOssStyle_'.$style_id)) return session('aliyunOssStyle_'.$style_id);
        $AliyunOssStyleModel = new AliyunOssStyleModel();
        $style = $AliyunOssStyleModel->getTransferConfig($style_id);
        session('aliyunOssStyle_'.$style_id,$style);
        return $style;
    }


}