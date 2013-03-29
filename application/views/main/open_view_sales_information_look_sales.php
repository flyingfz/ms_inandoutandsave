<html>
	<head>
		<title>查看销售单</title>
		<script charset="utf-8" src="<?=base_url("public/js/jquery.js");?>"></script>
		<script charset="utf-8" src="<?=base_url("public/js/main/open_view_sales_information_look_sales.js");?>"></script>
		<link rel="stylesheet" type="text/css" href="<?=base_url("public/css/main/open_view_sales_information_look_sales.css");?>" />
	</head>
	<body>
		<div class="div_1">
			<div class="div_2">
				<div class="title_div">
					<span>销售单详情</span>
				</div>
			</div>
			<div class="div_3">
				<div class="basic_div">
					<ul>
						<li>销售单号：<?=$sales_basic->sales_order_number;?></li>
						<li>日期：<?=$sales_basic->sales_order_date;?></li>
						<li>会员卡号：<?=$sales_basic->serial_number;?></li>
						<li>出货仓库：<?=$sales_basic->warehouse_id;?></li>
					</ul>
					<ul>
						<li>备　　注：<?=$sales_basic->sales_order_mark;?></li>
					</ul>
				</div>
				<div class="content_div">
					<table border="1">
						<tr>
							<th>序号</th><th>商品编号</th><th>商品名称</th><th>品牌</th><th>颜色</th><th>尺码</th><th>单位</th><th>数量</th><th>单价</th><th>总价</th>
						</tr>
						<?php
							if($sales_detail_res){
								$i = 0;
								foreach($sales_detail_res as $sales_detail){
									$i++;
									echo "<tr>";
									echo "<td align='center'>{$i}</td>";
									echo "<td align='center'>{$sales_detail['commodity_number']}</td>";  //商品编号
									echo "<td align='left'>{$sales_detail['commodity_name']}</td>";  //商品名称
									echo "<td align='left'>{$sales_detail['brand']}</td>";  //品牌
									echo "<td align='left'>{$sales_detail['commodity_color']}</td>";  //颜色
									echo "<td align='left'>{$sales_detail['commodity_size']}</td>";  //尺码
									echo "<td align='left'>{$sales_detail['dan_wei']}</td>";  //单位
									echo "<td align='left'>{$sales_detail['commodity_num']}</td>";  //数量
									echo "<td align='left'>{$sales_detail['unit_price']}</td>";  //单价
									echo "<td align='left'>".($sales_detail['unit_price']*$sales_detail['commodity_num'])."</td>";  //总价
									echo "</tr>";
								}
							}else{
								echo "<tr>";
								echo "<td colspan='10' align='center'>此单出现错误！</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>