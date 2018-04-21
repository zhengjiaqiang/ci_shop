<?php
/**
 * Created by PhpStorm.
 * User: DELL-
 * Date: 2018/3/19
 * Time: 12:08
 */

class User_address_model extends Base_Model
{
 protected $tableName='user_address';

  //默认地址
  public function default_address()
  {
      $user_id=$this->session->userdata('user_id');
      return $this->db->where(['user_id'=>$user_id,'is_default'=>1])->get($this->tableName)->row_array();
  }
}