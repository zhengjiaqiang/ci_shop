<?php
/**
 * Created by PhpStorm.
 * User: DELL-
 * Date: 2018/3/19
 * Time: 11:10
 */

class Region_model extends  Base_Model
{
 protected $tableName='region';
 public function __construct()
 {
     parent::__construct();
 }
  public function get_region($pid=0)
  {
     return $this->db->where('parent_id',$pid)->get($this->tableName)->result_array();
  }
}