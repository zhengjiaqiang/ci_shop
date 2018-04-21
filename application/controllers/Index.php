<?php
class  Index extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model','cat');
        $this->load->model('Goods_model','goods');
    }
    /**
     * 商城首页
     */
    public function index()
    {
        //查询全部商品分类
        $cat_list=$this->cat->sel_cat();
        $cat_tree_list=$this->cat->get_cat_tree($cat_list);
//        p($cat_list);
        //查询热门商品
        $goods_hot=$this->goods->get_goods_hot();
        //查询1F商品
         $son_cats=$this->cat->get_sons(1,$cat_list);
         $son_cats_ids=array_column($son_cats,'cat_id');
        $first_floor=$this->goods->first_floor($son_cats_ids);
       // p($son_cats);
        $is_show=1;//是否展示菜单栏
        $this->load->vars('is_show',$is_show);
        $this->load->vars('cat_tree_list',$cat_tree_list);
        $this->load->vars('first_floor',$first_floor);
        $this->load->vars('goods_hot',$goods_hot);
        $this->load->vars('son_cats',$son_cats);
        $this->load->view('home/index.html');
    }
}