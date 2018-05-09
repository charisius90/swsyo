<?
$title = "이용권 현황";
include_once('_head.php');

if(!$is_admin) {
	alert("접근 권한이 없습니다", "/");
}

$page = $_GET['rpage'] or $page = 1;
$page_rows = 15;
$sql = "SELECT COUNT(id) AS `cnt` FROM ticket";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함
$pages = new_paging($page_rows, $page, $total_page, '/act/listticket.php?'.'&amp;rpage=');

$sql="select * from ticket order by id desc limit {$from_record}, {$page_rows}";
$result=mysqli_query($db_conn, $sql);

$ticket_list=array();
while($row=mysqli_fetch_array($result)){
	$ticket_list[] = $row;//array_push($doctor_list, $row);
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
	<h3>이용권 현황</h3>
</div>

<div class="content">

<table class="table">
<tr>
	<th>ID</th>
	<th>종류</th>		
	<th class="col-md-2">구입</th>	
</tr>
<? foreach ($ticket_list as $val) { ?>
<tr>	
	<td><?=$val['mb_id']?></td>
	<td><?=$val['subject']?></td>
	<td><?=$val['created']?></td>	
</tr>	
<? } ?>
</table>

<?/*<pre><? var_export($ticket_list);?></pre>*/?>

<div style="text-align:center;"><? echo $pages; ?></div>

</div>
</div>
<? include_once('_foot.php'); ?>