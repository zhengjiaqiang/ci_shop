<?php
class  Goods_attr_model extends  Base_Model
{
    protected $tableName = 'goods_attr';
    public function __construct()
    {
        $this->config->load('set_config');//载入自定义配置文件
    }
}
