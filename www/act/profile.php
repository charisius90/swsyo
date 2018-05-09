<?php 
$title = "선생님 프로필";
include_once('_head.php');
$id = $_GET['id'] or die("실행에 필요한 인자가 없습니다."); // 일단, 인자로 선생님 아이디를 받는다.
$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';

/* 로그인 여부 체크 */

if($is_member) { 
	$query = "select * from ticket where mb_id='{$member['mb_id']}' order by id desc limit 1";//$query = "SELECT * FROM ticket where mb_id = '{$member[mb_id]}'"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 		
		$ticket_info = mysqli_fetch_assoc($result);		
		if(!$ticket_info) {
			//alert("예약을 하시려면 티켓이 필요합니다", "/act/order.php"); // url 붙여서?
			// 경고창 보다는 등록버튼 비활성화 하고 티켓 구입버튼 추가
			$ticket_msg = "예약을 하시려면 이용권이 필요합니다.";
		}
		// 기간 체크 로직 추가
		
		$expiredtime = strtotime($ticket_info['expired']); 
		$nowtime = time();			
		if($nowtime > $expiredtime && $expiredtime > 0) { // expired 가 있고 현재시점이 지났다면
			$ticket_info = 0;
			$ticket_msg = "예약 이용권의 기한이 종료되었습니다.";
		}
		
	} else { 
		//echo "Error : ".mysqli_error($db_conn); 
	} 	
} else {
	$url = urlencode("/act/profile.php?id={$id}&latlng={$_GET['latlng']}&address={$_GET['address']}");
	//alert("로그인 정보가 필요한 페이지입니다", "/act/login.php?url={$url}");
}

$query = "SELECT * FROM doctor_list where id = '{$id}'"; 
$result = mysqli_query($db_conn, $query); 
if ( $result ) { 		
	$doctor_info = mysqli_fetch_assoc($result);
} else { 
	echo "Error : ".mysqli_error($db_conn); 
} 	

$query = "SELECT * FROM doctor_config where doctor = '{$id}'"; 
$result = mysqli_query($db_conn, $query); 
if ( $result ) { 		
	$doctor_config = mysqli_fetch_assoc($result);
	if(!$doctor_config) {
		//alert("등록된 예약 설정이 없습니다");
	}
} else { 
	echo "Error : ".mysqli_error($db_conn); 
} 

if($mode == 'favorite') {		
	$sql = "select count(id) as cnt from favorite where mb_id = '{$member[mb_id]}' and doctor_id = '{$id}'";
	$result = mysqli_query($db_conn, $sql); 
	$raw = mysqli_fetch_assoc($result);	
	if($raw['cnt']) {
		die("<script>alert('이미 즐겨찾기에 등록되어 있습니다');document.location.replace('/act/profile.php?id={$id}&latlng={$_GET['latlng']}&address={$_GET['address']}');</script>");		
	} else {		
		$sql="insert into favorite (mb_id, doctor_id, doctor_name, doctor_age, doctor_career) values ('{$member[mb_id]}', '{$id}', '{$doctor_info[name]}', '{$doctor_info[age]}', '{$doctor_info[career]}')";
		mysqli_query($db_conn, $sql);
		$inserted_id = mysqli_insert_id($db_conn);

		if ($inserted_id) {
			die("<script>alert('즐겨찾기에 등록되었습니다');document.location.replace('/act/profile.php?id={$id}&latlng={$_GET['latlng']}&address={$_GET['address']}');</script>");	
		}		
	}	
}

if($mode == 'register') {		

	$sql="insert into reservation (content, doctor_id, user_id, rsv_date, rsv_time, address, latlng, status, tel, email) values ('{$_POST[content]}', '{$_POST[doctor_id]}', '{$member[mb_id]}', '{$_POST[rsv_date]}', '{$_POST[rsv_time]}', '{$_POST[address]}', '{$_POST[latlng]}', '신청', '{$_POST[tel]}', '{$_POST[email]}')";

	mysqli_query($db_conn, $sql);
	$inserted_id = mysqli_insert_id($db_conn);

	if ($inserted_id) {
	echo "<script>alert('예약이 등록되었습니다');document.location.replace('/act/my.php');</script>";	
	} 
	die(var_export($_POST));
}

?>
<style>
@media (max-width: 768px) { 
.bottom_table{margin-top:-15px;}
}
</style>

<div class="container">

<div class="page_name">
	<h3><?=$title?></h3>
</div>

<div class="content">

<div class="row">
    <div class="col-sm-6 text-center well">
	
		<img src="/act/data/<?=$doctor_info['thumbnail']?>" class="img-thumbnail" width="200">
	
	</div>
	<div class="col-sm-6">
		<table class="table">
		<tr>
			<td width="100"><label for="info">이름</label></td>
			<td><? echo mb_substr($doctor_info['name'], 0, 1).'*'.mb_substr($doctor_info['name'], -1);  ?></td>
		</tr>
		<tr>
			<td><label for="info">성별 / 나이</label></td>
			<td>			
			<? if($doctor_info['gender'] == 'male') { ?>남<? } else { ?>여<? } ?> / <?=$doctor_info['age']?>세</td>
		</tr>
		<tr>
			<td><label for="info">경력</label></td>
			<td><?=$doctor_info['career']?>년</td>
		</tr>
		<tr>
			<td><label for="info">치료분야</label></td>
			<td>			
			<? $doctor_options=explode(',', $doctor_info['options']); ?>
			<? if(in_array(1, $doctor_options)) { ?>검사/진단<br><? } ?>
			<? if(in_array(2, $doctor_options)) { ?>언어발달<br><? } ?>
			<? if(in_array(3, $doctor_options)) { ?>발음<br><? } ?>
			<? if(in_array(4, $doctor_options)) { ?>말더듬<br><? } ?>
			<? if(in_array(5, $doctor_options)) { ?>그룹치료<br><? } ?>			
			</td>
		</tr>		
		</table>
	</div>

	<div class="col-sm-12 bottom_table">
		<table class="table">
			<tr>
				<td width="100"><label for="info">경력사항및 자기소개</label></td>
				<td><?=$doctor_info['introduction']?></td>
			</tr>
			<tr>
				<td><label for="info">위치</label></td>
				<td><?=$doctor_info['address']?></td>
			</tr>				
			<tr>
				<td><label for="info">범위</label></td>
				<td><?=$doctor_info['coverarea']?></td>
			</tr>
		</table>
	</div>

</div>

<hr>

<div style='text-align:center;'>
	<?/* if(!$is_member) { ?>	
		* 로그인을 하셔야 예약이 가능합니다.<br><br>
		<a href="/act/login.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn btn-default" role="button">로그인</a>
	<? } else if(!$doctor_config) { ?>	
		* 아직 등록된 예약 설정이 없습니다.		
	<? } else if(!$ticket_info) { ?>
		* <?=$ticket_msg?><br><br>
		<a href="/act/order.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn btn-default" role="button">이용권 구입</a>
	<? } else {  ?>		
		<a href="/act/reservation.php?id=<?=$id?>&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>" class="btn btn-primary" role="button">선생님 예약</a>
	<? } */?>		
	<? if($_GET['latlng'] && $_GET['address']) { ?>
	<a href="/act/profile.php?mode=favorite&id=<?=$id?>&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>" class="btn btn-default" role="button">즐겨찾기 등록</a>	
	&nbsp;
	<a href="/act/reservation.php?id=<?=$id?>&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>" class="btn btn-primary" role="button">선생님 예약</a>
	<? } else { ?>
	* 예약하시려면 선생님 찾기에서 주소를 먼저 입력하세요<br><br>
	<a href="/act/search.php" class="btn btn-primary" role="button">선생님 찾기</a>
	<? } ?>
</div>

</div>
</div>
<? include_once('_foot.php'); ?>