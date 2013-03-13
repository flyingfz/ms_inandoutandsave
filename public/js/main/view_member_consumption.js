$(document).ready(function(){
	//删除提示
	$("a[name='del_member_consumption']").click(function(){
		if(confirm("您确定要删除此会员的消费记录吗（不可恢复）？")){
			return true;
		}
		return false;
	});
});