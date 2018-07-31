<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/6/29
 * Time: 下午2:03
 */
use \BaseYaf as Y;
use \Respect\Validation\Validator as v;

/**
 *  */
class AccountController extends \Base\BaseControllers
{
    /**
     * 用户登录页面
     *
     * @Author : whoSafe
     *
     */
    public function indexAction()
    {

        //        $this->getValidation()->setTemplate('aaa');

    }

    /**
     * 用户登录.
     *
     * @Author : whoSafe
     *
     */
    public function loginAction()
    {
        if ( $this->isAjax() ) {

            // 校验规则.
            $this->rule['user_name'] = v::alnum('_-')
                ->length(3, 20)
                ->notEmpty()
                ->setTemplate('必须是字符,数字_-');
            $this->rule['password']  = v::stringVal()
                ->length(6, 18)
                ->notEmpty()
                ->setTemplate('密码不正确，必须大于6位小于18位');

            // 获取数据.
            $userName = $this->post('user_name');
            $password = $this->post('password');
            // 检测校验结果
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            // 判断用户是否存在
            $data = UserModel::instance()
                ->getRowByUserName($userName);
            if ( !$data ) {
                return $this->error(100001002);
            }
            if ( $data['state'] != 1 ) {
                return $this->error(100001003);
            }
            // 检测密码
            if ( UserModel::instance()
                ->checkPassword($password, $data['password'])
            ) {
                // 写入日志
                LogModel::instance()
                    ->add(1, $data['id'], $data['id'], '用户登录', $this->getClientIp(), []);
                $_SESSION = $data;

                return $this->success();
            }

            return $this->error(100001002);
        }
        Y::disableView();

        return $this->redirect("/manage/account/index");

    }


}