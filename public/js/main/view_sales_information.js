$(document).ready(function(){
	//删除提示
	$("a[name='del_sale_order']").click(function(){
		if(confirm("您确定要删除此销售单吗（不可恢复）？")){
			return true;
		}
		return false;
	});
	
	//查看指定销售单
	$("a[name='look_sales_order']").click(function(){
		window.open($("#app_path").val()+"/main/open_view_sales_information_look_sales/index/"+$(this).attr("lang")+"/"+Math.random(),"open_view_sales_information_look_sales","location=no,menubar=no,resizable=no,scrollbars=no,toolbar=no,width=800px,height=480px,left="+(($(parent.window).width()/2)-400)+"px,top="+(($(parent.parent.window).height()/2)-240)+"px");
	});
});