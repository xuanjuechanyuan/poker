<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/2
 * Time: 上午11:30
 */

use \Respect\Validation\Validator as v;
use \BaseYaf as Y;

/**
 *  */
class UserController extends \Base\BaseControllers
{
    /**
     * 获取用户列表
     *
     * @Author : whoSafe
     *
     */
    public function ListAction()
    {
        $this->rule['page'] = v::intVal()
            ->min(1);
        $page               = $this->get('page', 1);
        $pageSize           = 10;
        // 获取总条数.
        $count  = UserModel::instance()
            ->getTotal();
        $offset = ( $page - 1 ) * $pageSize;
        // 获取用户列表
        $data = UserModel::instance()
            ->getUserList($offset, $pageSize);
        $this->assign('data', $data);
        // 创建分页
        $page = new Page($page, $count, 10);
        $this->assign('page', $page->getHtml());
    }

    /**
     * 添加用户
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function addUserAction()
    {
        // @todo 添加用户验证
        if ( $_SESSION['level'] != 1 ) {
            return $this->error(100001004, $this->errorMsg);
        }
        if ( $this->isAjax() ) {
            // 校验规则.
            $this->rule['user_name'] = v::alnum('_-')
                ->length(3, 20)
                ->notEmpty()
                ->setTemplate('必须是字符,数字_-');
            $this->rule['real_name'] = v::regex('/^[\x{4e00}-\x{9fa5}A-Za-z0-9_\- ()（）、－\&\/\+\.\[\]\,\*\#\，\—\“]+$/u')
                ->length(null, 20)
                ->setTemplate('必须是中文数字_- ()（）、－&/+.[],*');
            $this->rule['password']  = v::stringVal()
                ->length(6, 18)
                ->notEmpty()
                ->setTemplate('密码不正确，必须大于6位小于18位');
            $this->rule['email']     = v::email()
                ->notEmpty()
                ->setTemplate('邮箱格式不正确');
            // 获取数据.
            $userName = $this->post('user_name');
            $realName = $this->post('real_name');
            $password = $this->post('password');
            // $email = $this->post('email');
            // 检测校验结果
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            // 判断用户是否存在
            if ( $data = UserModel::instance()
                ->getRowByUserName($userName)
            ) {
                return $this->error(100001001, ['id' => $data['id']]);
            }
            // 添加用户
            $id = UserModel::instance()
                ->addUser($userName, $realName, $password, 2);
            if ( $id ) {
                // 写入日志
                LogModel::instance()
                    ->add(1, $id, $_SESSION['id'], '添加用户', $this->getClientIp(), []);

                return $this->success();
            } else {
                return $this->error(100000002);
            }
        }

    }

    /**
     * 修改密码.
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function editPasswordAction()
    {
        if ( $this->errorMsg ) {
            return $this->error(100000004, ['old_password'=>'密码错误，']);
        }
        if ( $this->isAjax() ) {
            $this->rule['old_password'] = v::stringVal()
                ->length(6, 18)
                ->notEmpty()
                ->setTemplate('密码不正确，必须大于6位小于18位');
            $this->rule['new_password'] = v::stringVal()
                ->length(6, 18)
                ->notEmpty()
                ->setTemplate('密码不正确，必须大于6位小于18位');
            $password                   = $this->post('old_password');
            $newPassword                = $this->post('new_password');
            // 检测密码格式是否正确
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            // 检测旧密码是否正确.
            if ( !UserModel::instance()
                ->checkPassword($password, $_SESSION['password'])
            ) {
                return $this->error(100001005);
            }
            // 更新密码
            if ( UserModel::instance()
                ->putPasswordById($_SESSION['id'], $newPassword)
            ) {
                // 写入日志
                LogModel::instance()
                    ->add(1, $_SESSION['id'], $_SESSION['id'], '修改自己密码', $this->getClientIp(), []);
                // 消耗登录
                $_SESSION = [];
                session_destroy();

                return $this->success();
            }

            return $this->error(100000002);
        }

        return $this->goHome();
    }

    /**
     * 重置用户密码
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function resetPasswordAction()
    {
        if ( $this->isAjax() ) {
            // 判断是否有权限修改用户密码
            if ( $_SESSION['level'] != 1 ) {
                return $this->error(100000002);
            }
            $this->rule['id'] = v::intVal()
                ->min(1, false)
                ->notEmpty()
                ->setTemplate('用户Id错误');

            $id       = $this->post('id');
            $str      = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
            $password = substr(str_shuffle($str), 15, 10);
            // 检测密码格式是否正确
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }

            // 更新密码
            if ( UserModel::instance()
                ->putPasswordById($id, $password)
            ) {
                // 写入日志
                LogModel::instance()
                    ->add(1, $id, $_SESSION['id'], '重置他人密码', $this->getClientIp(), []);

                return $this->success(['password' => $password]);
            }

            return $this->error(100000002, $id);
        }

        return $this->goHome();
    }

    /**
     * 修改用户状态
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function stateAction()
    {
        if ( $this->isAjax() ) {
            // 判断是否有权限修改用户密码
            if ( $_SESSION['level'] != 1 ) {
                return $this->error(100000002);
            }
            $this->rule['id'] = v::intVal()
                ->min(1, false)
                ->notEmpty()
                ->setTemplate('用户Id错误');

            $id = $this->post('id');
            // 检测密码格式是否正确
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            $data = UserModel::instance()
                ->getRowById($id);
            if ( $data['state'] == 1 ) {
                $state    = 2;
                $stateStr = '停用';
            } else {
                $state    = 1;
                $stateStr = '启用';

            }

            // 更新用户状态
            if ( UserModel::instance()
                ->putStateById($id, $state)
            ) {
                // 写入日志
                LogModel::instance()
                    ->add(1, $id, $_SESSION['id'], '修改用户状态[' . $stateStr . ']', $this->getClientIp(), []);

                return $this->success(['state' => $stateStr]);
            }

            return $this->error(100000002, $id);
        }

        return $this->goHome();
    }

    /**
     * 用户退出.
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function logOutAction()
    {

        return $this->goHome();
    }

    /**
     * 回调到首页
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    protected function goHome()
    {
        $_SESSION = [];
        session_destroy();
        Y::disableView();

        return $this->redirect("/manage/account/index");
    }

}