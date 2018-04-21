<?php
class  Cart extends  Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model', 'cat');
        $this->load->model('Goods_model', 'goods');
        $this->load->model('Product_model', 'product');
        $this->load->model('Cart_model', 'cart');
    }
    //购物车
    public function add_cart()
    {
        /**
         * 首先判断用户是否登录,然后根据商品id查询商品信息,再根据商品id和商品属性id查询货品信息
         * 接着判断货品是否充足,如果货品充足，在判断库存是否充足,如果货品不充足，则按单个商品购买
         **/
        $user_id=$this->session->userdata('user_id');//用户id
        $goods_id=intval($this->input->get('goods_id'));//商品id
        $buy_number=$this->input->get('buy_number');//商品id
        $goods_attr_id=$this->input->get('goods_attr_id');//商品id
        $cart_data['user_id']=$user_id;
        $cart_data['buy_number']=$buy_number;
        $cart_data['goods_id']=$goods_id;
        //根据商品id获取商品详细信息
        $fields="goods_id,goods_sn,goods_name,market_price,shop_price";
        $goods_info=$this->goods->find($goods_id,$fields);
        $cart_data['goods_name']=$goods_info['goods_name'];
        $cart_data['goods_sn']=$goods_info['goods_sn'];
        $cart_data['market_price']=$goods_info['market_price'];
        //查询货品id(详细信息)
        $product_info=$this->product->get_product($goods_id,$goods_attr_id);
        if($product_info)
        {
            //货品库存是否充足
            if($product_info['sku']<$buy_number)
            {

            $this->ajax_return(0,'没有库存了');
            }
            else
            {
                //如果库存充足按货品价格购买
              $cart_data['goods_price']=$product_info['goods_price'];
              $cart_data['goods_attr_id']=$goods_attr_id;
              $cart_data['product_id']=$product_info['product_id'];
              $cart_data['goods_sn']=$product_info['product_sn'];
              //$this->ajax_return(1,'购买货品成功');
            }
        }
        else
        {
            $this->ajax_return(0,'没有货品了');
             /*没有货品按商品购买*/
            //判断商品库存是否充足 是否上下架 是否在回收站
            $cart_data['goods_price']=$goods_info['shop_price'];

        }
         $map['goods_id']=$goods_id;
         $map['user_id']=$user_id;
         $map['goods_attr_id']=$goods_attr_id;
         //判断购物车中是否有数据有则更新，无则添加
         $cart_exists=$this->cart->cart_exist($map);
        if($cart_exists)
        {
           $cart_id=$cart_exists['cart_id'];
           $buy_number=$cart_exists['buy_number']+$buy_number;
           $bool=$this->cart->save($cart_id,$buy_number);
           if($bool)
           {
               //购物车总计
               $cart_totals=$this->cart->cart_total();
               $this->ajax_return(1,'购买成功',$cart_totals);
           }
        }
        else
        {
            $cart_id=$this->cart->add($cart_data);
            if($cart_id)
            {
                //购物车总计
                $cart_totals=$this->cart->cart_total();
                $this->ajax_return(1,'购买成功',$cart_totals);
            }
            else
            {
                $this->ajax_return(0,'购买失败');
            }
        }

    }
     //去购物车结算
     public function index()
     {
         //购物车总计
         $cart_info=$this->cart->cart_total();
         //p($cart_info);
         $this->load->view('home/buy_car.html',['cart_info'=>$cart_info]);
     }
    /**
     * AJAX返回
     * @param int  $status
     * @param string $msg
     * @param array $data  数据
     * return JSON
     */
    public function ajax_return($status,$msg='',$data='')
    {
        $arr=array(
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data
        );
        exit(json_encode($arr));
    }
}