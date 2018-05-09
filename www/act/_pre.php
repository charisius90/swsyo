<?
include_once('./_common.php');
include_once('_func.php');

$db_conn = @mysqli_connect("localhost", "ssw1990", "dhrhrkWkd", "ssw1990"); 
if (!$db_conn) { $error = mysqli_connect_error(); $errno = mysqli_connect_errno(); print "$errno: $error\n"; exit(); } 
//mysqli_close($db_conn);

if($is_member) {
	$query = "SELECT * FROM doctor_list where mb_id = '{$member[mb_id]}'"; 
	$result = mysqli_query($db_conn, $query); 
	$is_doctor = mysqli_fetch_assoc($result);
}
?>