<?php
class  Product_model extends  Base_Model
{
    protected $tableName= 'Products';

    /**
     * 查询可以组合的规格
     * @param $goods_id
     */
    public  function  get_spec($goods_id)
    {
        return $this->db->where('goods_id',$goods_id)->get($this->tableName)->result_array();
    }

    /**
     * 查询货品
     * @param  int $goods_id      商品id
     * @param  int $goods_attr_id 商品属性id
     */
    public function  get_product($goods_id,$goods_attr_id)
    {
       $_where=['goods_id'=>$goods_id,'attr_list'=>$goods_attr_id];
       return $this->db->where($_where)->get($this->tableName)->row_array();
    }
}