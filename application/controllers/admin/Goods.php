<?php
class Goods extends  Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Brand_model', 'brand');
        $this->load->model('Goods_model', 'goods');
        $this->load->model('Category_model', 'cat');
        $this->load->model('Attribute_model', 'attr');
        $this->load->model('Goods_attr_model', 'goods_attr');
        $this->load->model('Product_model', 'ProductModel');

    }
    public function goods_add()
    {
        if(IS_POST)
        {
            // 商品图片上传
            $data=$this->input->post();
            $data['goods_img']=$this->do_upload('goods_img');
            $goods_id=$this->goods->add($data);
            if($goods_id)
            {
                //处理商品属性-参数
                $attr_data=$data['attr_value_list'];
                $attr_data=array_filter($attr_data);
                $attr_data_row=[];
                foreach ($attr_data as $k2=>$v2)
                {
                 $attr_data_row['goods_id']=$goods_id;
                 $attr_data_row['attr_id']=$k2;
                 $attr_data_row['attr_value']=$v2;
                 $this->goods_attr->add($attr_data_row);
                }
                //处理商品属性-规格
                $spec_data=$data['spec_list'];
                foreach ($spec_data as $k3=>$v3)
                {
                    $spec_data_row=[];
                    $spec_data_id=[];
                    $temp=array_filter($v3['spec_id']);
                    if(empty($temp))
                    {
                        unset($spec_data[$k3]);
                        continue;
                    }
                    foreach ($v3['spec_id'] as $k4=>$v4)
                    {
                    $spec_data_row['goods_id']=$goods_id;
                    $spec_data_row['attr_id']=$k4;
                    $spec_data_row['attr_value']=$v4;
                    $spec_data_id[]=$this->goods_attr->add($spec_data_row);
                    }
                    //入库货品
                    $product_data=[];
                    $product_data['goods_id']=$goods_id;//商品id
                    $product_data['product_sn']=$v3['product_sn'];//货品货号
                    $product_data['goods_price']=$v3['goods_price'];//货品价格
                    $product_data['sku']=$v3['sku'];//货品库存
                    $product_data['attr_list']=implode(',',$spec_data_id);//商品属性(goods_attr的自增id)
                    $this->product->add($product_data);
                }

                $this->success('商品添加成功',site_url('admin/goods/goods_list'),3);
            }
            else
            {
                $this->error('商品添加失败',site_url('admin/goods/goods_add'),3);
            }
        }
        else
        {
            //查询商品分类
            $cat_list=$this->cat->select();
            //查询品牌
            $brand_list=$this->brand->sel_brand();
            //查询商品类型
            $type_list=$this->attr->get_types();
            $this->load->vars('type_list',$type_list);
            $this->load->view('admin/goods_add.html',['cat_list'=>$cat_list,'brand_list'=>$brand_list]);
        }
    }

    /**
     * 根据商品类型ID查询商品属性参数
     * @param  int $goods_type_id 商品类型id
     * return  JSON
     */
    public function get_attr()
    {
        $goods_type_id=$this->input->get('goods_type_id');//商品类型id
        $attr_type=$this->input->get('attr_type');//属性类型
        $attr_type=isset($attr_type)?$attr_type : 0;
        if($attr_type)
        {
            $spec_list=$this->attr->sel_attr($goods_type_id,$attr_type);
            //标题
            $str_th='';
            $str_th.= '<tr>';
            //内容
            $str3='';
            $str3.='<tr>';
            foreach ($spec_list as $k=>$v)
            {
              $str_th.='<td>'.$v['attr_name'].'</td>'.'&nbsp;';
              $attr_values_arr=explode("\r\n",$v['attr_values']);
              $str2='';
              foreach ($attr_values_arr as $key=>$value)
              {
                  $str2.=' <option value="'.$value.'">'.$value.'</option>';
              }
              $str3.='<td>
						<select name="spec_list[0][spec_id]['.$v["attr_id"].']">
					    <option value="">请选择...</option>'.$str2.'</select>';
            }
            $str_th.='<td>货号</td>
                      <td>价格</td>
                      <td>库存</td>
                      </tr>';
            $str3.= '<td><input name="spec_list[0][product_sn]" type="text" value="00101" size="40"> </td>
							  <td><input name="spec_list[0][goods_price]" type="text" size="40"> </td>
							  <td><input name="spec_list[0][sku]" type="text" value="1000" size="40"> </td>
						      </tr>';
             $str3.='<tr><td><a href="javascript:void(0)" class="addTrSpec">+添加一行</a></td></tr>';
             $str4=$str_th.$str3;
             exit($str4);

        }
        else
        {
            $attr_list=$this->attr->sel_attr($goods_type_id,$attr_type);
            exit(json_encode($attr_list));
        }

    }

}