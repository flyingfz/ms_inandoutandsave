<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract Open_view_sales_information_look_sales 查看指定销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class Open_view_sales_information_look_sales extends MY_Controller{
		/*
		 * @abstract index 查看指定销售单主页面
		 * @param $sales_id 销售单ID号
		 * @access public
		 * */
		public function index($sales_id){
			$this->load->model("main/open_view_sales_information_look_sales_model");
			$sales_data = array(
				"sales_basic" => $this->open_view_sales_information_look_sales_model->sel_sales_basic($sales_id),  //销售单基本信息
				"sales_detail_res" => $this->open_view_sales_information_look_sales_model->sel_sales_detail($sales_id)  //销售单详细信息
			);
			$this->load->view("main/open_view_sales_information_look_sales",$sales_data);
		}
	}
?>