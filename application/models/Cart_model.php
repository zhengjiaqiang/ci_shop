<?php
class  Cart_model extends  Base_Model
{
    protected $tableName = 'cart';
    /**
     * 清空购物车中（已买）商品
     */
    public function clear_cart()
    {
        $user_id=$this->session->userdata('user_id');
        return $this->db->where('user_id',$user_id)->delete($this->tableName);
    }
    /**
     * 查询购物车中的数据是否存在
     * @param array $map
     * return array
     */
    public function cart_exist($map)
    {
       return  $this->db->where($map)->get($this->tableName)->row_array();
    }

    /**
     * 更新购物车中的数据
     * @param $cart_id
     * @param $buy_number
     * return bool
     */
    public function save($cart_id,$buy_number)
    {
        $data['buy_number']=$buy_number;
        $bool= $this->db->where('cart_id',$cart_id)->update($this->tableName,$data);
        //echo $this->db->last_query();
        return $bool;
    }

    /**
     * 购物车总计
     */
    public function cart_total()
    {
      $user_id=$this->session->userdata('user_id');
      $cart_total=$this->db->where('user_id',$user_id)->get($this->tableName)->result_array();
       $nums=count($cart_total);//物种
        $buy_number=0;//件数
        $goods_price=0;//价格
      foreach ($cart_total as $k=>$v)
      {
        $buy_number=$buy_number+$v['buy_number'];
        $goods_price+=$v['goods_price']*$v['buy_number'];
        $cart_total[$k]['total']=$v['goods_price']*$v['buy_number'];//小计
        if(!empty($v['goods_attr_id']))
        {
            $sql="SELECT attr_name,attr_value FROM ci_goods_attr AS ga LEFT JOIN ci_attribute AS a  on ga.attr_id=a.attr_id WHERE goods_attr_id in($v[goods_attr_id])";
            $cart_total[$k]['goods_attr']=$this->db->query($sql)->result_array();
        }
      }
      return ['nums'=>$nums,'buy_number'=>$buy_number,'goods_price'=>$goods_price,'cart_list'=>$cart_total];
    }
    /*public function cart_property($goods_attr_id)
    {
        $sql="SELECT attr_name,attr_value FROM ci_goods_attr AS ga LEFT JOIN ci_attribute AS a  on ga.attr_id=a.attr_id WHERE goods_attr_id in($goods_attr_id)";
        return $this->db->query($sql)->result_array();
    }*/
}