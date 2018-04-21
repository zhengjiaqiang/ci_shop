<?php
class  Attribute extends  Admin_Controller
{
    public function __construct()
    {
       parent::__construct();
       $this->load->model('Attribute_model','attr');
    }
    //属性列表
    public function show($goods_type_id='')
    {
      $attrList=$this->attr->sel($goods_type_id);
//      print_r($attrList);
      $this->load->vars('goods_type_id',$goods_type_id);
      $this->load->view('admin/attribute_list.html',['attrList'=>$attrList]);
    }
    //属性添加
    public function  add($goods_type_id='')
    {
        if(IS_POST)
        {
        $data=$this->input->post();
        $res=$this->attr->add($data);
        if($res)
        {
        $this->success('属性添加成功',site_url('admin/attribute/show'));
        }
        else
        {
        $this->error('属性添加失败',site_url('admin/attribute/add'));
        }
        }
        else
        {
          $type_list=$this->attr->get_types();
          $this->load->vars('goods_type_id',$goods_type_id);
          $this->load->view('admin/attribute_add.html',['type_list'=>$type_list]);
        }
    }
}