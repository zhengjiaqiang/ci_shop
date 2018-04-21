<?php
/**
 * Created by PhpStorm.
 * User: DELL-
 * Date: 2018/3/8
 * Time: 16:46
 */
class Test extends  CI_Controller
{
    //表单展示
    public function add()
    {
          if(IS_POST)
          {
              $data=$this->input->post();
              $this->db->insert('student',$data);
              if($this->db->insert_id())
              {
                  redirect('test/show');
              }
          }
          $this->load->view('test/add');
    }

     public function  show()
     {
         $list=$this->db->get('student')->result_array();
         $this->load->view('test/show',['list'=>$list]);
     }

     public function del($id='')
     {
         $bool=$this->db->delete('student',['id'=>$id]);
         var_dump($bool);

     }
     //搜索
    public function search()
    {
        $keywords=$this->input->get('keywords');
        if(!empty($keywords))
        {
         $row=$this->db->like('uname',$keywords) ->get('student')->result_array();
         //echo $this->db->last_query();
          $this->load->view('test/search',['row'=>$row]);
        }
    }

}