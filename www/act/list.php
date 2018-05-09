<?
$title = "선생님 목록";
include_once('_head.php');

if(!$is_admin) {
	alert("접근 권한이 없습니다", "/");
}

$page = $_GET['rpage'] or $page = 1;
$page_rows = 15;
$sql = "SELECT COUNT(id) AS `cnt` FROM doctor_list";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함
$pages = new_paging($page_rows, $page, $total_page, '/act/list.php?'.'&amp;rpage=');

$sql="select * from doctor_list order by id desc limit {$from_record}, {$page_rows}";
$result=mysqli_query($db_conn, $sql);

$doctor_list=array();
while($row=mysqli_fetch_array($result)){
	array_push($doctor_list, $row);
}
?>

<style>

.doctor_unit{margin:10px 0;font-size:16px;border-top:1px solid #e6e6e6;border-bottom:1px solid #e6e6e6;}
/* .doctor_unit:hover{background:#fff;} */
.doctor_thumb{margin-right:15px;}
.doctor_thumb img{width:70px;height:70px;}
.vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
	padding:0;
}

</style>

<div class="container">

<br>
<div style='text-align:center;margin-bottom: 10px;'>
	<a href="/act/list.php" class="btn btn-default" role="button">선생님 목록</a>
	<a href="/act/listrsv.php" class="btn btn-default" role="button">예약 현황</a>
	<a href="/act/listticket.php" class="btn btn-default" role="button">이용권 현황</a>
</div>

<div class="page_name">
	<h3>선생님 목록</h3>
</div>

<div class="content">
<!--<div><a href="/act/register.php" class="btn btn-primary" role="button">선생님 등록</a></div>-->
<table class="table">
<?foreach($doctor_list as $v){?>
<tr>	
	<td><a href="/act/view.php?id=<?=$v['id']?>" class="doctor_unit_a"><span class="doctor_thumb"><img src="/act/data/<?=$v['thumbnail']?>"></span></a></td>
	<td><a href="/act/view.php?id=<?=$v['id']?>" class="doctor_unit_a"><?=$v['name']?><span class="">(<?if($v['gender']=="male"){echo "남";}else{echo "여";}?>)</span></a></td>
	<td><?=$v['created']?></td>
	<td class="col-xs-2"><span class=""><?echo ($v['confirm']=='1')?"인증":"대기";?></span></td>
</tr>
<?/*
<a href="/act/view.php?id=<?=$v['id']?>" class="doctor_unit_a">
	<div class="doctor_unit row">
		<div class="col-md-1 col-sm-2 vcenter"><span class="doctor_thumb"><img src="/act/data/<?=$v['thumbnail']?>"></span></div><!--
    --><div class="col-md-2 col-sm-3 vcenter"><span class=""><?=$v['name']?></span><span class="">(<?if($v['gender']=="male"){echo "남";}else{echo "여";}?>)</span></div><!--
    --><div class="col-md-2 col-sm-3 vcenter"><span class=""><span>경력</span><?=$v['career']?></span></div><!--
    --><div class="col-md-2 col-sm-3 vcenter"><span class=""><?echo ($v['confirm']=='1')?"인증완료":"인증 대기중";?></span></div>		
	</div>
</a>
*/?>
<?}?>
</table>

<div style="text-align:center;"><? echo $pages; ?></div>

</div>
</div>
<? include_once('_foot.php'); ?>