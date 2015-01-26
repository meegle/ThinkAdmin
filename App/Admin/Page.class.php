<?php
/**
 * 分页类
*/
class Page {
    public     $firstRow;        //起始行数
    public     $listRows;        //列表每页显示行数
    protected  $total_pages;      //总页数
    protected  $total_rows;       //总行数
    protected  $now_page;         //当前页数
    protected  $method  = 'defalut'; //处理情况 Ajax分页 Html分页(静态化时) 普通get方式 
    protected  $parameter = '';
    protected  $page_name;        //分页参数的名称
    protected  $ajax_func_name;
    protected  $plus = 4;         //分页偏移量
    protected  $url;
    
    /**
     * 构造函数
     * @param unknown_type $data
     */
    public function __construct($totalRows, $listRows = '', $data = array()) {
        $this->total_rows = $totalRows;
        if (!empty($listRows)) {
            $this->listRows = intval($listRows);
        }

        $this->parameter        = !empty($data['parameter']) ? $data['parameter'] : '';
        $this->listRows         = !empty($this->listRows) && $this->listRows <= 100 ? $this->listRows : 20;
        $this->total_pages      = ceil($this->total_rows / $this->listRows);
        $this->page_name        = !empty($data['page_name']) ? $data['page_name'] : 'p';
        $this->ajax_func_name   = !empty($data['ajax_func_name']) ? $data['ajax_func_name'] : '';
        
        $this->method           = !empty($data['method']) ? $data['method'] : '';
        
        /* 当前页面 */
        if (!empty($data['now_page'])) {
            $this->now_page = intval($data['now_page']);
        } else {
            $this->now_page = !empty($_GET[$this->page_name]) ? intval($_GET[$this->page_name]):1;
        }
        $this->now_page   = $this->now_page <= 0 ? 1 : $this->now_page;

        if (!empty($this->total_pages) && $this->now_page > $this->total_pages) {
            $this->now_page = $this->total_pages;
        }
        $this->firstRow = $this->listRows * ($this->now_page - 1);
    }
    
    /**
     * 得到当前连接
     * @param $page
     * @param $text
     * @return string
     */
    protected function _get_link($page, $text, $extr = '') {
        switch ($this->method) {
            case 'ajax':
                $parameter = '';
                if($this->parameter) {
                    $parameter = ','.$this->parameter;
                }
                return '<a onclick="' . $this->ajax_func_name . '(\'' . $page . '\''.$parameter.')" href="javascript:void(0)"'.$extr.'>' . $text . '</a>' . "\n";
            break;
            case 'html':
                $url = str_replace('?', $page,$this->parameter);
                return '<a href="' .$url . '"'.$extr.'>' . $text . '</a>' . "\n";
            break;
            default:
                return '<a href="' . $this->_get_url($page) . '"'.$extr.'>' . $text . '</a>' . "\n";
            break;
        }
    }
    
    /**
     * 设置当前页面链接
     */
    protected function _set_url() {
        $url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if (isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$this->page_name]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        if(!empty($params)) {
            $url .= '&';
        }
        $this->url = $url;
    }
    
    /**
     * 得到$page的url
     * @param $page 页面
     * @return string
     */
    protected function _get_url($page) {
        if ($this->url === NULL) {
            $this->_set_url();
        }
    //  $lable = strpos('&', $this->url) === FALSE ? '' : '&';
        return $this->url . $this->page_name . '=' . $page;
    }
    
    /**
     * 得到第一页
     * @return string
     */
    protected function first_page($name = '第一页') {
        if ($this->now_page > 5) {
            return $this->_get_link('1', $name);
        }
        return '';
    }
    
    /**
     * 最后一页
     * @param $name
     * @return string
     */
    protected function last_page($name = '最后一页') {
        if($this->now_page < $this->total_pages - 5) {
            return $this->_get_link($this->total_pages, $name);
        }
        return '';
    }
    
    /**
     * 上一页
     * @return string
     */
    protected function up_page($name = '上一页') {
        if ($this->now_page != 1) {
            return $this->_get_link($this->now_page - 1, $name);
        }
        return '';
    }
    
    /**
     * 下一页
     * @return string
     */
    protected function down_page($name = '下一页') {
        if ($this->now_page < $this->total_pages) {
            return $this->_get_link($this->now_page + 1, $name);
        }
        return '';
    }

    /**
     * 分页样式输出
     * @return string
     */
    public function show() {
        if ($this->total_rows < 1) {
            return '';
        }
        $className = 'show_' . strtolower(MODULE_NAME);
        $classNames = get_class_methods($this);
        if (in_array($className, $classNames)) {
            return $this->$className();
        }
        return '';
    }
    
    protected function show_() {
        $plus = $this->plus;
        if ($plus + $this->now_page > $this->total_pages) {
            $begin = $this->total_pages - $plus * 2;
        } else {
            $begin = $this->now_page - $plus;
        }
        
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return .= $this->first_page();
        $return .= $this->up_page();
        for ($i = $begin;$i <= $begin + $plus * 2;$i++) {
            if ($i > $this->total_pages) {
                break;
            }
            if ($i == $this->now_page) {
                $return .= "<a class='now_page'>$i</a>\n";
            } else {
                $return .= $this->_get_link($i, $i) . "\n";
            }
        }
        $return .= $this->down_page();
        $return .= $this->last_page();
        return $return;
    }
    
    protected function show_admin() {
        $plus = $this->plus;
        if ($plus + $this->now_page > $this->total_pages) {
            $begin = $this->total_pages - $plus * 2;
        } else {
            $begin = $this->now_page - $plus;
        }
        
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $first_page = $this->first_page();
        $return .= empty($first_page) ? '' : '<li>'.$first_page.'</li>';
        $up_page = $this->up_page();
        $return .= empty($up_page) ? '' : '<li>'.$up_page.'</li>';
        for ($i = $begin;$i <= $begin + $plus * 2;$i++) {
            if ($i > $this->total_pages) {
                break;
            }
            if($i == $this->now_page) {
                $return .= '<li class="active"><a>'.$i.'</a></li>'."\n";
            } else {
                $return .= '<li>'.$this->_get_link($i, $i).'</li>'."\n";
            }
        }
        $down_page = $this->down_page();
        $return .= empty($down_page) ? '' : '<li>'.$down_page.'</li>';
        $last_page = $this->last_page();
        $return .= empty($last_page) ? '' : '<li>'.$last_page.'</li>';
        return $return;
    }
}