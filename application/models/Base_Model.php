<?php
class  Base_Model extends  CI_Model
{
    protected $tableName = '';
    public function __construct()
    {
        $this->config->load('set_config');//载入自定义配置文件
    }

    //添加
    public function add($data)
    {
        $datas=$this->filter_field($data);//字段过滤方法
        $this->db->insert($this->tableName,$datas);
        $insert_id=$this->db->insert_id();
        if($insert_id)
        {
            return $insert_id;
        }
        else
        {
            return false;
        }

    }
    //商品添加方法
    public function goods_add($data)
    {
        $datas=$this->filter_field($data);//字段过滤方法
        $this->db->insert($this->tableName,$datas);
        $insert_id=$this->db->insert_id();
        if($insert_id)
        {
            return $insert_id;
        }
        else
        {
          return false;
        }
    }
    //查询
    public function select($limit=0)
    {
        $page_size=$this->config->item('per_page');//获取自定义配置每页显示条数
        return $this->db->get($this->tableName,$page_size,$limit)->result_array();
    }
    public function sel()
    {
        return $this->db ->get($this->tableName)->result_array();
    }
    //字段过滤
    public function  filter_field($data)
    {
        //获取当前表中的所有字段
        $fields=$this->db->list_fields($this->tableName);
        foreach ($data as $k=>$v)
        {
            if(!in_array($k,$fields))
            {
                unset($data[$k]);
            }
        }
        return $data;
    }
    //获取数据总条数
    public function count()
    {
        return $this->db->count_all_results($this->tableName);
    }
}