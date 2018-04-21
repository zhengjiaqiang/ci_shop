<?php
class Login extends  CI_Controller
{
    public function __construct()
    {
    parent::__construct();
    }

    /**
    * 登录
    */
    public function login()
    {
    if(IS_POST)
    {
    $admin_name=$this->input->post('admin_name');//用户名
    $password=$this->input->post('password');    //密码
    $captcha=$this->input->post('captcha');      //验证码
    //检测验证码是否输入正确
    $word= $this->session->userdata('word');
    if(strtolower($captcha)!=$word)
    {
        echo "验证码输入有误";
        $url=site_url('admin/login/login');
        header("refresh:3; url=".$url);
        exit();
    }
    //检测用户信息
    $_where=['admin_name'=>$admin_name,'password'=>md5($password)];
    $info=$this->db->where($_where)->get('admin')->row_array();
    if($info)
    {
    $this->session->set_userdata('is_logined',$admin_name);
    $this->session->set_userdata('info',$info);
    $this->success('登录成功',site_url('admin/index/show'));
    }
    else
    {
    $this->error('登录失败,用户名或密码输入有误',site_url('admin/login/login'),3);
    }
    }
    else
    {
     $cap=$this->captcha();
     $this->load->vars('cap_img',$cap['image']);
     $this->load->view('admin/login.html');
    }
    }
    //退出
    public function logout()
    {
    session_destroy();
    redirect('admin/login/login');
    }
    /**
     * 验证码
     */
     public function captcha()
     {
         $this->load->helper('captcha');
        $vals = array(
        'img_path'  => './captcha/',
        'img_url'   => base_url().'captcha/',
        'font_path' => './path/to/fonts/texb.ttf',
        'img_width' => '150',
        'img_height'    => 30,
        'expiration'    => 3600,
        'word_length'   => 4,
        'font_size' => 16,
        'img_id'    => 'Imageid',
        'pool'      => '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

        // White background and border, black text and red grid
        'colors'    => array(
        'background' => array(255, 255, 255),
        'border' => array(255, 255, 255),
        'text' => array(0, 0, 0),
        'grid' => array(255, 40, 40)
        )
        );

        $cap = create_captcha($vals);
        $this->session->set_userdata('word',strtolower($cap['word']));
        $cap['img_url']=$vals['img_url'].$cap['filename'];
        return $cap;

     }
    /**
     * 点击生成验证码
     */
     public function create_cap()
     {
         $cap=$this->captcha();
         exit(json_encode($cap['img_url']));

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