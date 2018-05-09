<?
if($bo_table == 'notice' && $w != 'u') {
	include_once('../fcm/send_fcm.php');
	$result = sql_query("Select Token From users");
	while ($row = sql_fetch_array($result)) {
		if($row['Token']) {
			$tokens[] = $row['Token'];
		}
	}
	$rt = send_fcm($wr_subject, $tokens);
}
?>