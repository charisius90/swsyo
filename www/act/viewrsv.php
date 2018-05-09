<?
$title = "예약 내용";
include_once('_head.php');
$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';
$id = $_GET['id'] or alert("잘못된 요청입니다");

$query = "SELECT * FROM reservation where id = '{$id}'"; 
$result = mysqli_query($db_conn, $query); 
if ( $result ) { 					
	$reservation_info = mysqli_fetch_assoc($result);
} else { 
	//echo "Error : ".mysqli_error($db_conn); 
}

// 예약 내용뿌리고 지도에 예약자 좌표 표시 OK
// 예약상태 신청 > 확인 으로 전환 OK
// 예약자 본인 & 선생님 & 그리고 관리자만 볼수 있어야 한다? OK
// 예약상태 신청/확인 > 취소 전환 신청자일 경우 >> 예약날짜 이틀전까지만 가능하도록

if($member['mb_id'] == 'admin' || $reservation_info['doctor_id'] == $is_doctor['id'] || $reservation_info['user_id'] == $member['mb_id']) {
	
} else {
	alert("권한이 없습니다");
}

if($mode == 'update'){
	$sql = "update reservation set status='확인' where id='{$id}'";
	mysqli_query($db_conn, $sql);
	echo "<script>alert('예약상태가 수정되었습니다');document.location.replace('/act/viewrsv.php?id=".$id."&from=doctor');</script>";
}

if($mode == 'cancel'){
	
	if($reservation_info['rsv_date'] > date('Y-m-d',strtotime("+1 days"))) { // 예약날짜 이틀전까지만 가능하도록
		$sql = "update reservation set status='취소' where id='{$id}'";
		mysqli_query($db_conn, $sql);
		echo "<script>alert('예약상태가 수정되었습니다');document.location.replace('/act/viewrsv.php?id=".$id."&from=my');</script>";				
	} else {
		echo "<script>alert('예약취소가 거부되었습니다');document.location.replace('/act/viewrsv.php?id=".$id."&from=my');</script>";				
	}
}
?>
<div class="container">
<div class="content">

<h4>예약 내용</h4>

<?/*<xmp><? print_r($reservation_info); ?></xmp>*/?>
<?/*<xmp><? print_r($is_doctor); ?></xmp>*/?>

<table class="table">
<tr>
	<td width="100">선생님</td>
	<td><?=$reservation_info['doctor_name']?></td>
</tr>
<tr>
	<td>신청</td>
	<td><?=$reservation_info['created']?></td>
</tr>
<tr>
	<td>상태</td>	
	<? if($_GET['from']=='doctor') { ?>
	
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="update">
	<input type="hidden" name="id" value="<?=$id?>">	
	<td><?=$reservation_info['status']?>
	<? if($reservation_info['status']=='신청' && $reservation_info['doctor_id'] == $is_doctor['id']) { ?>	
	<input type="submit" class="btn btn-primary btn-sm" value="확인" style='margin-left:20px;'> * 예약확인을 하시려면 클릭하세요. 이후 동일시간대 예약은 신청되지 않습니다.	
	<? } ?>	
	</td>
	</form>
	
	<? } else if($_GET['from']=='my') { ?>
	
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="cancel">
	<input type="hidden" name="id" value="<?=$id?>">	
	<td><?=$reservation_info['status']?>
	<? if($reservation_info['status']!='취소') { ?>
	<input type="submit" class="btn btn-primary btn-sm" value="취소" style='margin-left:20px;'> * 예약취소를 하시려면 클릭하세요. 예약일 2일전까지 취소가능합니다.
	<? } ?>
	</td>
	</form>
	
	<? } else { ?>
	
	<td><?=$reservation_info['status']?></td>
	
	<? } ?>	
</tr>
<tr>
	<td>예약</td>
	<td><?=$reservation_info['rsv_date']?> <? echo str_replace('pm', '오후 ', str_replace('am', '오전 ', $reservation_info['rsv_time'])); ?>시</td>
</tr>
<tr>
	<td>주소</td>
	<td><?=$reservation_info['address']?> <?//=$reservation_info['latlng']?><br><br>

		<?
		if($reservation_info['latlng']) {
			list($lat, $lng) = explode(',', $reservation_info['latlng']);
		}
		?>
	
		<script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=MnPiezvMyet0mWVg3WA8&amp;submodules=panorama,geocoder,drawing,visualization"></script>		
		<div id="map" style="width:100%;height:350px;"></div>
		
		<script type="text/javascript">
        var map = new naver.maps.Map("map", {			
			center: new naver.maps.LatLng(<?=$lng?>, <?=$lat?>),
			zoom: 8,
			mapTypeControl: true
		});

		var infoWindow = new naver.maps.InfoWindow({
			anchorSkew: true
		});

		map.setCursor('pointer');

		function initGeocoder() {
			map.addListener('click', function(e) {
				//searchCoordinateToAddress(e.coord);
			});
			var mymarker = new naver.maps.Marker({
				position: new naver.maps.LatLng(<?=$lng?>, <?=$lat?>),
				map: map,
				icon: {
					content: '<img src="/img/pin_default.png" alt="" ' +
							 'style="margin: 0px; padding: 0px; border: 0px solid transparent; display: block; max-width: none; max-height: none; ' +
							 '-webkit-user-select: none; position: absolute; width: 22px; height: 35px; left: 0px; top: 0px;">',
					size: new naver.maps.Size(22, 35),
					anchor: new naver.maps.Point(11, 35)
				}
			});
		}

		naver.maps.onJSContentLoaded = initGeocoder;		
		</script>		
	
	</td>
</tr>
<tr>
	<td>신청자</td>
	<td><?=$reservation_info['user_name']?></td>
</tr>
<tr>
	<td>연락처</td>
	<td><?=$reservation_info['tel']?></td>
</tr>
<tr>
	<td>이메일</td>
	<td><?=$reservation_info['email']?></td>
</tr>
<tr>
	<td>참조</td>
	<td><? echo nl2br($reservation_info['content']);?></td>
</tr>
</table>

<br><hr>	
<div style="text-align:center;">
<a href="<? if($_GET['from']=='doctor') { ?>/act/doctor.php<? } else if($_GET['from']=='my') { ?>/act/my.php<? } else if($_GET['from']=='admin') { ?>/act/listrsv.php<? } ?>" class="btn btn-default" role="button">뒤로</a>
</div>

</div>
</div>
<? include_once('_foot.php'); ?>