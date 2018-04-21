<?php
class  Brand_model extends  Base_Model
{
    protected $tableName='brand';
    public function sel_brand()
    {
       return $this->db->select('brand_id,brand_name')->get($this->tableName)->result_array();
    }

}