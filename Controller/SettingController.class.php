<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 11:33
 */

namespace AliyunOss\Controller;

use AliyunOss\Service\SettingService;

/**
 * 设置
 * Class SettingController
 * @package AliyunOss\Controller
 */
class SettingController extends BaseController
{
    /**
     * 使用手册
     */
    public function manual(){
        $this->assign('path',APP_PATH.'AliyunOss/Libs/');
        $this->display();
    }

    /**
     * 水印设置
     */
    public function setting(){
        if(IS_AJAX){
            $res = SettingService::getSetting();
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /**
     * 添加或者编辑设置
     */
    public function addEditSetting()
    {
        $post = I('post.');
        $res = SettingService::addEditSetting($post);
        $this->ajaxReturn($res);
    }

    /**
     * url 解密
     */
    public function urlDecryption(){
        $url = I('url','','trim');
        $style_id = I('style_id','','trim');
        $res = SettingService::urlDecryption($url,$style_id);
        $this->ajaxReturn(self::createReturn(true,$res,'获取成功'));
    }

}