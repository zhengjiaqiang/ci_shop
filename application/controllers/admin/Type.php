<?php
class  Type extends  Admin_Controller
{
    public function __construct()
    {
    parent::__construct();
    $this->load->model('Type_model','type');
    $this->load->library('pagination');
    }
    //商品类型列表
    public function type_list($limit=0)
    {
     $url=site_url('admin/type/type_list');//分页所在地址
    $count=$this->type->count();//获取数据总条数
    $pages=$this->get_pages($count,$url);//获取分页
    $typeList=$this->type->select($limit);
    $this->load->view('admin/goods_type_list.html',['typeList'=>$typeList,'pages'=>$pages]);
    }
    //商品类型添加
    public function type_add()
    {
    if(IS_POST)
    {
    $data=$this->input->post();
    $res=$this->type->add($data);
    if($res)
    {
    $this->success('商品类型添加成功',site_url('admin/type/type_list'));
    }
    else
    {
    $this->error('商品类型添加失败',site_url('admin/type/type_add'));
    }
    }
    else
    {
    $this->load->view('admin/goods_type_add.html');
    }
    }
}