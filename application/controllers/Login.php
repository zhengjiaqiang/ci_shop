<?php
class  Login extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function login()
    {
        if(IS_POST)
        {
            $user_name=$this->input->post('user_name');//用户名
            $password=$this->input->post('password');    //密码
            //检测用户信息
            $_where=['user_name'=>$user_name,'password'=>md5($password)];
            $info=$this->db->where($_where)->get('user')->row_array();
            if($info)
            {
              $this->session->set_userdata('user_id',$info['user_id']);
              $this->session->set_userdata('user_name',$info['user_name']);
              $this->session->set_userdata('info',$info);
              redirect('index/index');
            }
            else
            {
                redirect('login/login');
            }
        }
        else
        {
            $this->load->view('home/login.html');
        }
    }

}