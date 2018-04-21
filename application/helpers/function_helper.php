<?php
/**
 * array $data
 * 自定义函数
 */
 function p($data)
 {
    echo '<pre/>';
    print_r($data);
    exit();
 }

/**
 * 构造SQL中的in查询方法
 * @param array $data 一维数组
 * @return string
 */
 function db_create_in($data)
 {
     $ids="'".implode("','",$data)."'";
     return $ids;
 }