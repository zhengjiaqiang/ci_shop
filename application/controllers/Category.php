<?php
class  Category extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model','cat');
        $this->load->model('Goods_model','goods');
    }

    /**
     * 商品分类列表
     * @param int $cat_id 商品分类id
     */
    public function show($cat_id=1,$brand_id=0)
    {
        //查询全部商品分类
        $cat_list=$this->cat->sel_cat();
        $cat_tree_list=$this->cat->get_cat_tree($cat_list);
        //根据分类id查询其所有子分类id
        $cat_id=intval($cat_id);
        $cat_sons=$this->cat->get_sons($cat_id,$cat_list);
        $cat_sons_ids=array_column($cat_sons,'cat_id');
        $cat_sons_ids[]=$cat_id;
        //p($cat_sons_ids);
        //筛选品牌
        $filter_brand_list=$this->goods->get_filter_brand($cat_sons_ids);
//        p($filter_brand_list);
        //查询分类信息
        $cat_info=$this->cat->get_cat_info($cat_id);
        //查询当前位置(父亲)
        $parent_cat_list=$this->cat->get_cat_parents($cat_info,$cat_list);
        $now_url=$this->cat->nows($cat_info,$parent_cat_list);//当前位置
//   p($now_url);
        $goods_list=$this->goods->first_floor($cat_sons_ids);//商品列表
        $this->load->vars('cat_tree_list',$cat_tree_list);
        $this->load->vars('goods_list',$goods_list);
        $this->load->vars('filter_brand_list',$filter_brand_list);
        $this->load->vars('parent_cat_list',$parent_cat_list);
        $this->load->vars('cat_info',$cat_info);
        $this->load->vars('now_url',$now_url);
        $this->load->view('home/category_list.html');
    }
}