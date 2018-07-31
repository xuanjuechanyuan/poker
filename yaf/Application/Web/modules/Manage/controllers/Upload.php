<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/23
 * Time: 下午1:40
 */

use \BaseYaf as Y;
/**
 *  */
class UploadController extends \Base\BaseControllers
{

    /**
     * 图片上传
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function imageAction(){
        Y::disableView();
        $path = ROOT.'Site/'.SITE;
        $upload = new Upload\Image($_FILES['file']);
        try{
            $filePath = $upload->setLocation($path.'/upload/'.date("Ymd"),0755)
                ->setMime(['jpg','jpeg','png'])
                ->setName(time().'_'.rand(1000,100000))
                ->upload()
                ->getFullPath();
            $url = str_replace($path,'',$filePath);

            return $this->success(['url'=>$url]);
        }catch (\Throwable $throwable){
            return $this->error(100000002);
        }

    }

}