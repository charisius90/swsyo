<?php 
$title = "선생님 찾기";
include_once('_head.php');

if($_GET['latlng']) {
	list($lat, $lng) = explode(',', $_GET['latlng']);
}

if($_GET['mode'] == 'search') {	

	// WGS84 거리 계산
	function getDistance($lat1, $lng1, $lat2, $lng2) {
		$earth_radius = 6371;
		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lng2 - $lng1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * asin(sqrt($a));
		$d = $earth_radius * $c;
		return $d;
	}

	// 컬럼 정렬
	function sort_col($table, $colname) {
		$tn = $ts = $temp_num = $temp_str = array();
		foreach ($table as $key => $row) {
			if (is_numeric(substr($row[$colname], 0, 1))) {
				$tn[$key] = $row[$colname];
				$temp_num[$key] = $row;
			} else {
				$ts[$key] = $row[$colname];
				$temp_str[$key] = $row;
			}
		}
		unset($table);
		array_multisort($tn, SORT_ASC, SORT_NUMERIC, $temp_num);
		array_multisort($ts, SORT_ASC, SORT_STRING, $temp_str);
		return array_merge($temp_num, $temp_str);
	}
	
	$query = "SELECT * FROM ticket where subject like '선생님%' order by id desc"; 
	$result = mysqli_query($db_conn, $query); 
	$ticket_info = array();
	if ($result) { 			
		while ($row = mysqli_fetch_assoc($result)) { 			
			if(!$ticket_info[$row['mb_id']]) { $ticket_info[$row['mb_id']] = $row; }
		}
	}	

	$cond = ""; $ord = "";
	if($_GET['order'] == 'nam') {
		$cond = "gender = 'male' and ";
	} else if($_GET['order'] == 'yeo') {
		$cond = "gender = 'female' and ";
	} else if($_GET['order'] == 'career') {
		$ord = " order by career desc";
	} else if($_GET['order'] == 'price') {
		$ord = " order by price asc";
	} else if($_GET['order'] == 'review') {
		$ord = " order by review desc";
	}		
	
	$query = "SELECT * FROM doctor_list where {$cond}confirm = '1'{$ord}";	
	$result = mysqli_query($db_conn, $query); 
	if ( $result ) { 
		//echo "조회된 행의 수 : ".mysqli_num_rows($result)."<br />"; 
		//$i=0;
		while ($row = mysqli_fetch_assoc($result)) {					
			list($ilat, $ilng) = explode(',', $row['latlng']);	
			/*$doctor_list[$i] = $row;			
			$doctor_list[$i]['distance'] = number_format(getDistance($lat, $lng, trim($ilat), trim($ilng)), 2);
			$i++;*/			
			
			//$row['distance'] = number_format(getDistance($lat, $lng, trim($ilat), trim($ilng)), 2);
			$d = number_format(getDistance($lat, $lng, trim($ilat), trim($ilng)), 2);
			$dist_array[] = $d;
			$row['distance'] = $d;
			
			if($ticket_info[$row['mb_id']]) { // 이용권 여부도 체크 // ticket 에 닥터 id 는 저장되지 않는다. doctor_list 에는 mb_id 가 있다												 
				$c = $ticket_info[$row['mb_id']]['created'];
				$a = str_replace('m', ' month', $ticket_info[$row['mb_id']]['length']);				
				if(date("Y-m-d H:i:s", strtotime($c." +".$a)) > date("Y-m-d H:i:s")) {											
					$doctor_list[] = $row;
				}
			} else if (date("Y-m-d H:i:s", strtotime($row['created']." +1 month")) > date("Y-m-d H:i:s")) { // 무료 이용기간 로직 추가 doctor_lsit 생성시점 created 컬럼으로 기간체크하고
				$doctor_list[] = $row;					
			}
			//$date_list[] = date("Y-m-d H:i:s", strtotime($row['created']." +1 month"));
			//$doctor_list[] = $row;		
		} // 결과 해제 mysqli_free_result($result); 
	} else { 
		echo "Error : ".mysqli_error($db_conn); 
	} 	
	
	if(!$ord) $doctor_list = sort_col($doctor_list, 'distance');	
	sort($dist_array);
}

?>
<div class="container">

<div class="page_name">
	<!--<h3><?=$title?></h3>-->
</div>

<div class="content">
		
		<style type="text/css">
		.search { position:absolute;z-index:1000;top:20px;left:20px; }
		.search #address { width:150px;height:20px;line-height:20px;border:solid 1px #555;padding:5px;font-size:12px;box-sizing:content-box; }
		.search #submit { height:30px;line-height:30px;padding:0 10px;font-size:12px;border:solid 1px #555;border-radius:3px;cursor:pointer;box-sizing:content-box; }
		.infospan {background: white; padding:3px; margin:5px 0; display:block; border:1px solid #999999; border-radius: 5px;}
		</style>
		<script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=MnPiezvMyet0mWVg3WA8&amp;submodules=panorama,geocoder,drawing,visualization"></script>
		
		* 거주지 주소를 검색하시면 가까운 위치의 선생님부터 알려드립니다. <br><br>
		
		<div id="map" style="width:100%;height:350px;">
		<div class="search" style="">			
            <input id="address" name="address" type="text" placeholder="거주지 주소검색" value="<?=$_GET['address']?>" /><?//=$doctor['address']?>
            <input id="submit" type="button" value="주소 검색" />
			<? if($dist_array[0] > 10) { ?><br><span class="infospan">! 아직 10km이내에 선생님이 안계시네요 !</span><? } ?>
        </div>
		</div>						
		<script type="text/javascript">
        var map = new naver.maps.Map("map", {
			<? if($_GET['latlng']) { ?>
			center: new naver.maps.LatLng(<?=$lng?>, <?=$lat?>),
			zoom: 8,
			<? } else { ?>
			center: new naver.maps.LatLng(37.3595316, 127.1052133),
			zoom: 5,
			<? } ?>			
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
					return alert('주소를 찾을 수 없습니다.');
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
				// 추가
				//$('#latlng').val(latlng);
			});
		}

		// result by latlng coordinate
		function searchAddressToCoordinate(address) {
			naver.maps.Service.geocode({
				address: address
			}, function(status, response) {
				if (status === naver.maps.Service.Status.ERROR) {
					return alert('더 상세한 주소를 입력하세요!');
				}

				var item = response.result.items[0],
					addrType = item.isRoadAddress ? '[도로명 주소]' : '[지번 주소]',
					point = new naver.maps.Point(item.point.x, item.point.y);

				infoWindow.setContent([
						'<div style="padding:10px;min-width:200px;line-height:150%;">',
						'<h4 style="margin-top:5px;">검색 주소 : '+ response.result.userquery +'</h4>',//<br />
						addrType +' '+ item.address +'<br />',
						//'&nbsp&nbsp&nbsp -> '+ point.x +','+ point.y,
						'</div>'
					].join('\n'));


				map.setCenter(point);				
				infoWindow.open(map, point);
				// 추가
				$('#latlng').val(point.x +','+ point.y);
				search_doctor($('#latlng').val(), $('#address').val());
			});
		}

		function initGeocoder() {
			map.addListener('click', function(e) {
				//searchCoordinateToAddress(e.coord);
			});

			$('#address').on('keydown', function(e) {
				var keyCode = e.which;

				if (keyCode === 13) { // Enter Key
					searchAddressToCoordinate($('#address').val());
				}
			});

			$('#submit').on('click', function(e) {
				e.preventDefault();

				searchAddressToCoordinate($('#address').val());
			});

			<? if($_GET['latlng']) { ?>
			//searchAddressToCoordinate('<?=$_GET['address']?>');
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
			<? } ?>
		}

		naver.maps.onJSContentLoaded = initGeocoder;
		
		<?
		if($doctor_list) { 
		?>
		var latlngs = [
		<?
			foreach ($doctor_list as $key => $val) {
				list($vlat, $vlng) = explode(',', $val['latlng']);
		?>
			new naver.maps.LatLng(<?=$vlng?>, <?=$vlat?>),		
		<?
			}
		?>
		];
		var markerList = [];
		for (var i=0, ii=latlngs.length; i<ii; i++) {
			var	marker = new naver.maps.Marker({
					position: latlngs[i],
					map: map,				
				});
			marker.set('seq', i);
			markerList.push(marker);
			marker = null;			
		}
		<?
		}
		?>
		var doctorList = [
		<?
			foreach ($doctor_list as $key => $val) {				
		?>
			<?=$val["id"]?>,		
		<?
			}
		?>
		];		
		function getClickHandler(seq) {
			return function(e) {
				//alert(seq); 
				location.href = '/act/profile.php?id='+seq+'&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>'; // 클릭시 reservation.php?id=X 으로 보낸다.
			}
		}		
		for (var i=0, ii=markerList.length; i<ii; i++) {
			naver.maps.Event.addListener(markerList[i], 'click', getClickHandler(doctorList[i])); 
		}
		</script>	

		<br>
		<input type="hidden" id="latlng" name="latlng" value="<?=$_GET['latlng']?>">
		<!--<a href="" class="btn btn-default" role="button" onclick="search_doctor($('#latlng').val(), $('#address').val()); return false;">찾기</a>-->		
		
		<? if($doctor_list) { ?>
		
		<div style='text-align:center;margin-bottom: 10px;'>
		<a href="/act/search.php?mode=search&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">거리</a>
		<a href="/act/search.php?mode=search&order=nam&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">남</a>
		<a href="/act/search.php?mode=search&order=yeo&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">여</a>
		<a href="/act/search.php?mode=search&order=career&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">경력</a>
		<a href="/act/search.php?mode=search&order=price&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">가격</a>
		<a href="/act/search.php?mode=search&order=review&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>#result" class="btn btn-default" role="button">리뷰</a>
		</div>
		<br>
		<a name="result"></a>
		<table class="table table-hover">
		<tr>
		<th>선생님</th>
		<th>거리(km)</th>
		<th style="text-align: center;">위치</th>
		</tr>
		<? foreach($doctor_list as $key => $val) { // 클릭시 reservation.php?id=X 으로 보낸다. ?>		
		<tr onclick="document.location = '/act/profile.php?id=<?=$val['id']?>&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>';">
		<td>
		<a href="/act/profile.php?id=<?=$val['id']?>&latlng=<?=$_GET['latlng']?>&address=<?=$_GET['address']?>"><? echo mb_substr($val['name'], 0, 1).'*'.mb_substr($val['name'], -1); ?></a>
		</td>
		<td><?=$val['distance']?></td>
		<td><?=$val['address']?></td>
		</tr>		
		<? } ?>
		</table>
		<? } ?>
		
		<?// var_export($dist_array); ?>
		
		<p><br>

		<script>
		function search_doctor(latlng, address) {
			if(latlng) {
				location.href = '/act/search.php?mode=search&latlng='+latlng+'&address='+address;
			} else {
				alert('먼저 주소를 검색하세요.');
			}			
		}
		</script>
</div>
</div>
<? include_once('_foot.php'); ?>