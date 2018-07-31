<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/8
 * Time: 下午4:39
 */

/**
 *  */
class PokerModel extends \Base\BaseModels
{

    /*public function add(){
        $db = $this->getDataBase(false);
        $sql = "insert into `xuan_poker`.`poker_list` ( `update_time`, `uid`, `pic`, `title`, `poker_type`, `num`, `state`) values ( '2018-07-08 16:35:55', '1', '', '', :pokerType, :num, '1')";
        $res = $db->createSql($sql);

        for($i = 1; $i<14;$i++){
            for($j=1;$j<5;$j++){
                $query = [
                    ':pokerType' => $j,
                    ':num' => $i
                ];
                $res->query($query);
            }
        }
    }*/

    /**
     * 获取扑克列表.
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    public function getList(): array
    {
        $db  = $this->getDataBase();
        $sql = 'SELECT id,uid,title,pic,num,poker_type,update_time,state,des FROM poker_list ORDER BY id DESC ';

        return $db->createSql($sql)
            ->query()
            ->fetchAll();

    }

    /**
     * 根据id获取一条数据
     *
     * @Author : whoSafe
     *
     * @param int $id 扑克Id.
     *
     * @return mixed
     */
    public function getRowById(int $id): array
    {
        $db    = $this->getDataBase();
        $sql   = 'SELECT id,uid,title,pic,num,poker_type,des,update_time,state FROM poker_list WHERE id = :id';
        $query = [
            ':id' => $id
        ];

        return $db->createSql($sql)
            ->query($query)
            ->fetch();
    }
    /**
     * 根据id获取一条数据
     *
     * @Author : whoSafe
     *
     * @param string $title 名称.
     *
     * @return mixed
     */
    public function getRowByTitle(string $title): array
    {
        $db    = $this->getDataBase();
        $sql   = 'SELECT id FROM poker_list WHERE title = :title';
        $query = [
            ':title' => $title
        ];

        return $db->createSql($sql)
            ->query($query)
            ->fetch();
    }

    /**
     * 更新扑克信息.
     *
     * @Author : whoSafe
     *
     * @param int    $id    扑克Id.
     * @param string $title 标题.
     * @param string $pic   图片.
     * @param string $des   描述.
     * @param int $state   状态：1 正常，2 删除.
     *
     * @return int
     */
    public function putRowById(int $id, string $title, string $pic,string $des,int $state = 2): int
    {
        $db    = $this->getDataBase(false);
        $sql   = 'UPDATE poker_list SET title = :title,pic= :pic,update_time = :updateTime,des = :des,state = :state WHERE id = :id';
        $query = [
            ':id'         => $id,
            ':title'      => $title,
            ':pic'        => $pic,
            ':des'        => $des,
            ':state'        => $state,
            ':updateTime' => date('Y-m-d H:i:s')
        ];

        return $db->createSql($sql)
            ->query($query)
            ->rowCount();
    }

}