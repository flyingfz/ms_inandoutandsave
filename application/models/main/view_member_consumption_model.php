<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract View_member_consumption_model 查看所有会员消费记录
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class View_member_consumption_model extends MY_Controller{
		/*
		 * @abstract sel_all_consumption_num 查找所有消费记录总条数
		 * @return int 总条数
		 * @access public
		 * */
		public function sel_all_consumption_num(){
			$sel_consumption_str = "select * from `ms_sales_order_serial`";
			return $this->db->query($sel_consumption_str)->num_rows();
		}
		
		/*
		 * @abstract sel_all_consumption 查找所有消费记录
		 * @param $page_data 分页数据
		 * @return array 消费记录数组
		 * @access public
		 * */
		public function sel_all_consumption($page_data){
			$sel_consumption_str = "select * from `ms_sales_order_serial` order by `id` desc limit ".(($page_data['page']-1)*$page_data['page_row']).",".$page_data['page_row'];
			$sel_consumption_res = $this->db->query($sel_consumption_str);
			if($sel_consumption_res->num_rows() > 0){
				return $sel_consumption_res->result_array();
			}
			return false;
		}
	}
?>