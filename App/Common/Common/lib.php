<?php

//获取全局配置
function get_global_setting() {
    $list = array();
    if (!F('global_setting')) {
        $list_t = m('setting')->field('code, text')->select();
        foreach ($list_t as $key => $v) {
            $list[$v['code']] = de_xie($v['text']);
        }
        F('global_setting', $list);
    } else {
        $list = F('global_setting');
    }
    return $list;
}

//数据->配置文件 / 配置文件->数据
function FS($filename, $data = "", $path = "") {
    $path = __ROOT__.$path;
    if ($data == "") {
        $f = explode("/", $filename);
        $num = count($f);
        if (2 < $num) {
            $fx = $f;
            array_pop($f);
            $pathe = implode("/", $f);
            $re = F($fx[$num - 1], "", $pathe . "/");
        } else {
            isset($f[1]) ? ($re = F($f[1], "", __ROOT__.$f[0]."/")) : ($re = F($f[0]));
        }
        return $re;
    } else if (!empty($path)) {
        $re = F($filename, $data, $path);
    } else {
        $re = F($filename, $data);
    }
}

//字符串/数组转义
function de_xie($arr) {
    $data = array();
    if (is_array($arr)) {
        foreach ($arr as $key => $v) {
            if (is_array($v)) {
                foreach ($v as $skey => $sv) {
                    if (is_array($sv)) {
                    } else {
                        $v[$skey] = stripslashes($sv);
                    }
                }
                $data[$key] = $v;
            } else {
                $data[$key] = stripslashes($v);
            }
        }
    } else {
        $data = stripslashes($arr);
    }
    return $data;
}

//删除文件夹
function rmdirr($dirname) {
    if (!file_exists($dirname)) {
        return false;
    }
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }
    $dir = dir($dirname);
    while (false !== ($entry = $dir->read())) {
        if ($entry == "." || $entry == "..") {
            continue;
        }
        rmdirr($dirname.DIRECTORY_SEPARATOR.$entry);
    }
    $dir->close();
    return rmdir($dirname);
}

/**
 * 说明：字符串截取函数（大写字母算一个，英文及其标点算半个）
 * ( STRING ) $sourcestr // 要截取的字符串
 * ( INT ) $start // 开始截取的位置
 * ( INT ) $cutlength // 截取长度
 * ( BOOL | STRING ) $tail // 是否在末尾加上尾巴（默认是省略号（3个点）
 * ( BOOL) $stripTags // 是否在是否在截取前过滤HTML标签
 */
function cutStr($sourcestr = '', $start = 0,  $cutlength = 0, $tail = true, $stripTags = true) {
    if ($stripTags) $sourcestr = strip_tags($sourcestr);
    $returnstr = '';
    $i = 0; // 已经读取的字节数
    $n = 0; // 已经保留的字符数
    $s = 0; // 开始位置前的字节数
    $l = 0; // 开始位置前的字符数
    $begin = false; // 开始截取前的长度是否够
    $str_length = strlen($sourcestr); //字符串的总字节数
    // 处理接收的数据
    $sourcestr = (string)$sourcestr;
    $start = (int)abs($start);
    $cutlength = (int)abs($cutlength);
    while ($n < $cutlength) { // 长度是否需要截？是否够长(开始位置前的长度+已截取的长度)？
        // 记下开始位置的字节数
        if (false == $begin) {
            if ($start == $l) { // 是否到了该截取的开始位置
                $begin = TRUE;
                $s = $i; // 记下开始字节位置
                $preStrLen = $s; // 记下开始截前的字节
            }
            $temp_str = substr($sourcestr, $i, 1); // UTF-8编码的字符可能由1~3个字节组成，具体数目可以由第一个字节判断出来。
            $ascnum = Ord($temp_str); // 得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) { // 如果ASCII位高与224，
                $i = $i +3; // 实际Byte计为3
                $l++; // 字符串长度加壹
            } elseif ($ascnum >= 192) { // 如果ASCII位高与192，
                $i = $i +2; // 实际Byte计为2
                $l++;
            } elseif ($ascnum >= 65 && $ascnum <= 90) { // 如果是大写字母，
                $i = $i +1; // 实际的Byte数仍计1个
                $l++;
            } else { // 其他情况下，包括小写字母和半角标点符号，
                $i = $i +1; // 实际的Byte数计1个
                $l++;
            }
        }
        // 开始截取，（条件：已到开始截取的位置，并且截取的字符长度还不够）
        while (true == $begin && $cutlength > $n) {
            $temp_str = substr($sourcestr, $s, 1);
            $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) { //如果ASCII位高与224，
                $returnstr = $returnstr . substr($sourcestr, $s, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $s = $s +3; //实际Byte计为3
                $n++; //字串长度计1
            } else if ($ascnum >= 192) { //如果ASCII位高与192，
                $returnstr = $returnstr . substr($sourcestr, $s, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $s = $s +2; //实际Byte计为2
                $n++; //字串长度计1
            } else if ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
                $returnstr = $returnstr . substr($sourcestr, $s, 1);
                $s = $s +1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符（增强版函数，不考虑美观，直接算一个）
            } else { //其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . substr($sourcestr, $s, 1);
                $s = $s +1; //实际的Byte数计1个
                $n++; // 字母和半角标点也算一个字
            }
        }
    }
    if (!empty($tail) && false != $tail) { // 在末尾加上省略号
        if (true === $tail) {
            $tail = '...';
        } else {
            $tail = (string)$tail;
        }
        $cutlength = strlen($returnstr) + $preStrLen; // 截取长度 = 截取部分的长度 + 开头文字的长度
        if (0 < $start) {
            $preTail = $tail;
            $returnstr = $preTail . $returnstr;
        }
        if ($cutlength < $str_length) {
            $returnstr = $returnstr . $tail; //超过长度时在尾处加上省略号
        }
    }
    return $returnstr;
}

?>