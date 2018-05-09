<?
$title = "선생님 등록";
include_once('_head.php');

$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';
$id = $_GET['id'] or $id = $_POST['id'] or $id = '';
if($id) {
	$sql = "select * from doctor_list where id = '{$id}'";
    /* $doctor = sql_fetch($sql);	 */
	$result = mysqli_query($db_conn, $sql);
	$doctor  = mysqli_fetch_array($result);
	$title = "선생님 정보수정";
} else {
	if($is_doctor) {
		$doctor  = $is_doctor;
		$title = "선생님 정보수정";
		$id = $doctor['id'];
	}
}

if($is_member) { 
	// 기존 선생님인지 체크 / 정보수정인지 아닌지 체크
	$sql = "select count(id) as cnt, id from doctor_list where mb_id = '{$member[mb_id]}'";
	$result = mysqli_query($db_conn, $sql);
	$row = mysqli_fetch_array($result);
	//if($row['cnt'] && !$id) alert("이미 선생님 등록을 하셨습니다", "/");
	if($is_admin || ($is_doctor && $is_doctor['id'] == $id)) { 

	} else {
		if($is_doctor && $is_doctor['id'] != $id) {
			alert("수정 권한이 없습니다", "/");
		}
	}
} else {	
	alert("로그인 정보가 필요한 페이지입니다", "/act/login.php?url=/act/register.php");
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

$ofile = $doctor['ofile'] or $ofile = '';
if($_FILES['ofile']['name']) {				
	$target_file = uniqid().'_'.basename($_FILES['ofile']['name']);		
	move_uploaded_file($_FILES['ofile']['tmp_name'], $target_dir.$target_file);
	$ofile = $target_file;
}

if($mode == 'register') {

	$options=implode(',', $_POST['options']);
	$sql="insert into doctor_list (mb_id, name, introduction, age, career, options, coverarea, thumbnail, cfile, ofile, address, latlng, tel, gender, confirm) values ('{$member[mb_id]}', '{$_POST['name']}', '{$_POST['introduction']}', '{$_POST['age']}', '{$_POST['career']}', '{$options}', '{$_POST['coverarea']}', '{$thumbnail_image}', '{$cfile}', '{$ofile}', '{$_POST['address']}', '{$_POST['latlng']}', '{$_POST['tel']}', '{$_POST['gender']}','0')"; 

	mysqli_query($db_conn, $sql);

	$inserted_id = mysqli_insert_id($db_conn);

	if ($inserted_id) {
	echo "<script>alert('등록되었습니다');document.location.replace('/act/view.php?id=".$inserted_id."');</script>";	
	} 

}elseif($mode == 'update'){

	$options=implode(',', $_POST['options']);
	$sql="update doctor_list set name='{$_POST['name']}', introduction='{$_POST['introduction']}', age='{$_POST['age']}', career='{$_POST['career']}', options='{$options}', coverarea='{$_POST['coverarea']}', thumbnail='{$thumbnail_image}', cfile='{$cfile}', ofile='{$ofile}', address='{$_POST['address']}', latlng='{$_POST['latlng']}', tel='{$_POST['tel']}', gender='{$_POST['gender']}' where id='{$id}'";

	mysqli_query($db_conn, $sql);

	echo "<script>alert('수정되었습니다');document.location.replace('/act/view.php?id=".$id."');</script>";	
}

?>
<div class="container">


<div class="page_name">
	<h3><?=$title?></h3>
</div>

<div class="content">
	
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="<? if($doctor['name']) { ?>update<? } else { ?>register<? } ?>">
	<input type="hidden" name="id" value="<?=$doctor['id']?>">

	<table class="table">
	<tr>
		<td class="sub_title">이름</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<input type="text" class="form-control" name="name" value="<?=$doctor['name']?>" required>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">나이</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<input type="number" class="form-control" name="age" value="<?=$doctor['age']?>" placeholder="33" required> 
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">성별</td>
		<td>
		<label class="radio-inline">
		  <input type="radio" name="gender" id="" value="male" <? if($doctor['gender']=='male'){ echo "checked";} ?>> 남
		</label>
		<label class="radio-inline">
		  <input type="radio" name="gender" id="" value="female" <? if($doctor['gender']=='female'){ echo "checked";} ?>> 여
		</label>
		</td>
	</tr>
	<tr>
		<td class="sub_title">사진</td>
		<td>		
		<label class="btn btn-primary" for="thumbnail-file-selector">
		<input id="thumbnail-file-selector" type="file" name="thumbnail" accept="image/*" style="display:none" onchange="$('#thumbnail-file-info').html(this.files[0].name)">
		사진 찾기
		</label>
		<span class='label label-info' id="thumbnail-file-info"><?=$doctor['thumbnail']?></span>
		<br>&nbsp;
		
		<? if($doctor['thumbnail']) { ?>
		<p><img src="/act/data/<?=$doctor['thumbnail']?>"></p>
		<? } ?>
		
		<div class='input_info'></div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">연락처</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<input type="text" class="form-control" name="tel" value="<?=$doctor['tel']?>" placeholder="010-1234-5678" required> 
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">경력</td>
		<td>
		<div class="row">
			<div class="col-sm-3">
			<input type="number" class="form-control" name="career" value="<?=$doctor['career']?>" placeholder="10" required> 
			</div>
		</div>
		<div class="" style="margin-top:20px;">
		<label class="btn btn-primary" for="career-file-selector">
		<input id="career-file-selector" type="file" name="cfile" style="display:none" onchange="$('#career-file-info').html(this.files[0].name)">
		자격증 사본 (필수)
		</label>
		<a href="/act/data/<?=$doctor['cfile']?>"><span class='label label-info' id="career-file-info"><?=$doctor['cfile']?></span></a>
		<br><br>
		
		<?// 보수교육 첨부파일 선택 ofile ?>
		
		<label class="btn btn-primary" for="o-file-selector">
		<input id="o-file-selector" type="file" name="ofile" style="display:none" onchange="$('#o-file-info').html(this.files[0].name)">
		보수교육이수증 사본
		</label>
		<a href="/act/data/<?=$doctor['ofile']?>"><span class='label label-info' id="o-file-info"><?=$doctor['ofile']?></span></a>		
		
		</div>
		</td>
	</tr>
	<tr>
		<td class="sub_title">경력사항및 자기소개</td>
		<td>		
		<textarea class="form-control" rows="5" name="introduction" required><?=$doctor['introduction']?></textarea>
		</td>
	</tr>
	
<? $doctor_options=explode(',', $doctor['options']); ?>
	<tr>
		<td class="sub_title">전문분야</td>
		<td>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="1" <? foreach($doctor_options as $value){if($value==1){echo "checked";break;}} ?>>검사/진단</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="2" <? foreach($doctor_options as $value){if($value==2){echo "checked";break;}} ?>>언어발달</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="3" <? foreach($doctor_options as $value){if($value==3){echo "checked";break;}} ?>>발음</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="4" <? foreach($doctor_options as $value){if($value==4){echo "checked";break;}} ?>>말더듬</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="options[]" value="5" <? foreach($doctor_options as $value){if($value==5){echo "checked";break;}} ?>>그룹치료</label>
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
            <input id="address" name="address" type="text" placeholder="검색할 주소" value="<?=$doctor['address']?>" required /><?//=$doctor['address']?>
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

			searchAddressToCoordinate('정자동 178-1');
		}

		naver.maps.onJSContentLoaded = initGeocoder;
		</script>		
		
		</td>
	</tr>
	<tr>
		<td class="sub_title">가능 지역</td>
		<td>
		<textarea class="form-control" rows="5" name="coverarea" required><?=$doctor['coverarea']?></textarea>
		</td>
	</tr>
	
	<tr>
		<td class="sub_title">동의 사항</td>
		<td>
		<input type="checkbox" required> 상기 기입한 내용은 사실에 근거하며, 허위내용 작성에 대한 책임은 본인에게 있음을 확인합니다.
		</td>
	</tr>
	
	</table>

	<div style='text-align:center;'>
	<input type="submit" class="btn btn-primary" value="등록">
	<a href="/act/doctor.php" class="btn btn-primary" role="button">나가기</a>
	<!--<a href="" class="btn btn-default" role="button" onclick="is_del(); return false;">삭제</a>-->
	</div>
	
	</form>	
	<br><br>
	
	
</div>
</div>
<? include_once('_foot.php'); ?>