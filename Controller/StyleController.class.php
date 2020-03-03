<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 11:27
 */

namespace AliyunOss\Controller;

use AliyunOss\Service\StyleService;

/**
 * 处理样式问题
 * Class StyleController
 * @package AliyunOss\Controller
 */
class StyleController extends BaseController
{

    /**
     * 样式设计
     */
    public function styleList(){
        if(IS_AJAX){
            $page = I('page','1','trim');
            $limit = I('limit','20','trim');
            $where['is_delete'] = '0';
            $order = 'listorder desc,id desc';

            $title = I('title','','trim');
            if($title) $where['title'] = ['like',['%'.$title.'%']];

            $res = StyleService::getStyleList($where,$order,$page,$limit);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /**
     * 样式详情
     */
    public function styleDetails(){
        if(IS_AJAX){
            $id = I('id','','trim');
            $res = StyleService::getStyleDetails($id);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /**
     * 添加或者编辑样式
     */
    public function addEditStyle(){
        $post = I('post.');
        $res = StyleService::addEditStyle($post);
        $this->ajaxReturn($res);
    }


}