<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/8
 * Time: 下午4:22
 */

use \Respect\Validation\Validator as v;
use \BaseYaf as Y;

/**
 *  */
class PokerController extends \Base\BaseControllers
{
    // 扑克编号.
    public $pokerList = array (
        14 => 'JOKER',
        13 => 'K',
        12 => 'Q',
        11 => 'J',
        10 => '10',
        9  => '9',
        8  => '8',
        7  => '7',
        6  => '6',
        5  => '5',
        4  => '4',
        3  => '3',
        2  => '2',
        1  => '1',
    );
    // 扑克花色.
    public $pokerTypeStr = [
        1 => '<span style="color: red;font-size: 20px;">♥</span>',
        2 => '<span style="color: black;font-size: 20px;">♠</span>',
        3 => '<span style="color: red;font-size: 20px;">♦</span>',
        4 => '<span style="color: black;font-size: 20px;">♣</span>',
        5 => '<span style="color: red;">大王</span>',
        6 => '<span style="color: black;">小王</span>',
    ];

    public $pokerTypeStrOne = [
        1 => '<span style="color: red;">♥</span>',
        2 => '<span style="color: black;">♠</span>',
        3 => '<span style="color: red;">♦</span>',
        4 => '<span style="color: black;">♣</span>',
        5 => '',
        6 => '',
    ];


    /**
     * 获取扑克列表.
     *
     * @Author : whoSafe
     *
     */
    public function getListAction()
    {
        $this->assign('pokerTypeStr', $this->pokerTypeStr);
        $this->assign('pokerList', $this->pokerList);
        $data = PokerModel::instance()
            ->getList();

        $this->assign('data', $data);
    }

    /**
     * 编辑扑克.
     *
     * @Author : whoSafe
     *
     */
    public function editPokerAction()
    {
        $this->rule['id'] = v::intVal()
            ->min(1)
            ->notEmpty()
            ->setTemplate('用户Id错误');

        $id = $this->get('id');
        // 检测密码格式是否正确
        if ( $this->errorMsg ) {
            Y::disableView();

            return $this->redirect("/manage/poker/getlist");
        }
        $data = PokerModel::instance()
            ->getRowById($id);
        $this->assign('pokerTypeStr', $this->pokerTypeStrOne);
        $this->assign('pokerList', $this->pokerList);
        $this->assign('data', $data);

    }

    /**
     * 重置扑克信息
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function resetPokerAction()
    {
        if ( $this->isAjax() ) {
            $this->rule['id'] = v::intVal()
                ->min(1)
                ->notEmpty()
                ->setTemplate('用户Id错误');

            $id = $this->post('id');

            // 检测密码格式是否正确
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            // 获取对应的poker信息
            $data = PokerModel::instance()
                ->getRowById($id);
            if ( $data ) {
                if ( PokerModel::instance()
                    ->putRowById($id, '', '', '', 2)
                ) {
                    // 写入日志
                    $logDes = [
                        'title' => $data['title'],
                        'pic'   => $data['pic'],
                        'des'   => $data['des']
                    ];
                    LogModel::instance()
                        ->add(2, $id, $_SESSION['id'], '清除poker信息', $this->getClientIp(), $logDes);

                    return $this->success();
                }
            }

        }

        return $this->error(100000002);
    }

    /**
     * 编辑poker信息
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function ajaxEditAction()
    {
        if ( $this->isAjax() ) {
            $this->rule['id']    = v::intVal()
                ->min(1)
                ->notEmpty()
                ->setTemplate('用户Id错误');
            $this->rule['title'] = $this->getCustomRule('string')
                ->setTemplate('标题只能是中文、数字');
            $this->rule['des']   = $this->getCustomRule('string')
                ->setTemplate('描述只能是中文、数字');
            $this->rule['pic']   = v::alnum('/_.')
                ->notEmpty()
                ->setTemplate('图片不能为空');

            $id    = $this->post('id');
            $title = $this->post('title');
            $des   = $this->post('des');
            $pic   = $this->post('pic');
            // 检测密码格式是否正确
            if ( $this->errorMsg ) {
                return $this->error(100000004, $this->errorMsg);
            }
            $info = PokerModel::instance()
                ->getRowByTitle($title);
            if($info && $info['id'] != $id){
                return $this->error(100000004, ['title' => '名称已经存在']);
            }
            // 获取对应的poker信息
            $data = PokerModel::instance()
                ->getRowById($id);

            if ( $data ) {
                if(!$data['title']){
                    $data['title'] = $title;
                }
                if ( PokerModel::instance()
                    ->putRowById($id, $data['title'], $pic, $des, 1)
                ) {
                    // 写入日志
                    $logDes = [
                        'title' => $data['title'],
                        'pic'   => $data['pic'],
                        'des'   => $data['des']
                    ];
                    LogModel::instance()
                        ->add(2, $id, $_SESSION['id'], '变更poker信息', $this->getClientIp(), $logDes);

                    return $this->success();
                }
            }
        }

        return $this->error(100000002);
    }

}