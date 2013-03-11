<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract View_sales_information_model 查看所有销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class View_sales_information_model extends CI_Model{
		/*
		 * @abstract sel_all_wait_storage 查找所有销售单
		 * @param $page_data 分页数据
		 * @return array 销售单结果
		 * @access public
		 * */
		public function sel_all_sales($page_data){
			$sel_all_sales_storage_str = "select * from `ms_sales_order` order by `id` desc limit ".(($page_data['page']-1)*$page_data['page_row']).",".$page_data['page_row'];
			$sel_all_sales_storage_res = $this->db->query($sel_all_sales_storage_str);
			if($sel_all_sales_storage_res->num_rows() > 0){
				return $sel_all_sales_storage_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract sel_all_sales_num 查找销售单总条数
		 * @return int 总条数
		 * @access public
		 * */
		public function sel_all_sales_num(){
			$sel_sales_str = "select * from `ms_sales_order`";
			return $this->db->query($sel_sales_str)->num_rows();
		}
		/*
		 * @abstract sel_warehouse 查找所有仓库
		 * @return array 仓库ID号为键，仓库名称为值的数组
		 * @access public
		 * */
		public function sel_warehouse(){
			$sel_warehouse_str = "select * from `ms_warehouse`";
			$sel_warehouse_res = $this->db->query($sel_warehouse_str);
			if($sel_warehouse_res->num_rows() > 0){
				$warehouse = array();
				foreach($sel_warehouse_res->result_array() as $warehouse_res){
					$warehouse[$warehouse_res['id']] = $warehouse_res['warehouse_name'];
				}
				return $warehouse;
			}
			return false;
		}
		
		/*
		 * @abstract del_sale_order 删除指定销售单
		 * @param $sale_order_id 指定的销售单ID号
		 * @access public
		 * */
		public function del_sale_order($sale_order_id){
			$del_sale_order_str = "delete from `ms_sales_order` where `id` = {$sale_order_id}";
			if($this->db->query($del_sale_order_str)){
				$del_sale_commodity_str = "delete from `ms_detail_sales_order` where `sales_order_id` = {$sale_order_id}";
				if($this->db->query($del_sale_commodity_str)){
					return true;
				}
				return false;
			}
			return false;
		}
	}
?>