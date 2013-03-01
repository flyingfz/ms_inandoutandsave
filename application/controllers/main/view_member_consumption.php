<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract View_member_consumption 查看所有会员消费记录
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class View_member_consumption extends MY_Controller{
		/*
		 * @abstract index 查看所有会员消费记录
		 * @param $page 当前页数
		 * @access public
		 * */
		public function index($page = 1){
			$this->load->model("main/view_member_consumption_model");  //载入模型
			//分页数据
			$page_row = 21;  //每页显示条数
			$num_row = $this->view_member_consumption_model->sel_all_consumption_num();  //总条数
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
			$consumption_data = array(
					"page_data" => $page_data,  //分页数组
					"consumption_res" => $this->view_member_consumption_model->sel_all_consumption($page_data)  //所有消费记录数据
			);
			$this->load->view("main/view_member_consumption",$consumption_data);
		}
	}
?>