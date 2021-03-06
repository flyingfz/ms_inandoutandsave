<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	 * @abstract Add_sales_order 新增销售单
	 * @access public
	 * @author 娱扬 hai191273@163.com
	 * @version 美思服装进销存
	 * */
	class Add_sales_order extends MY_Controller{
		/*
		 * @abstract index 新增销售单主页面
		 * @access public
		 * */
		public function index(){
			$this->load->model("main/add_sales_order_model");
			$sales_data = array(
				"warehouse_res" => $this->add_sales_order_model->sel_warehouse()  //查找所有仓库
			);
			$this->load->view("main/add_sales_order",$sales_data);
		}
		/*
		 * @abstract sel_serial 查找会员(AJAX用)
		 * @access public
		 * */
		public function sel_serial(){
			$this->load->model("main/add_sales_order_model");
			//获取数据
			$serial_number = $this->input->post("serial_number");
			//模糊搜索
			$serial_res = $this->add_sales_order_model->sel_vague_serial($serial_number);
			if($serial_res != false){
				$i = 0;
				foreach($serial_res as $serial){
					echo "<ul>";
					echo "<li name='sel_serial_li' lang='{$i}'><span name='serial_number_{$i}'>{$serial['serial_number']}</span>&nbsp;&nbsp;<span name='name'>{$serial['name']}</span><span name='serial_id_{$i}' style='display:none;'>{$serial['id']}</span></li>";
					echo "</ul>";
					$i++;
				}
			}
		}
		/*
		 * @abstract sel_gift 查找礼品(AJAX用)
		 * @access public
		 * */
		public function sel_gift(){
			$this->load->model("main/add_sales_order_model");
			//获取数据
			$gift_name = $this->input->post("gift_name");
			//模糊搜索
			$gift_res = $this->add_sales_order_model->sel_vague_gift($gift_name);
			if($gift_res != false){
				$i = 0;
				foreach($gift_res as $gift){
					echo "<ul>";
					echo "<li name='sel_gift_li' lang='{$i}'><span name='gift_name_{$i}'>{$gift['name']}</span>&nbsp;&nbsp;<span name='number'>{$gift['number']}</span><span style='display:none;' name='gift_id_{$i}'>{$gift['id']}</span></li>";
					echo "</ul>";
					$i++;
				}
			}
		}
		/*
		 * @abstract open_selection_commodity 选择商品页面
		 * @access public
		 * */
		public function open_selection_commodity($commodity_number){
			$commodity_number = urldecode($commodity_number);
			$this->load->model("main/add_sales_order_model");
			//模糊查询商品编号
			$commodity_data = array(
				"commodity_res" => $commodity_number == "-" ? $this->add_sales_order_model->sel_all_commodity($commodity_number) : $this->add_sales_order_model->sel_commodity_fuzzy_number($commodity_number)  //按商品编号模糊查询
			);
			$this->load->view("main/open_sales_commodity",$commodity_data);
		}
		/*
		 * @abstract sel_number_commodity 通过编号查找商品(ajax用)
		 * @return string
		 * @access public
		 * */
		public function sel_number_commodity(){
			$this->load->model("main/add_sales_order_model");
			//获取数据
			$commodity_number = $this->input->post("commodity_number");
			$commodity = $this->add_sales_order_model->sel_number_commodity($commodity_number);
			if($commodity == "0"){
				echo "0";
			}else if($commodity == "1"){
				echo "1";
			}else{
				$commodity_str = "<tr name='content_tr' id='{$commodity->id}'>";
				$commodity_str .= "<td align='center'></td>";
				$commodity_str .= "<td>{$commodity->commodity_number}</td>";  //商品编号
				$commodity_str .= "<td>{$commodity->commodity_name}</td>";  //商品名称
				$commodity_str .= "<td>{$commodity->brand}</td>";  //品牌
				$commodity_str .= "<td>{$commodity->commodity_color}</td>";  //颜色
				$commodity_str .= "<td>{$commodity->commodity_size}</td>";  //尺码
				$commodity_str .= "<td>{$commodity->dan_wei}</td>";  //单位
				$commodity_str .= "<td style='width:65px;padding:0px;'><input type='hidden' name='commodity_id[]' value='{$commodity->id}' /><input type='text' class='input_6' id='num_{$commodity->id}' name='num[]' /></td>";  //数量
				$commodity_str .= "<td style='width:65px;padding:0px;'><input type='text' class='input_6' id='tag_price_{$commodity->id}' value='{$commodity->tag_price}' name='tag_price[]' /></td>";  //吊牌价
				$commodity_str .= "<td name='total' id='total_{$commodity->id}' align='right'>&nbsp;</td>";  //总价
				$commodity_str .= "<td align='center'><a href='javascript:void();' name='del_commodity' id='del_{$commodity->id}'>删除</a></td>";
				$commodity_str .= "</tr>";
				echo $commodity_str;
			}
		}
		/*
		 * @abstract open_settle_accounts 结算弹出窗口
		 * @access public
		 * */
		public function open_settle_accounts($total_price){
			$settle_data = array(
				"total_price" => $total_price
			);
			$this->load->view("main/open_settle_accounts",$settle_data);
		}
		/*
		 * @abstract add_order 添加销售单
		 * @access public
		 * */
		public function add_order(){
// 			echo "<pre>";
// 			print_r($_POST);
// 			echo "</pre>";
// 			die();
			$this->load->library("form_validation");
			$this->load->model("main/add_sales_order_model");
			//防空验证
			$this->form_validation->set_rules("sales_order_number","销售单号","trim|required");
			$this->form_validation->set_rules("sales_date","日期","trim|required");
			$this->form_validation->set_rules("warehouse_id","出货仓库","trim|required");
			$this->form_validation->set_rules("commodity_num","合计数量","trim|required");
			$this->form_validation->set_rules("total_price","整单金额","trim|required");
			if($this->form_validation->run() != false){
				if($this->input->post("mode") == "storage_submit" || $this->input->post("mode") == "settle_accounts"){
					//销售单结算状态
					if($this->input->post("mode") == "storage_submit"){
						$state = 0;
					}else{
						$state = 1;
					}
					//获取销售单基本数据
					$order_data = array(
						"sales_order_number" => $this->input->post("sales_order_number"),  //销售单编号
						"sales_order_date" => $this->input->post("sales_date"),  //日期
						"input_staff_id" => "1", //录入员ID号
						"serial_number" => $this->input->post("serial_number"),  //会员卡号
						"warehouse_id" => $this->input->post("warehouse_id"),  //出货仓库
						"commodity_num" => $this->input->post("commodity_num"),  //合计数量
						"total_price" => $this->input->post("total_price"),  //合计整单金额
						"state" => $state  //结算状态
					);
					//礼品ID
					$gift_id = $this->input->post("gift_id") ? $this->input->post("gift_id") : "";
					//会员ID号
					$membership_id = $this->input->post("serial_id") ? $this->input->post("serial_id") : "";
					//添加基本数据
					if($sales_order_id = $this->add_sales_order_model->add_sales_order($order_data,$gift_id,$membership_id)){
						//获取详细数据
						$order_detailed_data = array(
							"sales_order_id" => $sales_order_id,  //销售单ID号
							"commodity_id" => $this->input->post("commodity_id"),  //商品ID数组
							"unit_price" => $this->input->post("tag_price"),  //商品单价
							"commodity_num" => $this->input->post("num")  //商品数量
						);
						//添加详细数据
						if($this->add_sales_order_model->add_sales_order_detailed($order_detailed_data,$order_data['warehouse_id'])){
							$success_data = array(
								"content" => "销售单提交成功！",
								"time" => 3,
								"url" => site_url("main/add_sales_order")
							);
							$this->load->view("prompt/success",$success_data);
						}
					}else{
						$error_data = array(
							"content" => "提交销售单详细信息时出错！",
							"time" => 3,
							"url" => site_url("main/add_sales_order")
						);
						$this->load->view("prompt/error",$error_data);
					}
				}else{
					$error_data = array(
						"content" => "提交方式错误！",
						"time" => 3,
						"url" => site_url("main/add_sales_order")
					);
					$this->load->view("prompt/error",$error_data);
				}
			}else{
				$error_data = array(
					"content" => validation_errors(),
					"time" => 3,
					"url" => site_url("main/add_sales_order")
				);
				$this->load->view("prompt/error",$error_data);
			}
		}
	}
?>