<?php
class  MY_Controller extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}
/*********************** 后台公共控制器**********************************************/
 class  Admin_Controller extends  MY_Controller
 {
     public function __construct()
     {
         parent::__construct();
         $is_logined=$this->session->userdata('is_logined');
         if(!$is_logined)
         {
             redirect('admin/login/login');
         }
     }
     /**
      * 文件上传
      * string  $img_name
      * @return string $file_name  文件名
      */
     public function do_upload($img_name)
     {
         $config['upload_path']      = './uploads/';
         $config['allowed_types']    = 'gif|jpg|png';
         $config['max_size']         =  5*1024;
         $this->load->library('upload', $config);
         if (!$this->upload->do_upload($img_name))
         {
             $error = $this->upload->display_errors();
             exit($error);
         }
         else
         {
             $file_name= $this->upload->data('file_name');
             return $file_name;

         }
     }
     /**
      * 分页
      */
     public function  get_pages($count,$url)
     {
         $this->config->load('set_config');//载入自定义配置文件
         $page_size=$this->config->item('per_page');//获取自定义配置
         $config['base_url'] = $url;
         $config['total_rows'] =$count;
         $config['per_page'] = $page_size;
         $config['full_tag_open'] = '<div id="turn-page"><span id="page-link">'; //（添加封装标签）
         $config['full_tag_close'] = '</div></span>';
         //自定义第一个链接
         $config['first_link'] = '首页';
         //自定义最后一个链接
         $config['last_link'] = '末页';
         //自定义上一页链接
         $config['prev_link'] = '上一页';
         //自定义下一页链接
         $config['next_link'] = '下一页';
         $this->pagination->initialize($config);
         $pages=$this->pagination->create_links();
         return $pages;
     }
     /**
      *  成功消息提示
      */
     public function success($msg='',$url='',$wait=3)
     {
         $data['message']=$msg;
         $data['url']=$url;
         $data['wait']=$wait;
         $data['status']=1;
         $this->load->view('admin/message.html',$data);
     }
     /**
      *  失败消息提示
      */
     public function error($msg='',$url='',$wait='')
     {
         $data['message']=$msg;
         $data['url']=$url;
         $data['wait']=$wait;
         $data['status']=0;
         $this->load->view('admin/message.html',$data);
     }

 }

/*********************** 前公共控制器**********************************************/
class  Home_Controller extends  MY_Controller
{
   public function __construct()
   {
       parent::__construct();
       //判断用户是否登录
       $user_id=$this->session->userdata('user_id');
        if(IS_AJAX)
        {
        if(!isset($user_id))
        {
        $this->ajax_return(0,'请先登录');
        }

        }
        else
        {
            if(!isset($user_id))
            {

                echo '<h1><font color="red">请先登录...</font></h1>';
                $url=site_url('login/login');
                header("refresh:3 url=".$url);
            }
        }
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


