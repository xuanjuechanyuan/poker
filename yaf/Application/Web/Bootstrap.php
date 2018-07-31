<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/25
 * Time: 上午9:17
 */

use \BaseYaf as Y;
/**
 *  */
class Bootstrap extends \Base\Bootstrap
{
    /**
     * 加载插件
     */
    public function _initPlugin()
    {
        Y::registerPlugin('Web');
    }
}