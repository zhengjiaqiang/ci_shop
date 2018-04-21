<?php
class  Type_model extends  Base_Model
{
    protected $tableName='goods_type';
    /**
     * 查询商品类型结果集
     * @return array
     */
    public function get_types()
    {
        return $this->db ->get($this->tableName)->result_array();
    }
}