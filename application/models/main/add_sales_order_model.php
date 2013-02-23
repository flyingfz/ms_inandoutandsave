<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract Add_sales_order_model 新增销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class Add_sales_order_model extends CI_Model{
		/*
		 * @abstract sel_warehouse 查找所有仓库
		 * @return array 仓库数组
		 * @access public
		 * */
		public function sel_warehouse(){
			$sel_warehouse_str = "select * from `ms_warehouse`";
			$sel_warehouse_res = $this->db->query($sel_warehouse_str);
			if($sel_warehouse_res->num_rows() > 0){
				return $sel_warehouse_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract sel_vague_serial 模糊搜索卡号和人名
		 * @param $serial_number
		 * @return array
		 * @access public
		 * */
		public function sel_vague_serial($serial_number){
			$sel_vague_serial_str = "select * from `ms_membership_information` where `serial_number` like '%{$serial_number}%' or `name` like '%{$serial_number}%' limit 15";
			$sel_vague_serial_res = $this->db->query($sel_vague_serial_str);
			if($sel_vague_serial_res->num_rows() > 0){
				return $sel_vague_serial_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract sel_vague_gift 模糊搜索礼品
		 * @param $gift_name
		 * @return array
		 * @access public
		 * */
		public function sel_vague_gift($gift_name){
			$sel_vague_gift_str = "select * from `ms_gift_info` where `name` like '%{$gift_name}%' limit 15";
			$sel_vague_gift_res = $this->db->query($sel_vague_gift_str);
			if($sel_vague_gift_res->num_rows() > 0){
				return $sel_vague_gift_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract sel_number_commodity 按商品编号直接查询
		 * @param $commodity_number 商品编号
		 * @return array 商品数组
		 * @access public
		 * */
		public function sel_number_commodity($commodity_number){
			$sel_commodity_str = "select * from `ms_commodity_information` where `commodity_number` = '{$commodity_number}'";
			$sel_commodity_res = $this->db->query($sel_commodity_str);
			if($sel_commodity_res->num_rows() > 0){
				if($sel_commodity_res->num_rows() == 1){
					return $sel_commodity_res->row();
				}else{
					return "1";
				}
			}else{
				return "0";
			}
		}
		/*
		 * @abstract sel_commodity_fuzzy_number 按商品编号模糊查询
		 * @param $commodity_number 商品编号
		 * @return array 商品数组
		 * @access public
		 * */
		public function sel_commodity_fuzzy_number($commodity_number){
			$sel_commodity_str = "select * from `ms_commodity_information` where `commodity_number` like '%{$commodity_number}%' order by `id` desc";
			$sel_commodity_res = $this->db->query($sel_commodity_str);
			if($sel_commodity_res->num_rows() > 0){
				return $sel_commodity_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract sel_all_commodity 查找所有商品及库存
		 * @return array 商品及库存数组
		 * @access public
		 * */
		public function sel_all_commodity(){
			$sel_commodity_str = "select * from `ms_commodity_information` order by `id` desc";
			$sel_commodity_res = $this->db->query($sel_commodity_str);
			if($sel_commodity_res->num_rows() > 0){
				return $sel_commodity_res->result_array();
			}
			return false;
		}
		/*
		 * @abstract add_sales_order 添加销售单基本信息
		 * @param $order_data 基本数据
		 * @return int 销售单ID号
		 * @access public
		 * */
		public function add_sales_order($order_data){
			if($this->db->insert("ms_sales_order",$order_data)){
				return mysql_insert_id();
			}
			return false;
		}
		/*
		 * @abstract add_sales_order_detailed 添加销售单详细信息
		 * @param $order_detailed_data 详细数据
		 * @return bool
		 * @access public
		 * */
		public function add_sales_order_detailed($order_detailed_data,$warehouse_id){
// 			echo "<pre>";
// 			print_r($order_detailed_data);
// 			echo "</pre>";
// 			die();
			//添加详细
			$add_sales_order_str = "insert into `ms_detail_sales_order` (`id`,`sales_order_id`,`commodity_id`,`commodity_num`,`unit_price`) values ";
			for($i=0;$i<count($order_detailed_data['commodity_id']);$i++){
				if($i == 0){
					$add_sales_order_str .= "(NULL,'{$order_detailed_data['sales_order_id']}','{$order_detailed_data['commodity_id'][$i]}','{$order_detailed_data['commodity_num'][$i]}','{$order_detailed_data['unit_price'][$i]}')";
				}else{
					$add_sales_order_str .= ",(NULL,'{$order_detailed_data['sales_order_id']}','{$order_detailed_data['commodity_id'][$i]}','{$order_detailed_data['commodity_num'][$i]}','{$order_detailed_data['unit_price'][$i]}')";
				}
			}
			if($this->db->query($add_sales_order_str)){
				//更新商品库存数量
				for($i=0;$i<count($order_detailed_data['commodity_id']);$i++){
					$update_stock_str = "update `ms_stock_information` set `inventory_number`=`inventory_number`-".$order_detailed_data['commodity_num'][$i]." where `commodity_id`=".$order_detailed_data['commodity_id'][$i]." and `warehouse_id`=".$warehouse_id;
					$this->db->query($update_stock_str);
				}
				return true;
			}
			return false;
		}
	}
?>