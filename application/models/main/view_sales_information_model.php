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
	}
?>