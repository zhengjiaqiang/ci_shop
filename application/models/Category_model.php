<?php
class  Category_model extends  Base_Model
{
    protected $tableName='category';

    /**
     * 商品分类查询
     * return array
     */
    public function select($limit=0)
    {
            return $this->db
            ->select("cat_id,cat_name,parent_id,path,sort,is_nav,is_show,CONCAT(path,'-',cat_id) AS depath")
            ->order_by("depath ASC")->get($this->tableName)->result_array();
    }
    /******************** 前台 ************************************************************/

    /**
     * 查找当前位置
     * @param array $cat_info
     * @param array $parent_cat_list
     * return string  $str
     */
     public function nows($cat_info,$parent_cat_list)
     {
         $str='';
         $str.='<a href="'.site_url('index/index').'">首页</a>'.'&gt';
         if($parent_cat_list)
         {
            krsort($parent_cat_list);
             foreach ($parent_cat_list as $k=>$v)
             {
                 $str.='<a href="'.site_url('category/show').'/'.$v['cat_id'].'">'.$v['cat_name'].'</a>'.'&gt';
             }
         }
         $str.=$cat_info['cat_name'];
         return $str;
     }
    public function sel_cat()
    {
        return $this->db ->get($this->tableName)->result_array();
    }

    /**
     * 获取分类树
     * @param  array $cat_list 二维数组
     * @param  int  $parent_id 父级id
     */
    public function get_cat_tree($cat_list,$parent_id=0)
    {
        $result=[];
        foreach ($cat_list as $k=>$v)
        {
          if($v['parent_id']==$parent_id)
          {
              $result[$k]=$v;
              $result[$k]['son']=$this->get_cat_tree($cat_list,$v['cat_id']);
          }
        }
       return $result;
    }

    /**
     * 查询当前分类下的子id
     * @param int $cat_id 分类id
     * @param array $cat_list 分类列表
     */
    public function get_sons($cat_id,$cat_list)
    {
        static  $result=[];
        if($cat_list)
        {
            foreach ($cat_list as $k=>$v)
            {
                if($v['parent_id']==$cat_id)
                {
                    $result[]=$v;
                    $this->get_sons($v['cat_id'],$cat_list);
                }
            }
        }
        return $result;
    }

    /**
     * 查询分类信息
     * @param int $cat_id
     */
    public function get_cat_info($cat_id)
    {
        $_where=['cat_id'=>$cat_id];
        return$this->db->where($_where)->get($this->tableName)->row_array();
    }

    /**
     * 查找子分类的父亲
     * @param array $cat_info 分类信息
     * @param array $cat_list 分类结果集（二维数组）
     * return array  $result;
     */
    public function get_cat_parents($cat_info,$cat_list)
    {
        static  $result=[];
        if($cat_list)
        {
            foreach ($cat_list as $k=>$v)
            {
                if($cat_info['parent_id']==$v['cat_id'])
                {
                    $result[]=$v;
                    $this->get_cat_parents($v,$cat_list);
                }
            }
        }
        return $result;
    }

}


















?>













