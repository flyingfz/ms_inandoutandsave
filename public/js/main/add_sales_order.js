﻿$(document).ready(function(){
	//生成销售单号
	var rand_number = Math.floor(Math.random()*10);
	$("input[name='sales_order_number']").val("xs-"+_year+_month+_day+_hours+_minute+_sec+rand_number);
	
	//自动生成日期
	$("input[name='sales_date']").val(_year+"-"+_month+"-"+_day);
	
	//日期单击事件
	$("input[name='sales_date']").click(function(){
		new Calendar().show(this);
	});
	
	//监控F4键（结算）和F2键（提交）
	$(document).keyup(function(event){
		//F4
		if(event.keyCode == "115"){
			$("input[name='settle_accounts']").click();
		}
		//F2
		if(event.keyCode == "113"){
			$("input[name='storage_submit']").click();
		}
	});
	
	//结算
	$("input[name='settle_accounts']").click(function(){
		if($("input[name='total_price']").val() == ""){
			alert("请填写商品单价或数量！");
			return false;
		}
		//数量防空验证
		$num_bool = false;
		$("input[name='num[]']").each(function(){
			if($(this).val() == ""){
				$num_bool = true;
				return false;
			}
		});
		if($num_bool){
			alert("请将商品数量填写完整！");
			return false;
		}
		//单价防空验证
		$price_bool = false;
		$("input[name='tag_price[]']").each(function(){
			if($(this).val() == ""){
				$price_bool = true;
				return false;
			}
		});
		if($price_bool){
			alert("请将商品单价填写完整！");
			return false;
		}
		window.open($("#app_path").val()+"/main/add_sales_order/open_settle_accounts/"+$("input[name='total_price']").val()+"/"+Math.random(),"open_settle_accounts","resizable=no,scrollbars=no,status=no,toolbar=no,width=355,height=225,left="+(($(parent.window).width()/2)-175)+"px,top="+(($(parent.parent.window).height()/2)-110)+"px");
		return false;
	});
	
	//商品编号框单击事件
	$("input[name='commodity_number']").focus(function(){
		//控制文字
		if($(this).val() == "请输入商品编号(按回车添加)"){
			$(this).val("");  //清空文本框
			$(this).css("color","#000000");  //设置文字颜色
		}
		//捕捉回车
		$(this).unbind("keyup");
		$(this).keyup(function(event){
			if(event.keyCode == 13){
				var commodity_number = $("input[name='commodity_number']").val();
				$.post($("#app_path").val()+"/main/add_sales_order/sel_number_commodity/"+Math.random(),{commodity_number: commodity_number},function(data){
					if(data == "1"){
						alert("找到多条商品，检查商品编号！");
						return false;
					}else if(data == "0"){
						alert("没有找到相关商品，检查商品编号！");
						return false;
					}
					$("#tick_tr").before(data);
				});
				$("input[name='commodity_number']").blur();
			}
		});
	});
	
	//商品编号框失去焦点事件
	$("input[name='commodity_number']").blur(function(){
		if($(this).val() == ""){
			$(this).val("请输入商品编号(按回车添加)");  //设置文本框
			$(this).css("color","#999999");  //设置文字颜色
		}
	});
	
	//=======================================查找会员开始=======================================
	var serial_number = "";
	//获取焦点事件
	$("input[name='serial_number_temp']").focus(function(){
		$(this).unbind("keyup");
		$(this).keyup(function(event){
			if(event.keyCode == 40 || event.keyCode == 38){
				//上下移动待开发
			}else{
				//如果内容发生改变
				if($(this).val() != ""){
					if(serial_number != $(this).val()){
						serial_number = $(this).val();  //获取卡号内容
						//调用AJAX查找
						$.post($("#app_path").val()+"/main/add_sales_order/sel_serial/"+Math.random(),{serial_number: serial_number},function(data){
							$("#sel_serial").css("display","block");
							$("#sel_serial").html(data);
						});
					}
				}else{
					serial_number = "";
					$("#sel_serial").css("display","none");
				}
			}
		});
		$(this).click(function(){
			serial_number = $("input[name='serial_number_temp']").val();
			//调用AJAX查找
			$.post($("#app_path").val()+"/main/add_sales_order/sel_serial/"+Math.random(),{serial_number: serial_number},function(data){
				$("#sel_serial").css("display","block");
				$("#sel_serial").html(data);
			});
		});
	});
	//鼠标经过事件
	$("li[name='sel_serial_li']").live("mouseenter",function(){
		$(this).css("background","#F5F5F5");
	});
	//离开焦点事件
	$("input[name='serial_number_temp']").blur(function(){
		//鼠标选中(单击)事件
		$("li[name='sel_serial_li']").live("click",function(){
			var i = $(this).attr("lang");
			$("input[name='serial_number_temp']").val($("span[name='serial_number_"+i+"']").html());  //显示会员卡号
			$("input[name='serial_id']").val($("span[name='serial_id_"+i+"']").html());  //赋值会员ID号
			$("#sel_serial").css("display","none");
			$(this).unbind("click");
		});
		$("input[name='serial_number_temp']").unbind("keyup click");
	});
	//鼠标滑开事件
	$("li[name='sel_serial_li']").live("mouseleave",function(){
		$(this).css("background","none");
	});
	/**************************此段代码，查找礼品也有共享******************************/
	$(document).click(function(){
		//判断会员
		if($("#sel_serial").css("display") == "block"){
			$("input[name='serial_number_temp']").unbind("keyup");
			$("#sel_serial").css("display","none");
		}
		//判断礼品
		if($("#sel_gift").css("display") == "block"){
			$("input[name='gift_temp']").unbind("keyup");
			$("#sel_gift").css("display","none");
		}
	});
	/*************************此段代码，查找礼品也有共享End*****************************/
	//=======================================查找会员结束=======================================
	
	//=======================================查找礼品开始=======================================
	var gift_name = "";
	$("input[name='gift_temp']").focus(function(){
		$(this).unbind("keyup,click");
		$(this).keyup(function(event){
			if(event.keyCode == 40 || event.keyCode == 38){
				//上下移动待开发
			}else{
				//如果内容发生改变
				if($(this).val() != ""){
					if(gift_name != $(this).val()){
						gift_name = $(this).val();  //获取礼品名称
						//调用AJAX查找
						$.post($("#app_path").val()+"/main/add_sales_order/sel_gift/"+Math.random(),{gift_name: gift_name},function(data){
							$("#sel_gift").css("display","block");
							$("#sel_gift").html(data);
						});
					}
				}else{
					gift_name = "";
					$("#sel_gift").css("display","none");
				}
			}
		});
		$(this).click(function(){
			gift_name = $("input[name='gift_temp']").val();
			//调用AJAX查找
			$.post($("#app_path").val()+"/main/add_sales_order/sel_gift/"+Math.random(),{gift_name: gift_name},function(data){
				$("#sel_gift").css("display","block");
				$("#sel_gift").html(data);
			});
		});
	});
	//鼠标经过事件
	$("li[name='sel_gift_li']").live("mouseenter",function(){
		$(this).css("background","#F5F5F5");
	});
	//离开焦点事件
	$("input[name='gift_temp']").blur(function(){
		//鼠标选中(单击)事件
		$("li[name='sel_gift_li']").live("click",function(){
			var i = $(this).attr("lang");
			$("input[name='gift_temp']").val($("span[name='gift_name_"+i+"']").html());  //礼品名称
			$("input[name='gift_id']").val($("span[name='gift_id_"+i+"']").html());  //礼品ID号
			$("#sel_gift").css("display","none");
			$(this).unbind("click");
		});
		$("input[name='gift_temp']").unbind("keyup click");
	});
	//鼠标滑开事件
	$("li[name='sel_gift_li']").live("mouseleave",function(){
		$(this).css("background","none");
	});
	//=======================================查找礼品结束=======================================
	

	//删除指定行
	$("a[name='del_commodity']").live("click",function(){
		if(confirm("您确定要删除此行吗？")){
			var id = $(this).attr("id");
			id = id.match(/\d+/);
			$("#"+id).remove();
			if($("tr[name='content_tr']").length < 1){
				$("input[name='commodity_num']").val("");  //商品总数清空
				$("input[name='total_price']").val("");  //整单金额清空
			}else{
				$("input[name='num[]']").blur();
			}
		}
	});
 	
 	//计算数量，总价，整单数量和整单金额
	$("input[name='num[]'],input[name='tag_price[]']").live("blur",function(){
 		var id = $(this).attr("id");  //获取当前行的ID号
 		id = id.match(/\d+/);
		var tag_price = parseFloat($("#tag_price_"+id).val()) ? parseFloat($("#tag_price_"+id).val()) : 0;  //获取当前行的吊牌价
		var num = parseInt($("#num_"+id).val()) ? parseInt($("#num_"+id).val()) : 0;  //获取数量
		$("#total_"+id).html(tag_price*num ? tag_price*num : "");  //当前行的总价
		
		//整单数量
		var order_num = 0;
		$("input[name='num[]']").each(function(){
			var num = $(this).val();
			num = num.match(/^\d+$/);
			if(num != null){
				order_num += parseInt(num);
			}
		});
		$("input[name='commodity_num']").val(order_num ? order_num : "");
		//整单金额
		var order_price = 0;
		$("td[name='total']").each(function(){
			var price = $(this).html();
			price = price.match(/^\d+\.?\d+$/);
			if(price != null){
				order_price += parseFloat(price);
			}
		});
		$("input[name='total_price']").val(order_price);
	});
	
	//添加商品单击事件
	$("#add_commodity").click(function(){
		var commodity_number = $("input[name='commodity_number']").val();
		if(commodity_number == "请输入商品编号(按回车添加)" || commodity_number == ""){
			commodity_number = "-";
		}
		window.open($("#app_path").val()+"/main/add_sales_order/open_selection_commodity/"+commodity_number+"/"+Math.random(),"add_storage_order_open_selection_commodity","location=no,menubar=no,resizable=no,scrollbars=no,toolbar=no,width=800px,height=480px,left="+(($(parent.window).width()/2)-400)+"px,top="+(($(parent.parent.window).height()/2)-240)+"px");
	});
	
	//提交订单
	$("input[name='storage_submit']").click(function(){
		//日期防空验证
		if($("input[name='purchase_date']").val() == ""){
			alert("日期不能为空！");
			return false;
		}
		
		if($("input[name='total_price']").val() == ""){
			alert("请填写商品单价或数量！");
			return false;
		}
		//单价防空验证
		var purchase_price = true;
		$("input[name='purchase_price[]']").each(function(){
			if($(this).val() == ""){
				purchase_price = false;
				alert("商品单价不能为空！");
				$(this).focus();
				return false;
			}
		});
		if(!purchase_price){
			return false;
		}
		//数量防空验证
		var num = true;
		$("input[name='num[]']").each(function(){
			if($(this).val() == ""){
				num = false;
				alert("商品数量不能为空！");
				$(this).focus();
				return false;
			}
		});
		if(!num){
			return false;
		}
		if(!confirm("确定要提交此销售单吗？")){
			return false;
		}
		//把会员卡号内容复制到隐藏域中（防止input框缓存）
		$("input[name='serial_number']").val($("input[name='serial_number_temp']").val());
		$("input[name='serial_number_temp']").val("");
		//把礼品内容复制到隐藏域中（防止input框缓存）
		$("input[name='gift_name']").val($("input[name='gift_temp']").val());
		$("input[name='gift_temp']").val("");
		//修改方式
		$("input[name='mode']").val("storage_submit");
		//提交表单
		$("form[name='add_storage_order_form']").submit();
	});
});