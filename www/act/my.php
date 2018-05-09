<?
$title = "마이 페이지";
include_once('_head.php');

if($is_member) { 
	$query = "SELECT * FROM ticket where mb_id = '{$member[mb_id]}' order by id desc"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 				
		while ($row = mysqli_fetch_assoc($result)) { 			
			$ticket_info[] = $row;
		}
	} else { 
		//echo "Error : ".mysqli_error($db_conn); 
	} 	
	
	$query = "SELECT * FROM reservation where user_id = '{$member[mb_id]}' order by id desc"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 				
		while ($row = mysqli_fetch_assoc($result)) { 			
			$reservation_info[] = $row;
		}		
	} else { 
		//echo "Error : ".mysqli_error($db_conn); 
	} 	
	
	$query = "SELECT * FROM favorite where mb_id = '{$member[mb_id]}' order by id desc"; 
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 							
		while ($row = mysqli_fetch_assoc($result)) { 			
			$favorite_info[] = $row;
		}		
	} else { 
		//echo "Error : ".mysqli_error($db_conn); 
	}
	
} else {	
	alert("로그인 정보가 필요한 페이지입니다", "/act/login.php?url=/act/my.php");
}
?>
<div class="container">
<div class="content">

<h4>예약 현황</h4>

<table class="table">
<tr>
	<th class="col-md-2">신청</th>
	<th width="80">선생님</th>	
	<th>예약일시</th>
	<!--<th>참조</th>-->
	<th width="50">상태</th>		
	<th>리뷰</th>
</tr>
<? foreach ($reservation_info as $val) { ?>
<tr>
	<td><a href="/act/viewrsv.php?id=<?=$val['id']?>&from=my"><?=substr($val['created'], 0, -3)?></a></td>
	<td><?=$val['doctor_name']?></td>
	<td><?=$val['rsv_date']?> <? echo str_replace('pm', '오후 ', str_replace('am', '오전 ', $val['rsv_time'])); ?>시</td>
	<!--<td><?=$val['content']?></td>-->
	<td><?=$val['status']?></td>		
	<td><a href="/act/review.php?rsv=<?=$val['id']?>">[리뷰]</a></td>
</tr>	
<? } ?>
</table>

<br><hr>

<h4>선생님 즐겨찾기</h4>

<table class="table">
<tr>	
	<th width="80">선생님</th>	
	<th width="80">나이</th>
	<th width="80">경력</th>
</tr>
<? foreach ($favorite_info as $val) { ?>
<tr>
	<td><a href="/act/profile.php?id=<?=$val['doctor_id']?>"><? echo mb_substr($val['doctor_name'], 0, 1).'*'.mb_substr($val['doctor_name'], -1);  ?></a></td>
	<td><?=$val['doctor_age']?></td>
	<td><?=$val['doctor_career']?></td>
</tr>	
<? } ?>
</table>

<br><hr>

<h4>이용권</h4>

<table class="table">
<tr>
	<th>종류</th>		
	<th class="col-md-2">구입</th>
	<!--<th>기타</th>-->
</tr>
<? foreach ($ticket_info as $val) { ?>
<tr>	
	<td><?=$val['subject']?></td>
	<td><?=$val['created']?></td>
	<!--<td></td>-->
</tr>	
<? } ?>
</table>

<a href="/act/order.php" class="btn btn-primary" role="button">이용권 구입</a>

<? if($is_doctor) { // 선생님 메뉴 표시 (프로필 / 예약설정 / 예약 현황 / 이용권 현황)>  나중에 탭으로 이동하던지?>

	<br><br><hr>	
	<div style="text-align:center;">
	<a href="/act/doctor.php" class="btn btn-default" role="button">선생님 페이지</a>
	</div>
	
<? } else { // 별도 메뉴로 넣자?>
	<!-- <a href="/act/register.php" class="btn btn-default" role="button">선생님으로 등록하기</a> -->
<? } ?>

</div>
</div>
<? include_once('_foot.php'); ?>