<?
//echo getcwd();
//echo $_GET['url'];
//echo '<br>';
//echo urlencode('/act/reservatioin.php?id=13&address=장전동');
//echo strtotime('0000-00-00 00:00:00');
/*if('2018-03-18' > date('Y-m-d',strtotime("+1 days"))) {	
	echo "이틀전";
} else {
	echo "이틀이내";
}
echo "2018-02-02 14:51:00";
echo "<br>";
echo date("Y-m-d H:i:s");
echo "<br>";
echo date("Y-m-d H:i:s", strtotime("2018-02-02 14:51:00 +3 month"));
echo "<br>";
if(date("Y-m-d H:i:s", strtotime("2018-02-02 14:51:00 +3 month")) > date("Y-m-d H:i:s")) {
	echo "아직 이용하실 수 있습니다.";
} else {
	echo "이용기한이 다 되었습니다.";
}
*/
$rsv_time = "am11";

$apm = substr($rsv_time, 0, 2);
$num = substr($rsv_time, 2);
$num = $num + 2;

if($num > 12) {
	if($apm == 'am') {
		$apm = "pm"; 
	} else {
		$apm = "am"; 
	}
	$num = $num - 12;
}		

echo $apm.$num;
?>