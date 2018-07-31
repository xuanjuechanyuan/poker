<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whosafe
 * Date: 2018/6/26
 * Time: 上午11:13
 */

namespace Error;
/**
 *  */
class Web extends ErrorCode
{
    public $errorCode = [
        100001001 => '用户已存在',
        100001002 => '登录失败',
        100001003 => '用户已被禁用，请联系管理员',
        100001004 => '您无添加用户权限',
        100001005 => '旧密码错误',

    ];

}