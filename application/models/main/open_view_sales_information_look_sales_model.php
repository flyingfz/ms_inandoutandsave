<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract Open_view_sales_information_look_sales_model 查看指定销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class Open_view_sales_information_look_sales_model extends CI_Model{
		/*
		 * @abstract sel_sales_basic 查找指定的销售单基本信息
		 * @param $sales_id 销售单ID号
		 * @return array 基本信息数组
		 * @access public
		 * */
		public function sel_sales_basic($sales_id){
			$sel_basic_str = "select * from `ms_sales_order` where `id` = {$sales_id} limit 1";
			$sel_basic_res = $this->db->query($sel_basic_str);
			if($sel_basic_res->num_rows() > 0){
				return $sel_basic_res->row();
			}
			return false;
		}
		
		/*
		 * @abstract sel_sales_detail 查找指定销售单详细信息
		 * @param $sales_id 销售单ID号
		 * @return array 详细信息数组
		 * @access public
		 * */
		public function sel_sales_detail($sales_id){
			$sel_detail_str = "select `ms_detail_sales_order`.*,`ms_commodity_information`.`commodity_number`,`ms_commodity_information`.`commodity_serial_number`,`ms_commodity_information`.`commodity_name`,`ms_commodity_information`.`commodity_color`,`ms_commodity_information`.`commodity_size`,`ms_commodity_information`.`dan_wei`,`ms_commodity_information`.`brand` from `ms_detail_sales_order`,`ms_commodity_information` where `ms_detail_sales_order`.`commodity_id`=`ms_commodity_information`.`id` and `ms_detail_sales_order`.`sales_order_id` = {$sales_id}";
			$sel_detail_res = $this->db->query($sel_detail_str);
			if($sel_detail_res->num_rows() > 0){
				return $sel_detail_res->result_array();
			}
			return false;
		}
	}
?>