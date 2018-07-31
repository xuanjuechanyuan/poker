<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/24
 * Time: 下午2:27
 */

/**
 *  */
class LogModel extends \Base\BaseModels
{

    /**
     * 添加日志。
     *
     * @Author : whoSafe
     *
     * @param int   $fromType 来源：1：用户，2：poker
     * @param int   $fromId 来源Id
     * @param int   $uid 操作用户Id
     * @param string   $msg 操作描述
     * @param string   $ip 操作ip
     *
     * @param array $des 描述信息
     *
     * @return mixed
     */
    public function add(int $fromType,int $fromId,int $uid,string $msg,string $ip,array $des){
        $db = $this->getDataBase(false);
        $sql = 'INSERT INTO log(from_type,from_id,uid,msg,des,ip) VALUE (:fromType,:fromId,:uid,:msg,:des,:ip)';
        $query = [
            ':fromType'=>$fromType,
            ':fromId' => $fromId,
            ':uid' => $uid,
            ':msg' => $msg,
            ':des' => json_encode($des,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ':ip' => $ip,
        ];
        return $db->createSql($sql)
            ->query($query)
            ->lastInsertId();
    }

    /**
     * 获取日志列表.
     *
     * @Author : whoSafe
     *
     * @param int $offset 便宜量.
     * @param int $limit  获取条数.
     *
     * @return array
     */
    public function getLogList(int $offset, int $limit): array
    {
        $db = $this->getDataBase();
        $sql = 'SELECT id,from_type,from_id,uid,msg,create_time FROM log ORDER BY id DESC LIMIT :offset,:limit';
        $query = [
            ':offset' => $offset,
            ':limit'  => $limit,
        ];

        return $db->createSql($sql)
            ->query($query)
            ->fetchAll();
    }

    /**
     * 获取用户总条数.
     *
     * @Author : whoSafe
     *
     * @return int
     */
    public function getTotal()
    {
        $db  = $this->getDataBase();
        $sql = 'SELECT count(id) as c FROM log';

        $data = $db->createSql($sql)
            ->query()
            ->fetch();
        if ( $data ) {
            return $data['c'];
        }

        return 0;
    }

}