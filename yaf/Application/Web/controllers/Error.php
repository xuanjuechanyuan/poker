<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/25
 * Time: 下午2:57
 */

use BaseYaf as Y;
/**
 *  */
class ErrorController extends \Base\BaseControllers
{
    public function errorAction($exception)
    {
//        Y::disableView();
//        var_dump($exception);
//        file_put_contents('/data/log/php/zhl.log',var_export($exception,1),8);
        //\Tools\Response::error(400, '系统错误' ,$exception->getMessage());
    }

}