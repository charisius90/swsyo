<?php 
$title = "선생님 예약";
include_once('_head.php');
$id = $_GET['id'] or die("실행에 필요한 인자가 없습니다."); // 일단, 인자로 선생님 아이디를 받는다.
$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';

/* 로그인 여부 체크 */

if($is_member) { 
	$query = "select * from ticket where mb_id='{$member['mb_id']}' and subject like '회원%' order by id desc limit 1";//$query = "SELECT * FROM ticket where mb_id = '{$member[mb_id]}'"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 		
		$ticket_info = mysqli_fetch_assoc($result);		
		if(!$ticket_info) {
			//alert("예약을 하시려면 티켓이 필요합니다", "/act/order.php"); // url 붙여서?
			// 경고창 보다는 등록버튼 비활성화 하고 티켓 구입버튼 추가
			$ticket_msg = "예약을 하시려면 이용권이 필요합니다.";
		} else {			
			// length 기간 체크 로직 추가				
			$c = $ticket_info['created'];
			$a = str_replace('m', ' month', $ticket_info['length']);				
			if(date("Y-m-d H:i:s", strtotime($c." +".$a)) < date("Y-m-d H:i:s")) {
				$ticket_info = 0;
				$ticket_msg = "예약 이용권의 기한이 종료되었습니다.";
			}
			
			// 빌링 체크로직
			/*
			$expiredtime = strtotime($ticket_info['expired']); 
			$nowtime = time();			
			if($nowtime > $expiredtime && $expiredtime > 0) { // expired 가 있고 현재시점이 지났다면		
				$ticket_info = 0;
				$ticket_msg = "예약 이용권의 기한이 종료되었습니다.";
			}
			*/				
		}		
		// 무료 이용기간 로직 추가 g5_member 생성시점 컬럼으로 기간체크하고 ticket 체크 스킵 mb_datetime
		if(date("Y-m-d H:i:s", strtotime($member['mb_datetime']." +1 month")) > date("Y-m-d H:i:s")) {
			$ticket_info = 1;			
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

if($mode == 'register') {		

	$sql="insert into reservation (content, doctor_id, doctor_name, user_id, user_name, rsv_date, rsv_time, address, latlng, status, tel, email) values ('{$_POST[content]}', '{$_POST[doctor_id]}', '{$_POST[doctor_name]}', '{$member[mb_id]}', '{$member[mb_name]}', '{$_POST[rsv_date]}', '{$_POST[rsv_time]}', '{$_POST[address]}', '{$_POST[latlng]}', '신청', '{$_POST[tel]}', '{$_POST[email]}')";

	mysqli_query($db_conn, $sql);
	$inserted_id = mysqli_insert_id($db_conn);

	if ($inserted_id) {
	echo "<script>alert('예약이 등록되었습니다');document.location.replace('/act/my.php');</script>";	
	} 
	die(var_export($_POST));
}

?>
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
			<td><? echo mb_substr($doctor_info['name'], 0, 1).'*'.mb_substr($doctor_info['name'], -1); ?></td>
		</tr>
		<tr>
			<td width="100"><label for="info">예상치료시간</label></td>
			<td><?=$doctor_config['timeunit']?> 시간 가량</td>
		</tr>
		<tr>
			<td width="100"><label for="info">예상비용</label></td>
			<td><?=$doctor_config['price']?> 원</td>
		</tr>
		<tr>
			<td width="100"><label for="info">안내</label></td>
			<td><?=$doctor_config['comment']?></td>
		</tr>  					
		</table>
	</div>
</div>

<hr>

<form method="post">
<input type="hidden" name="mode" value="<? if($doctor['name']) { ?>update<? } else { ?>register<? } ?>">
<input type="hidden" name="doctor_id" value="<?=$doctor_info['id']?>">
<input type="hidden" name="doctor_name" value="<?=$doctor_info['name']?>">
<input type="hidden" name="latlng" value="<?=$_GET['latlng']?>">
<input type="hidden" name="address" value="<?=$_GET['address']?>">

<link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
<script>
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년',
		minDate: 0
    });		
	<? 
	$config_workday = explode(',', $doctor_config['workday']);
	$script_array = array();
	if(!in_array('1', $config_workday)){ $script_array[] = 'date.getDay() != 1'; }
	if(!in_array('2', $config_workday)){ $script_array[] = 'date.getDay() != 2'; }
	if(!in_array('3', $config_workday)){ $script_array[] = 'date.getDay() != 3'; }
	if(!in_array('4', $config_workday)){ $script_array[] = 'date.getDay() != 4'; }
	if(!in_array('5', $config_workday)){ $script_array[] = 'date.getDay() != 5'; }
	if(!in_array('6', $config_workday)){ $script_array[] = 'date.getDay() != 6'; }
	if(!in_array('7', $config_workday)){ $script_array[] = 'date.getDay() != 0'; }
	$script_str = implode(' && ', $script_array);
	?>	
    $(function() {
        $("#datepicker1").datepicker({
			<? if(count($config_workday) < 7 ) { ?>beforeShowDay: function(date){ return [<?=$script_str?>, ""]}<? } ?>
		});
    });
	
	function check_rsv_time(rd) {
		$.post(
			"/act/ajax_rsv_time.php",
			{ doctor: "<?=$id?>", rsv_date: rd }
		)
		.done(function(msg) {			
			$("#sel2").html(msg);
			//alert(msg);
		});
	}
</script>

<div class="form-group">
<label for="sel1">날짜</label>
<input type="text" id="datepicker1" class="form-control" name="rsv_date" onchange="check_rsv_time(this.value);" required>
</div>

<div class="form-group">
<label for="sel1">시간</label>
<select class="form-control" id="sel2" name="rsv_time" required>
	<? list($timeas, $timeae, $timeps, $timepe) = explode(',', $doctor_config['worktime']); ?>
	<?	
	for($i = $timeas; $i <=$timeae; $i++) {
	?>
	<option value="am<?=$i?>">오전 <?=$i?>시</option>
	<?
	}
	?>
	<?	
	for($i = $timeps; $i <=$timepe; $i++) {
	?>
	<option value="pm<?=$i?>">오후 <?=$i?>시</option>
	<?
	}
	?>
</select>
</div>

<div class="form-group">
<label for="sel1">연락처</label>
<input type="text" class="form-control" name="tel" placeholder="010-xxxx-xxxx" required>
</div>

<div class="form-group">
<label for="sel1">이메일</label>
<input type="text" class="form-control" name="email" placeholder="xxxxx@xxxxx.com" required>
</div>

<div class="form-group">
	<label for="comment">참조</label>
	<textarea class="form-control" rows="5" id="content" name="content"></textarea>
</div>

<div style='text-align:center;'>
	<? if(!$is_member) { ?>	
		* 로그인을 하셔야 예약이 가능합니다.<br><br>
		<a href="/act/login.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn btn-default" role="button">로그인</a>
	<? } else if(!$doctor_config) { ?>	
		* 아직 등록된 예약 설정이 없습니다.		
	<? } else if(!$ticket_info) { ?>
		* <?=$ticket_msg?><br><br>
		<a href="/act/order.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn btn-default" role="button">이용권 구입</a>
	<? } else {  ?>
		<input type="submit" class="btn btn-primary" value="등록">
	<? } ?>	
</div>
<br><br>
</form>

</div>
</div>
<? include_once('_foot.php'); ?>