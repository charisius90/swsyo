<?
/*
이름 / 나이 / 성별
경력
자기소개
분야 
	검사/진단
	언어발달
	발음
	말더듬
주소지 
출장가능지역
사진

전문분야에 ‘그룹치료’ 추가해주시고 ‘출장가능지역’에서 ‘출장’은 빼주셔도 될 것 같습니다. 경력란에 ‘경력증빙서류 업로드’란이 필요할 것 같습니다.
*/
$title = "선생님 정보";
include_once('_head.php');

$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';
$id = $_GET['id'] or $id = $_POST['id'] or $id = '';
if($id) {
	$sql = "select * from doctor_list where id = '{$id}'";
    /* $doctor = sql_fetch($sql);	 */
	$result = mysqli_query($db_conn, $sql);
	$doctor  = mysqli_fetch_array($result);
}

// 썸네일 / 커버 업로드 처리
$target_dir = "data/";
$thumbnail_image = $doctor['thumbnail'] or $thumbnail_image = '';
if($_FILES['thumbnail']['name']) {				
	$target_file = uniqid().'_'.basename($_FILES['thumbnail']['name']);		
	move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_dir.$target_file);
	$thumbnail_image = $target_file;
}

$cfile = $doctor['cfile'] or $cfile = '';
if($_FILES['cfile']['name']) {				
	$target_file = uniqid().'_'.basename($_FILES['cfile']['name']);		
	move_uploaded_file($_FILES['cfile']['tmp_name'], $target_dir.$target_file);
	$cfile = $target_file;
}


if($mode == 'update'){

	$sql="update doctor_list set confirm='{$_POST['confirm']}' where id='{$id}'";

	mysqli_query($db_conn, $sql);

	echo "<script>alert('수정되었습니다');document.location.replace('/act/view.php?id=".$id."');</script>";	

}

?>
<div class="container">


<div class="page_name">
	<h3>선생님 정보</h3>
</div>

<div class="content">
	


	<table class="table">
	
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="<? if($doctor['name']) { ?>update<? } ?>">
	<input type="hidden" name="id" value="<?=$doctor['id']?>">
	<tr>
		<td class="sub_title">인증</td>
		<td>
		<? if($is_admin) { ?>
		
		<label class="radio-inline">
		  <input type="radio" name="confirm" id="" value="1" <? if($doctor['confirm']=='1'){ echo "checked";} ?>> 인증
		</label>
		<label class="radio-inline">
		  <input type="radio" name="confirm" id="" value="0" <? if($doctor['confirm']!='1'){ echo "checked";} ?>> 대기
		</label>
		<input type="submit" class="btn btn-primary" value="확인" style='margin-left:20px;'>
		
		<? } else { ?>
		
			<? if($doctor['confirm']=='1') { ?>			
				인증
			<? } else { ?>
				대기
			<? } ?>
		
		<? } ?>
		</td>
	</tr>
	</form>
	
	<tr>
		<td class="sub_title">이름</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
				<?=$doctor['name']?>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">나이</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<?=$doctor['age']?>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">성별</td>
		<td>
		<? echo ($doctor['gender']=='male')?"남":"여"; ?>
		</td>
	</tr>
	<tr>
		<td class="sub_title">사진</td>
		<td>		
		<!--span class='label label-info' id="thumbnail-file-info"><?=$doctor['thumbnail']?></span-->
	
		<? if($doctor['thumbnail']) { ?>
		<p style='margin-top:10px;'><img src="/act/data/<?=$doctor['thumbnail']?>"></p>
		<? } ?>

		</td>
	</tr>
	<tr>
		<td class="sub_title">연락처</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<?=$doctor['tel']?>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">경력</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<?=$doctor['career']?>년
			</div>
		</div>
		<div class="" style="margin-top:10px;">
		자격증 <a href="/act/data/<?=$doctor['cfile']?>"><span class='label label-info' id="career-file-info"><?=$doctor['cfile']?></span></a>
		<br><br>
		보수교육이수증 <a href="/act/data/<?=$doctor['ofile']?>"><span class='label label-info' id="o-file-info"><?=$doctor['ofile']?></span></a>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">경력사항및 자기소개</td>
		<td>		
		<?=$doctor['introduction']?>
		</td>
	</tr>

<? $doctor_options=explode(',', $doctor['options']); ?>
	<tr>
		<td class="sub_title">전문분야</td>
		<td>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="1" <? foreach($doctor_options as $value){if($value==1){echo "checked";break;}} ?> disabled>검사/진단</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="2" <? foreach($doctor_options as $value){if($value==2){echo "checked";break;}} ?> disabled>언어발달</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="3" <? foreach($doctor_options as $value){if($value==3){echo "checked";break;}} ?> disabled>발음</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="4" <? foreach($doctor_options as $value){if($value==4){echo "checked";break;}} ?> disabled>말더듬</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="5" <? foreach($doctor_options as $value){if($value==5){echo "checked";break;}} ?> disabled>그룹치료</label>
		</div>
		</td>
	</tr>

	<tr>
		<td class="sub_title">주소지</td>
		<td>		
		<input type="hidden" id="latlng" name="latlng" value="<?=$doctor['latlng']?>">
		<style type="text/css">
		.search { position:absolute;z-index:1000;top:20px;left:20px; }
		.search #address { width:150px;height:20px;line-height:20px;border:solid 1px #555;padding:5px;font-size:12px;box-sizing:content-box; }
		.search #submit { height:30px;line-height:30px;padding:0 10px;font-size:12px;border:solid 1px #555;border-radius:3px;cursor:pointer;box-sizing:content-box; }
		</style>
		<script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=MnPiezvMyet0mWVg3WA8&amp;submodules=panorama,geocoder,drawing,visualization"></script>
		<div id="map" style="width:100%;height:250px;">
		<div class="search" style="">
            <input id="address" name="address" type="text" placeholder="검색할 주소" value="<?=$doctor['address']?>" required  disabled/><?//=$doctor['address']?>
            <input id="submit" type="button" value="주소 검색" />
        </div>
		</div>						
		<script type="text/javascript">
        var map = new naver.maps.Map("map", {
			center: new naver.maps.LatLng(37.3595316, 127.1052133),
			zoom: 10,
			mapTypeControl: true
		});

		var infoWindow = new naver.maps.InfoWindow({
			anchorSkew: true
		});

		map.setCursor('pointer');

		// search by tm128 coordinate
		function searchCoordinateToAddress(latlng) {
			var tm128 = naver.maps.TransCoord.fromLatLngToTM128(latlng);

			infoWindow.close();

			naver.maps.Service.reverseGeocode({
				location: tm128,
				coordType: naver.maps.Service.CoordType.TM128
			}, function(status, response) {
				if (status === naver.maps.Service.Status.ERROR) {
					return alert('Something Wrong!');
				}

				var items = response.result.items,
					htmlAddresses = [];

				for (var i=0, ii=items.length, item, addrType; i<ii; i++) {
					item = items[i];
					addrType = item.isRoadAddress ? '[도로명 주소]' : '[지번 주소]';

					htmlAddresses.push((i+1) +'. '+ addrType +' '+ item.address);
					htmlAddresses.push('&nbsp&nbsp&nbsp -> '+ item.point.x +','+ item.point.y);
				}

				infoWindow.setContent([
						'<div style="padding:10px;min-width:200px;line-height:150%;">',
						'<h4 style="margin-top:5px;">검색 좌표 : '+ response.result.userquery +'</h4><br />',
						htmlAddresses.join('<br />'),
						'</div>'
					].join('\n'));

				infoWindow.open(map, latlng);
			});
		}

		// result by latlng coordinate
		function searchAddressToCoordinate(address) {
			naver.maps.Service.geocode({
				address: address
			}, function(status, response) {
				if (status === naver.maps.Service.Status.ERROR) {
					return alert('Something Wrong!');
				}

				var item = response.result.items[0],
					addrType = item.isRoadAddress ? '[도로명 주소]' : '[지번 주소]',
					point = new naver.maps.Point(item.point.x, item.point.y);

				infoWindow.setContent([
						'<div style="padding:10px;min-width:200px;line-height:150%;">',
						'<h4 style="margin-top:5px;">검색 주소 : '+ response.result.userquery +'</h4><br />',
						addrType +' '+ item.address +'<br />',
						'&nbsp&nbsp&nbsp -> '+ point.x +','+ point.y,
						'</div>'
					].join('\n'));


				map.setCenter(point);				
				infoWindow.open(map, point);
				// 추가
				$('#latlng').val(point.x +','+ point.y);
			});
		}

		function initGeocoder() {
			map.addListener('click', function(e) {
				searchCoordinateToAddress(e.coord);
			});

			$('#address').on('keydown', function(e) {
				var keyCode = e.which;

				/* if (keyCode === 13) { // Enter Key
					searchAddressToCoordinate($('#address').val());
				} */
			});

			$('#submit').on('click', function(e) {
				e.preventDefault();

				searchAddressToCoordinate($('#address').val());
			});

			searchAddressToCoordinate('<?=$doctor['address']?>');
		}

		naver.maps.onJSContentLoaded = initGeocoder;
		</script>		
		
		</td>
	</tr>
	<tr>
		<td class="sub_title">가능 지역</td>
		<td>
		<?=$doctor['coverarea']?>
		</td>
	</tr>
	<!--
	<tr>
		<td class="sub_title">이용자 리뷰</td>
		<td>
		
		</td>
	</tr>
	-->
	</table>

	<div style='text-align:center;margin-top:50px'>
	
	<? if($is_admin) { ?><a href="/act/list.php" class="btn btn-primary" role="button">목록으로</a><? } ?>
	<? if($is_doctor && $is_doctor['id'] == $id) { ?><a href="/act/doctor.php" class="btn btn-primary" role="button">선생님 페이지로</a><? } ?>
	<? if($is_admin || ($is_doctor && $is_doctor['id'] == $id)) { ?><a href="/act/register.php?id=<?=$doctor['id']?>" class="btn btn-primary" role="button">정보수정</a><? } ?>
	<!--<a href="" class="btn btn-default" role="button" onclick="is_del(); return false;">삭제</a>-->
	</div>
	<br><br>
	
</div>
</div>
<? include_once('_foot.php'); ?>