<?php
class  Attribute_model extends  Base_Model
{
    protected  $tableName='attribute';

    /**
     * 根据商品类型id关联商品类型表(goods_type)
     * @param int $goods_type_id 商品类型ID
     * @return array
     */
    public function sel($goods_type_id='')
    {
        return $this->db
            ->where([$this->tableName.'.goods_type_id'=>$goods_type_id])
            ->join('goods_type',"goods_type.goods_type_id=$this->tableName.goods_type_id")
            ->get($this->tableName)->result_array();
    }

    /**
     * 查询商品类型结果集
     * @return array
     */
    public function get_types()
    {
        return $this->db ->get('goods_type')->result_array();
    }

    /**
     * 根据商品类型ID查询商品属性参数
     * @param  int $goods_type_id 商品类型id
     * @param  int $attr_type     属性类型
     * return  array
     */
    public function sel_attr($goods_type_id,$attr_type)
    {
     $_where=['goods_type_id'=>$goods_type_id,'attr_type'=>$attr_type];
     $attr_list=$this->db->where($_where)->get($this->tableName)->result_array();
     return $attr_list;
    }

}