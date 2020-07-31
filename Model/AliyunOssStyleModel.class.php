<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2020/3/3
 * Time: 15:05
 */

namespace AliyunOss\Model;

use Common\Model\RelationModel;

class AliyunOssStyleModel extends RelationModel
{

    protected $tableName = 'aliyun_oss_style';

    /**
     * 校验数据
     * @param $post
     * @return array
     */
    public function checkData($post)
    {
        if (!$post['title']) return createReturn(false, '', '我们不建议标题为空');
        $content['watermarkenable'] = $post['watermarkenable'];
        $content['watermarkminwidth'] = $post['watermarkminwidth'];
        $content['watermarkminheight'] = $post['watermarkminheight'];
        $content['watermarkimg'] = $post['watermarkimg'];
        $content['watermarkpct'] = $post['watermarkpct'];
        $content['watermarkquality'] = $post['watermarkquality'];
        $content['watermarkpos'] = $post['watermarkpos'];
        $content['pictures_length'] = $post['pictures_length'];
        $content['pictures_width'] = $post['pictures_width'];
        $content['title'] = $post['title'];
        $content['listorder'] = $post['listorder'];
        $content['edit_time'] = time();
        $content['is_display'] = '1';
        $content['is_delete'] = '0';
        $content['quality'] = $post['quality'];
        $content['quality_num'] = $post['quality_num'];
        return createReturn(true, $content, '校验成功');
    }

    public function getTransferConfig($style_id = 0)
    {
        $aliyunRes = $this->where(['id' => $style_id])->find();
        $style = '';
        if ($aliyunRes['watermarkenable']) {
            //处理水印
            $config = [
                'enable' => intval($aliyunRes['watermarkenable']),
                'position' => self::getPositionByNumber($aliyunRes['watermarkpos']),
                'quality' => intval($aliyunRes['watermarkquality']),
                'width' => intval($aliyunRes['watermarkminwidth']),
                'height' => intval($aliyunRes['watermarkminheight']),
                'opacity' => intval($aliyunRes['watermarkpct']),//透明度
                'img_path' => $aliyunRes['watermarkimg'],//水印图片路径
            ];

            $pictures_length = $aliyunRes['pictures_length'];  //原图长度
            $pictures_width = $aliyunRes['pictures_width']; //原图宽度

            $quality = $aliyunRes['quality'];
            $quality_num = $aliyunRes['quality_num'];

            $parse_url = parse_url($config['img_path']);

            //添加水印的设置
            if (strpos($parse_url['host'], 'oss')) {
                //水印为OSS的水印
                $path = $parse_url['path'];  //路径
                $path = ltrim($path, "/");
                $path = base64_encode($path);

                $style = 'image/auto-orient,1';
                $style .= '/watermark,image_' . $path;  //水印
                $style .= ',t_' . $config['opacity']; //透明度

                //位置
                $position = $config['position'];
                if ($position == 'top-left') {
                    $style .= ',g_nw'; //左上
                } else if ($position == 'top') {
                    $style .= ',g_north'; //上
                } else if ($position == 'top-right') {
                    $style .= ',g_ne'; //右上
                } else if ($position == 'left') {
                    $style .= ',g_west'; //中左
                } else if ($position == 'center') {
                    $style .= ',g_center'; //中
                } else if ($position == 'right') {
                    $style .= ',g_east'; //中右
                } else if ($position == 'bottom-left') {
                    $style .= ',g_sw'; //下左
                } else if ($position == 'bottom') {
                    $style .= ',g_south'; //下
                } else if ($position == 'bottom-right') {
                    $style .= ',g_se'; //下右
                }
            }

            //图片尺寸的设置
            if ($pictures_length && $pictures_width) {
                if($style) $style .= ',';
                $style .= 'image/auto-orient,1/resize,m_lfit,w_' . $pictures_width . ',h_' . $pictures_length;
            }

            //添加图片质量的设置
            if($quality > 0){
                if ($quality == 1) {
                    //使用相对质量
                    if($style) $style .= ',';
                    $style .= 'image/quality,q_'.$quality_num;
                }
                if ($quality == 2) {
                    //使用绝对质量
                    if($style) $style .= ',';
                    $style .= 'image/quality,Q_'.$quality_num;
                }
            }
        }
        return $style;
    }

    static function getPositionByNumber($waterPos)
    {
        $waterPos = intval($waterPos);
        switch ($waterPos) {
            case 1://1为顶端居左
                return 'top-left';
            case 2://2为顶端居中
                return 'top';
            case 3://3为顶端居右
                return 'top-right';
            case 4://4为中部居左
                return 'left';
            case 5://5为中部居中
                return 'center';
            case 6://6为中部居右
                return 'right';
            case 7://7为底端居左
                return 'bottom-left';
            case 8://8为底端居中
                return 'bottom';
            case 9://9为底端居右
                return 'bottom-right';
            default:
                return 'top-left';
        }
    }

}