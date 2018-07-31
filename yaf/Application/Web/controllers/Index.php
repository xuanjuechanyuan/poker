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

/**
 *  */
class IndexController extends Base\BaseControllers
{
    // 扑克编号.
    public $pokerList = array (
        14 => 'J O K E R',
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
        1  => 'A',
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

    public function indexAction(){

        $data = PokerModel::instance()->getList();

        $this->assign('data',$data);
        $this->assign('pokerTypeStr', $this->pokerTypeStrOne);
        $this->assign('pokerList', $this->pokerList);
    }

}