<?php
class Admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model','admin');
    }

    /**
     * 管理员列表
     */
    public function show()
    {
        $admin_list=$this->admin->sel();
        $this->load->view('admin/admin_list.html',['admin_list'=>$admin_list]);
    }
    /**
     * 添加管理员
     */
    public function add()
    {
        if(IS_POST)
        {
        $data['admin_name']=$this->input->post('admin_name');
        $data['password']=md5($this->input->post('password'));
        $data['role_id']= $this->input->post('role_id');
        $admin_id=$this->admin->add($data);
        if($admin_id)
        {
        $this->success('管理员添加成功',site_url('admin/admin/show'),3);
        }
        else
        {
        $this->error('管理员添加失败',site_url('admin/admin/add'),3);
        }

        }
        else
        {
        //查询角色
        $role_list=$this->db->select('role_id,role_name')->get('role')->result_array();
        $this->load->vars('role_list',$role_list);
        $this->load->view('admin/admin_add.html');
        }
    }
}
