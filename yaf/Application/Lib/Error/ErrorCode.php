<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/26
 * Time: 上午11:11
 */

namespace Error;
/**
 *  */
class ErrorCode
{
    public static $error = array (
        100000000 => '编码错误',
        100000001 => '系统错误',

        100000002 => '操作失败',
        100000003 => '无数据',
        100000004 => '参数错误',
    );


    public $errorCode = array();

    /**
     * ErrorCode constructor.
     */
    public function __construct()
    {
        return $this->errorCode = $this->errorCode + self::$error;
    }


}