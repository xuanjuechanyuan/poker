<?php
/**
 * Created by PhpStorm.
 *
 * @author 曾洪亮<zenghongl@126.com>
 * @email  zenghongl@126.com
 * User: whoSafe
 * Date: 2018/7/6
 * Time: 下午2:41
 */

/**
 *  */
class Page
{
    private $url       = 0;  // 访问的url
    private $page      = 0;  // 当前访问页码.
    private $total     = 0;  // 总条数.
    private $totalPage = 0;  // 总页数.
    private $pageSize  = 0;  // 没有条数.
    private $query     = []; // 参数.

    /**
     * Page constructor.
     *
     * @param int $page     当前页码.
     * @param int $total    总条数.
     * @param int $pageSize 每页条数.
     */
    public function __construct(int $page, int $total, int $pageSize)
    {
        $this->url   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query = $_GET;
        unset($this->query['page'], $this->query[ $this->url ]);
        $this->page      = $page > 1 ? $page : 1;
        $this->total     = $total;
        $this->pageSize  = $pageSize;
        $this->totalPage = ceil($total / $pageSize);
        $this->page      = $this->page > $this->totalPage ? $this->totalPage : $this->page;
    }

    /**
     * 获取Html代码.
     *
     * @Author : whoSafe
     *
     * @return mixed|string
     */
    public function getHtml()
    {
        $str = $this->getDes();
        $str .= "<div class='dataTables_paginate paging_simple_numbers'><ul class='pagination'>";
        $str .= $this->getIndex();
        $str .= $this->getPrev();
        $str .= $this->getNext();
        $str .= $this->getLast();
        $str .= " </ul></div></div>";

        return $str;
    }

    /**
     * 获取分页的首页和第一页.
     *
     * @Author : whoSafe
     *
     * @return string
     */
    private function getIndex()
    {
        if ( $this->page == 1 ) {
            $str = "<li class='paginate_button previous'><a href='javascript:void(0)'>首页</a></li>";
            $str .= "<li class='paginate_button previous'><a href='javascript:void(0)'>上一页</a></li>";
        } else {
            $str = sprintf("<li class='paginate_button previous'><a href='%s'>首页</a></li>", $this->getUri(1));
            $str .= sprintf("<li class='paginate_button previous'><a href='%s'>上一页</a></li>", $this->getUri($this->page - 1));
        }

        return $str;
    }

    /**
     * 获取当前页码之前的分页html
     *
     * @Author : whoSafe
     *
     * @return string
     */
    private function getPrev()
    {
        $endNum = $this->totalPage - $this->page;
        if ( $endNum < 3 && $endNum >= 0 ) {
            $num = 6 - $endNum;
        } else {
            $num = 3;
        }
        $statPage = $this->page - $num;
        $statPage = $statPage > 0 ? $statPage : 1;
        $str      = '';
        for ( ; $statPage < $this->page; $statPage++ ) {
            $str .= sprintf("<li class='paginate_button previous'><a href='%s'>%s</a></li>", $this->getUri($statPage), $statPage);
        }

        $str .= sprintf("<li class='paginate_button active disabled'><a href='javascript:void(0)'>%s</a></li>", $this->page);

        return $str;
    }

    /**
     * 获取页码之后的Html
     *
     * @Author : whoSafe
     *
     * @return string
     */
    private function getNext()
    {
        $endNum = $this->page - 1;
        if ( $endNum < 3 && $endNum >= 0 ) {
            $num = 6 - $endNum;
        } else {
            $num = 3;
        }
        $page    = $this->page + 1;
        $endPage = $this->page + $num;
        $endPage = $endPage < $this->totalPage ? $endPage : $this->totalPage;
        $str     = '';
        for ( ; $page <= $endPage; $page++ ) {
            $str .= sprintf("<li class='paginate_button previous'><a href='%s'>%s</a></li>", $this->getUri($page), $page);
        }

        return $str;
    }

    /**
     * 获取结束和下一页的Html
     *
     * @Author : whoSafe
     *
     * @return string
     */
    private function getLast()
    {

        if ( $this->page == $this->totalPage ) {
            $str = "<li class='paginate_button previous'><a href='javascript:void(0)'>下一页</a></li>";
            $str .= "<li class='paginate_button previous'><a href='javascript:void(0)'>末尾页</a></li>";
        } else {
            $str = sprintf("<li class='paginate_button previous'><a href='%s'>下一页</a></li>", $this->getUri($this->page + 1));
            $str .= sprintf("<li class='paginate_button previous'><a href='%s'>末尾页</a></li>", $this->getUri($this->totalPage));
        }

        return $str;
    }

    /**
     * 获取描述信息.
     *
     * @Author : whoSafe
     *
     * @return mixed
     */
    private function getDes()
    {
        $str      = "<div class='col-sm-6'><div class='dataTables_info' >当前第%s页 共%s页，显示%s 到 %s 项，共 %s 项</div> </div>";
        $startNum = ( $this->page - 1 ) * $this->pageSize;
        $startNum = ( $startNum > 0 ? $startNum : 1 );
        $endNum   = $this->page * $this->pageSize;

        return sprintf($str, $this->page, $this->totalPage, $startNum, $endNum, $this->total);
    }

    /**
     * 获取url地址
     *
     * @Author : whoSafe
     *
     * @param int $page 页码.
     *
     * @return mixed
     */
    private function getUri($page)
    {
        $this->query['page'] = $page;

        return $this->url . '?' . http_build_query($this->query);
    }
}