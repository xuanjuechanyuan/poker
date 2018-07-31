<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/25
 * Time: 上午9:18
 */
use BaseYaf as Y;
//ini_set('yaf.library','/data/www/scimall.cc/zhl/web2/yafLib');
define("ROOT", dirname(__FILE__,3) . '/');
define('SITE', 'Web');
define('ENV', 'dev');


$app = new \Yaf\Application(ROOT . 'Config/' . SITE . '.ini');
//Y::web();

$y = Y::web();
//var_export($y);
if(is_object($y)){
    $y->response();
}

/*
use Handler\Handler as Y;
ini_set('yaf.library','/data/www/scimall.cc/zhl/web2/yafLib');
define("ROOT", dirname(__FILE__,3) . '/');
define('SITE', 'Web');
define('ENV', 'dev');
//true 开启debug
define("AdminType",1);
define("__P__","http://".$_SERVER['HTTP_HOST']."/vendors");
define("__R__","http://".$_SERVER['HTTP_HOST']);
//入口文件
$app = new \Yaf\Application(ROOT . 'Config/' . SITE . '.ini', ENV);

$Y = Y::web();
if(is_object($Y)){
    $Y->response();
}*/
