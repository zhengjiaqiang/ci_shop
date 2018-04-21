<?php
class  Order extends  Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model', 'cat');
        $this->load->model('Goods_model', 'goods');
        $this->load->model('Product_model', 'product');
        $this->load->model('Region_model', 'region');
        $this->load->model('User_address_model', 'user_address');
        $this->load->model('Cart_model', 'cart');
        $this->load->model('Order_info_model', 'order_info');
        $this->load->model('Order_goods_model', 'order_goods');
    }
    /**
     * 确认订单信息
     * 1.首先判断用户是否 处于登录状态,如果用户是第一次购物要填写收货人的详细地址,再次购物时默认使用上一次地址
     */
     public function order_info()
     {
        $user_address=$this->session->userdata('user_address');
        $default_adress=$this->user_address->default_address();//默认地址
        if(!isset($user_address)&& !isset($default_adress))
        {
        echo '<h1><font color="red">请先填写详细地址...</font></h1>';
        $url=site_url('order/address');
        header("refresh:3 url=".$url);
        }
        else
        {
         $user_address=isset($user_address)?$user_address:$default_adress;
         $this->session->set_userdata('user_address',$user_address);
        }
        //购物车总计
        $cart_info=$this->cart->cart_total();
        //把购物车中的商品信息保存在session中
         $this->session->set_userdata('cart_info',$cart_info);
        //订单金额存入session中
         $shipping_fee=10;//配送费用
         $order_money=[];
         $order_amount=$cart_info['goods_price']+$shipping_fee;//应付款金额
        $order_money['goods_amount']=$cart_info['goods_price'];//商品总金额
        $order_money['order_amount']=$order_amount;
        $order_money['shipping_fee']=$shipping_fee;
        $this->session->set_userdata('order_money',$order_money);
        $data['user_address']=$user_address;
        $data['cart_info']=$cart_info;
        //把订单地址存入session中
        $this->load->view('home/order_info.html',$data);
     }

    /**
     * 收货人地址展示页面
     */
     public function address()
     {
        if(IS_POST)
        {
        $data=$this->input->post();
        $data['country']=$data['region'][0];//国家
        $data['province']=$data['region'][1];
        $data['city']=$data['region'][2];
        $data['district']=$data['region'][3];
        unset($data['region']);
        $data['user_id']=$this->session->userdata('user_id');
        $adress_id=$this->user_address->add($data);
        if($adress_id)
        {
        //保存用户详细地址
        $this->session->set_userdata('user_address',$data);
        redirect('order/order_info');
        }
        else
        {
        exit('信息添加失败');
        }
        }
        else
        {

        //查询一级地址
        $region_list=$this->region->get_region();
        $this->load->view('home/order_address.html',['region_list'=>$region_list]);
        }
     }
      /*查询一级地址下的地址*/
     public function order_address()
     {
         $region_id=$this->input->get('region_id');
         $order_address=$this->region->get_region($region_id);
         exit(json_encode($order_address));
     }

     /*
      * 订单提交(确认订单)
      */
      public function action()
      {
          $data=$this->input->post();
          //配送信息
          //生成订单号
          $order_sn=$this->create_order();
          $user_address=$this->session->userdata('user_address');
          $order_money=$this->session->userdata('order_money');
          $order_data['user_id']=$this->session->userdata('user_id');//用户id
          $order_data['order_sn']=$order_sn;//订单号
          $order_data['add_time']=time();//订单生成时间
         /* $order_data['goods_amount']=$order_money['goods_amount'];//商品总金额
          $order_data['order_amount']=$order_money['order_amount'];//应付款金额*/
          $order_data=array_merge($data,$order_data,$user_address,$order_money);
          //订单入库
          $order_id=$this->order_info->add($order_data);
            if($order_id>0)
            {
            //订单商品入库(从session中获取购物车中的商品信息)
            $cart_info=$this->session->userdata('cart_info');
            foreach ($cart_info['cart_list'] as $key=>$value)
            {
            $temp_arr=[];
            $value['order_id']=$order_id;
            $str='';
            foreach ($value['goods_attr'] as $k=>$v)
            {
            $str.=$v['attr_name'].':'.$v['attr_value'].'<br>';
            }
            $value['goods_attr']=$str;
            $temp_arr=$value;
            //订单商品入库
             if($this->order_goods->add($temp_arr)==false)
             {
                 exit('订单商品添加失败');
             }
            }
            //下单成功
             $data['order_data']=$order_data;
            //清除购物车中我已买的商品
              $this->cart->clear_cart();
              //库存减一
             $data['status']=1;
             $this->load->view('home/order_action.html',$data);
            }
            else
            {
                $data['status']=0;
                $data['msg']='下单失败';
                $this->load->view('home/order_action.html',$data);
            }
      }

      /**
       *  生成唯一订单号
       *  return string
       */
      protected  function  create_order()
      {
          $order_sn=date('YmdHis').rand(1000,9999);
          return $order_sn;
      }
}