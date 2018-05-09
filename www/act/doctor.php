<?
$title = "선생님 페이지";
include_once('_head.php');
/*
우선 예약 보기 링크 - readrsv.php > 예약내용 디스플레이 > 
다음으로 페이링 라인 - ajax 로 처리하던가... 그렇게 할까? doctor.php 에서는 ajax 로 처리할 필요가 없다 리스팅 할게 하나뿐이니까
*/

if($is_member) { 
	if($is_doctor) {		
		$page = $_GET['rpage'] or $page = 1;
		$page_rows = 5;
		
		$sql = "SELECT COUNT(id) AS `cnt` FROM reservation WHERE doctor_id = '{$is_doctor[id]}'";
		$row = sql_fetch($sql);
		$total_count = $row['cnt'];
		$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
		$from_record = ($page - 1) * $page_rows; // 시작 열을 구함
		$pages = new_paging($page_rows, $page, $total_page, '/act/doctor.php?'.'&amp;rpage=');
		
		$query = "SELECT * FROM reservation where doctor_id = '{$is_doctor[id]}' order by id desc limit {$from_record}, {$page_rows}"; 
		$result = mysqli_query($db_conn, $query); 
		if ( $result ) { 					
			while ($row = mysqli_fetch_assoc($result)) { 			
				$reservation_doctor_info[] = $row;
			}		
		} else { 
			//echo "Error : ".mysqli_error($db_conn); 
		} 	
	}
	
} else {	
	alert("로그인 정보가 필요한 페이지입니다", "/act/login.php?url=/act/my.php");
}
?>
<div class="container">
<div class="content">

<? if($is_doctor) { // 선생님 메뉴 표시 (프로필 / 예약설정 / 예약 현황 / 이용권 현황)>  나중에 탭으로 이동하던지?>
	
	<a href="/act/view.php?id=<?=$is_doctor['id']?>" class="btn btn-default" role="button">선생님 정보조회</a>&nbsp;
	<a href="/act/config.php?id=<?=$is_doctor['id']?>" class="btn btn-default" role="button">선생님 예약설정</a>	
	<br><br>	
	<?if($is_doctor['confirm']) { // 인증여부 표기?> * 선생님 인증이 완료되었습니다. 예약설정을 마치시면 예약을 받으실 수 있습니다. <? } else { ?> * 선생님 인증이 진행중입니다. 예약설정을 미리 해두세요. <? } ?>		
	
	<?// 예약설정이 되어야지 예약이 가능한 멘트 ?>		
	
	<br><br>
	<h4>선생님 예약 현황</h4>
	
	<table class="table">
	<tr>
		<th class="col-md-2">신청</th>
		<th>이름</th>	
		<th>예약일시</th>
		<th>참조</th>
		<th>상태</th>	
		<th>리뷰</th>
	</tr>
	<? foreach ($reservation_doctor_info as $val) { ?>
	<tr>	
		<td><a href="/act/viewrsv.php?id=<?=$val['id']?>&from=doctor"><?=substr($val['created'], 0, -3)?></a></td> <?// 초단위 생략 예약내용을 볼 수 있도록 링크?>
		<td><?=$val['user_name']?></td>
		<td><?=$val['rsv_date']?> <? echo str_replace('pm', '오후 ', str_replace('am', '오전 ', $val['rsv_time'])); ?>시</td>
		<td><?=$val['content']?></td>
		<td><?=$val['status']?></td>
		<th><a href="/act/review.php?rsv=<?=$val['id']?>">[리뷰]</a></th>
	</tr>	
	<? } ?>
	</table>	

	<div style="text-align:center;"><? echo $pages; ?></div>
	
<? } else { // 별도 메뉴로 넣자?>
	<!-- <a href="/act/register.php" class="btn btn-default" role="button">선생님으로 등록하기</a> -->
<? } ?>

</div>
</div>
<? include_once('_foot.php'); ?>