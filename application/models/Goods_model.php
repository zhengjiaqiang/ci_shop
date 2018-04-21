<?php
class  Goods_model extends  Base_Model
{
    protected $tableName='goods';
    /**
     * 查询热门商品
     */
    public function get_goods_hot()
    {
        $_where=['is_best'=>1,'is_delete'=>0,'is_on_sale'=>1];
        $fields='goods_id,brand_id,goods_name,goods_brief,shop_price';
        return $this->db->select($fields)->where($_where)->get($this->tableName)->result_array();
    }

    /**
     * @param array $son_cats_ids 分类id
     */
    public function first_floor($son_cats_ids)
    {
        $_where=['is_delete'=>0,'is_on_sale'=>1];
        $fields='goods_id,goods_name,goods_brief,shop_price';
         return $this->db->select($fields)->where($_where)->where_in('cat_id',$son_cats_ids)->get($this->tableName)->result_array();
        //echo $this->db->last_query();

    }

    /**
     * 筛选品牌
     * @param $cat_sons_ids
     */
    public function get_filter_brand($cat_sons_ids)
    {
     $sql="SELECT g.brand_id,brand_name FROM ci_goods AS g,ci_brand as b  WHERE g.brand_id=b.brand_id AND g.cat_id in(".db_create_in($cat_sons_ids).")  GROUP BY brand_id";
     return$this->db->query($sql)->result_array();
     //echo $this->db->last_query();
    }

    /**
     * 查询商品详细信息
     * @param int $goods_id 商品id
     * return array   一维数组
     */
    public function find($goods_id,$fields='*')
    {
        $goods_info=$this->db->select($fields)->where('goods_id',$goods_id)->get($this->tableName)->row_array();
        return $goods_info;
    }

    /**
     * 查询商品属性(参数、规格)
     * @param int $goods_id
     */
    public function property($goods_id)
    {
     $sql="SELECT goods_attr_id,goods_id,ga.attr_id,attr_name,attr_value,attr_type FROM ci_goods_attr AS ga  LEFT JOIN ci_attribute AS a ON ga.attr_id=a.attr_id WHERE goods_id=1";
     $property_list=$this->db->query($sql)->result_array();
     //p($property_list);
     $temp_attr=[];
     $temp_spec=[];
     foreach ($property_list as $k=>$v)
     {
         //商品规格
        if($v['attr_type'])
        {
            $temp_spec[$v['attr_id']]['attr_name']=$v['attr_name'];
            $temp_spec[$v['attr_id']]['attr_value'][$v['goods_attr_id']]=$v['attr_value'];
        }
        else
        {
          $temp_attr[$k]['attr_name']=$v['attr_name'];
          $temp_attr[$k]['attr_value']=$v['attr_value'];
        }
     }
      return ['attr_list'=>$temp_attr,'spec_list'=>$temp_spec];
    }

}