<?php
class  Category extends  Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model','cat');
    }

    //商品分类
    public function cat_list()
    {
        $cat_list=$this->cat->select();
        $this->load->view('admin/cat_list.html',['cat_list'=>$cat_list]);
    }
    //添加分类
    public function cat_add()
    {
        if(IS_POST)
        {
        $data=$this->input->post();
        $data['path']=($data['parent_id']==0)?0:$data['path'].'-'.$data['parent_id'];
        $bool=$this->cat->add($data);
        if($bool)
        {
         $this->success('商品分类添加成功',site_url('admin/category/cat_list'));
        }
        else
        {
         $this->success('商品分类添加失败',site_url('admin/category/cat_add'));
        }
        }
        else
        {
            $cat_list=$this->cat->select();
            $this->load->view('admin/cat_add.html',['cat_list'=>$cat_list]);
        }
    }
}