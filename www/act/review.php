<?
$title = "리뷰";
include_once('_head.php');
$rsv_id = $_GET['rsv'];
$doctor_id = $_GET['doctor_id'];
$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';

if($mode == 'write') { // 리뷰등록
	
	$sql = "insert into review (content, user_id, user_name, doctor_id, satisfaction, reservation_id) values ('{$_POST[content]}', '{$_POST[user_id]}', '{$_POST[user_name]}', '{$_POST[doctor_id]}', '{$_POST[satisfaction]}', '{$_POST[reservation_id]}')";
	mysqli_query($db_conn, $sql);
	$inserted_id = mysqli_insert_id($db_conn);
	
	$sql = "update doctor_list set review = review + 1 where id = '{$_POST[doctor_id]}'";
	mysqli_query($db_conn, $sql);

	if ($inserted_id) {
		echo "<script>alert('리뷰가 등록되었습니다');document.location.replace('/act/my.php');</script>";	
	}  else { 
		die("Error : ".mysqli_error($db_conn)); 
	} 
}

if($rsv_id) { // rsv_id 를 받아서 일치하는 정보가 있으면 뿌리고 아니면 쓰기가능

	// 예약정보 쿼리
	$query = "SELECT * FROM reservation where id = '{$rsv_id}'"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 		
		$reservation_info = mysqli_fetch_assoc($result);		
	} 	
	
	// 예약정보 쿼리
	$query = "SELECT * FROM doctor_list where id = '{$reservation_info[doctor_id]}'"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 		
		$doctor_info = mysqli_fetch_assoc($result);		
	} 	
	
	// 리뷰정보 쿼리
	$query = "SELECT * FROM review where reservation_id = '{$rsv_id}'"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 		
		$review_info = mysqli_fetch_assoc($result);		
	} 	
	
} else if($doctor_id) { // 선생님 id 가 있다면 일치하는 리뷰를 전부 뿌린다
	
}

?>
<div class="container">
<div class="content">

<div class="page_name" style='margin-bottom:30px;'>
	<h3>예약 리뷰</h3>
</div>

<? if($doctor_id) { // 리뷰 전부 ?>



<? } else { ?>

    <table class="table">
	<tr>
		<th>선생님</th>
		<th>예약일자</th>
		<th>예약장소</th>
	</tr>
	<tr>
		<td><?=$doctor_info[name]?></td>
		<td><?=$reservation_info[rsv_date]?> <?=$reservation_info[rsv_time]?></td>
		<td><?=$reservation_info[address]?></td>
	</tr>	
	</table>

	<? if($review_info) { // 리뷰 ?>

		<label for="satisfaction">이름:</label> <?=$review_info['user_name']?>
		<br>
		
		<label for="satisfaction">만족도:</label> <?=$review_info['satisfaction']?>/5점
		<br>
		
		<label for="content">리뷰:</label>
		<textarea name="content" class="form-control" rows="12" readonly><?=$review_info['content']?></textarea>
		
	<? } else { // 리뷰 폼 ?>
	
		<? if($member['mb_id'] == $reservation_info['user_id']) { // 예약자만 폼이 나오도록 ?>

		<form method="POST">
		<input type="hidden" name="mode" value="write">
		<input type="hidden" name="user_id" value="<?=$member['mb_id']?>">
		<input type="hidden" name="user_name" value="<?=$member['mb_name']?>">
		<input type="hidden" name="doctor_id" value="<?=$reservation_info['doctor_id']?>">
		<input type="hidden" name="reservation_id" value="<?=$rsv_id?>">
		
		<label for="satisfaction">만족도:</label> 5점만점입니다
		<select name="satisfaction" class="form-control" required>
			<option value="">선택해주세요</option>
			<option value="5">5</option>
			<option value="4">4</option>
			<option value="3">3</option>
			<option value="2">2</option>
			<option value="1">1</option>
		</select>
		<br>
		<label for="content">리뷰:</label>
		<textarea name="content" class="form-control" rows="7" required></textarea>
		<br>		
		<input type="submit" value="리뷰 등록">
		</form>
		
		<? } else { ?>
		
		<br>
		<label>* 아직 등록된 리뷰가 없습니다.</label>
		<br>
		
		<? } ?>
		
	<? } ?>
<? } ?>

</div>
</div>
<? include_once('_foot.php'); ?>