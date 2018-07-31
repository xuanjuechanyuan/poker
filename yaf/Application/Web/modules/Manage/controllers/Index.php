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

use \Respect\Validation\Validator as v;

/**
 *  */
class IndexController extends Base\BaseControllers
{
    /**
     * 首页
     *
     * @Author : whoSafe
     *
     */
    public function indexAction()
    {

        $this->assign('userName', $_SESSION['user_real']);
        switch ( $_SESSION['level'] ) {
            case 1:
                $levalName = '超级管理员';
                break;
            case 2:
                $levalName = '管理员';
                break;
        }
        $this->assign('level', $levalName);
    }

    /**
     * 首页列表.
     *
     * @Author : whoSafe
     *
     */
    public function homeAction()
    {

        $fromType = [
            1 => '用户',
            2 => '扑克'
        ];
        $this->assign('fromType', $fromType);
        $this->rule['page'] = v::intVal()
            ->min(1);
        $page               = $this->get('page', 1);
        $pageSize           = 10;
        // 获取总条数.
        $count  = LogModel::instance()
            ->getTotal();
        $offset = ( $page - 1 ) * $pageSize;
        // 获取用户列表
        $data = LogModel::instance()
            ->getLogList($offset, $pageSize);
        $this->assign('data', $data);
        // 创建分页
        $page = new Page($page, $count, 10);
        $this->assign('page', $page->getHtml());
    }

}