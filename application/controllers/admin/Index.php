<?php
class Index extends Admin_Controller
{
    /**
     * 后台首页
     */
    public function show()
    {
        $this->load->view('admin/index.html');
    }

    public  function top()
    {
        $this->load->view('admin/top.html');
    }

    public  function menu()
    {
        //根据用户id查询角色id
        $admin_id=$this->session->userdata('info')['admin_id'];
        $sql="select * from ci_admin where admin_id=".$admin_id;
        $info=$this->db->query($sql)->result_array();
        $role_id=$info[0]['role_id'];
        //根据角色id查询所拥有的权限ids
        $sql="select * from ci_role where role_id=".$role_id;
        $rinfo=$this->db->query($sql)->result_array();
        $auth_ids=$rinfo[0]['role_auth_ids'];
        //根据$auth_ids查询全部拥有的权限信息
        //1.获得顶级权限
        $sql="select * from ci_auth where auth_level=0 ";
        //如果是admin管理员要实现全部权限
        if($admin_id!=1){
            $sql.="and auth_id in($auth_ids)";
        }
        $p_info=$this->db->query($sql)->result_array();
        //echo $this->db->last_query();die;
        //1.获得次级权限
        $sql="select * from ci_auth where auth_level=1 ";
        //如果是admin管理员要实现全部权限
        if($admin_id!=1){
            $sql.="and auth_id in($auth_ids)";
        }
        $s_info=$this->db->query($sql)->result_array();
        /*print_r($p_info);
        echo "<hr/>";
        print_r($s_info);*/
        $this->load->vars('p_info',$p_info);
        $this->load->vars('s_info',$s_info);
        $this->load->view('admin/menu.html');
    }

    public  function main()
    {
        $this->load->view('admin/main.html');
    }
}