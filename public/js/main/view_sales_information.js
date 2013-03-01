$(document).ready(function(){
	//删除提示
	$("a[name='del_sale_order']").click(function(){
		if(confirm("您确定要删除此销售单吗（不可恢复）？")){
			return true;
		}
		return false;
	});
});