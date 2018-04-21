<?php
/**
 * Created by PhpStorm.
 * User: DELL-
 * Date: 2018/3/17
 * Time: 9:48
 */
class  Goods extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model', 'cat');
        $this->load->model('Goods_model', 'goods');
        $this->load->model('Product_model', 'product');
    }

    /**
     * 查询商品详细信息
     * @param int $goods_id 商品id
     */
    public  function  show($goods_id=0)
    {
        $goods_id=intval($goods_id);
        //查询商品详细信息
        $goods_info=$this->goods->find($goods_id);
        //查询商品属性(参数、规格)
        $property_list=$this->goods->property($goods_id);
        $data['attr_list']=$property_list['attr_list'];
        $data['spec_list']=$property_list['spec_list'];
        //p($property_list);
        $data['goods_info']=$goods_info;
        $this->load->view('home/product.html',$data);
    }
      /*查询可以组合的规格*/
    public function get_group_spec()
    {
        $goods_id=$this->input->get('goods_id');
        $spec_list=$this->product->get_spec($goods_id);
//        p($spec_list);
        exit(json_encode($spec_list));

    }


}