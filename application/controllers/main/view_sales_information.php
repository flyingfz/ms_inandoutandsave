<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract View_sales_information 新增销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class View_sales_information extends MY_Controller{
		/*
		 * @abstract index 未结算入库单列表主页面
		 * @access public
		 * */
		public function index($page=1){
			$this->load->model("main/view_sales_information_model");  //载入模型
			//分页数据
			$page_row = 21;  //每页显示条数
			$num_row = $this->view_sales_information_model->sel_all_sales_num();  //总条数
			$page_num = ceil($num_row/$page_row);  //总页数
			if($page > $page_num && $page_num != 0){
				$page = $page_num;
			}else if($page < 1){
				$page = 1;
			}
			$page_data = array(
					"page" => $page,  //当前页
					"page_row" => $page_row,  //每页显示条数
					"num_row" => $num_row,  //总条数
					"page_num" => $page_num  //总页数
			);
			$sales_information_data = array(
					"page_data" => $page_data,  //分页数组
					"warehouse_res" => $this->view_sales_information_model->sel_warehouse(),  //查找出货仓库
					"sales_information_res" => $this->view_sales_information_model->sel_all_sales($page_data)  //所有会员数据
			);
			$this->load->view("main/view_sales_information",$sales_information_data);
		}
		
		/*
		 * @abstract del_sale_order 删除指定销售单
		 * @param $sale_order_id 指定的销售单ID号
		 * @access public
		 * */
		public function del_sale_order($sale_order_id){
			$this->load->model("main/view_sales_information_model");
			if($this->view_sales_information_model->del_sale_order($sale_order_id)){
				$success_data = array(
					"content" => "删除成功！",
					"time" => 3,
					"url" => site_url("main/view_sales_information")
				);
				$this->load->view("prompt/success",$success_data);
			}else{
				$error_data = array(
					"content" => "删除失败！",
					"time" => 3,
					"url" => site_url("main/view_sales_information")
				);
				$this->load->view("prompt/error",$error_data);
			}
		}
	}
?>