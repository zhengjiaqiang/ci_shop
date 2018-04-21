<?php
 class Brand extends  Admin_Controller
 {
        public  function __construct()
        {
        parent::__construct();
        $this->load->model('Brand_model','brand');
        $this->load->library('pagination');//加载分页类
        }

        //商品品牌列表
        public function brand_list($limit=0)
        {
        $url=site_url('admin/brand/brand_list');//分页所在地址
        $count=$this->brand->count();//获取数据总条数
        $pages=$this->get_pages($count,$url);//获取分页
        $list=$this->brand->select($limit);
        $this->load->view('admin/brand_list.html',['list'=>$list,'pages'=>$pages,'count'=>$count]);
        }
        //品牌添加
        public function brand_add()
        {
        if(IS_POST)
        {
        // 品牌图片上传
        $data=$this->input->post();
        $data['brand_logo']=$this->do_upload('brand_logo');
        $res=$this->brand->add($data);
        if($res)
        {
        //redirect('admin/brand/brand_list');
        $this->success('商品品牌添加成功',site_url('admin/brand/brand_list'),3);
        }
        else
        {
        $this->error('商品品牌添加失败',site_url('admin/brand/brand_add'),3);
        }
        }
        else
        {
        $this->load->view('admin/brand_add.html');
        }
        }

 }