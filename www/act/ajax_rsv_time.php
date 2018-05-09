<?
include_once('_pre.php');
$id = $_POST['doctor'];
$rsv_date = $_POST['rsv_date'];

$query = "SELECT * FROM doctor_config where doctor = '{$id}'"; 
$result = mysqli_query($db_conn, $query); 
if ( $result ) { 		
	$doctor_config = mysqli_fetch_assoc($result);
}

$query = "SELECT * FROM reservation where doctor_id = '{$id}' and status = '확인' and rsv_date = '{$rsv_date}'";
$result = mysqli_query($db_conn, $query); 
if ( $result ) { 		
	while ($row = mysqli_fetch_assoc($result)) { 			
		$reservation_info[] = $row;
	}	
}

list($timeas, $timeae, $timeps, $timepe) = explode(',', $doctor_config['worktime']);	
$return_msg = '';
$checked_time = array();
if($reservation_info) { // 예약체크		
	foreach($reservation_info as $val) {
		for($i = 0; $i <= $doctor_config['timeunit']; $i++) {				
			$apm = substr($val['rsv_time'], 0, 2);
			$num = substr($val['rsv_time'], 2);
			$num = $num + $i;
			if($num > 12) {
				if($apm == 'am') {
					$apm = "pm"; 
				} else {
					$apm = "am"; 
				}
				$num = $num - 12;
			}		
			$checked_time[] = $apm.$num;
		}
	}		
}/* else {	
	for($i = $timeas; $i <= $timeae; $i++) {	
		$return_msg .= '<option value="am'.$i.'">오전 '.$i.'시</option>';	
	}	
	for($i = $timeps; $i <= $timepe; $i++) {	
		$return_msg .= '<option value="pm'.$i.'">오후 '.$i.'시</option>';
	}	
}*/

for($i = $timeas; $i <= $timeae; $i++) {			
	if(!in_array('am'.$i, $checked_time)) {
		$return_msg .= '<option value="am'.$i.'">오전 '.$i.'시</option>';	
	}		
}	
for($i = $timeps; $i <= $timepe; $i++) {	
	if(!in_array('pm'.$i, $checked_time)) {
		$return_msg .= '<option value="pm'.$i.'">오후 '.$i.'시</option>';
	}
}

echo $return_msg; //var_export($checked_time); //
?>