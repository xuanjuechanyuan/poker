<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/25
 * Time: 上午11:24
 */

class WebPlugin extends \Base\Plugins
{
    public function routerStartup(Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }

    public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
        // 检测用户是否登录，
        if($request->module == 'Manage'){
            if($request->controller != 'Account' && empty($_SESSION['id'])){
                header("Location:/manage/account/index");
                exit;
            }
        }

    }


    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }

    public function preResponse(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }
}

