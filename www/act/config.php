<?
$title = "예약 설정";
include_once('_head.php');

$mode = $_POST['mode'] or $mode = $_GET['mode'] or $mode = 'form';
$id = $_GET['id'] or $id = $_POST['id'] or $id = '';
if($id) {
	$sql = "select * from doctor_config where doctor = '{$id}'";
 	$result = mysqli_query($db_conn, $sql);
	$config  = mysqli_fetch_array($result);
} else {
	// doctor_id 가 있어야만 페이지 접근 가능 
	// 우선적으로 로그인은 생략하고 직접 doctor_id 로 접근하자. id=13 
	die("필수인자나 권한이 부족합니다.");
}

$workday = implode(',', $_POST['workday']);
$worktime = $_POST['timeas'].",".$_POST['timeae'].",".$_POST['timeps'].",".$_POST['timepe'];

if($mode == 'register') {
	
	$sql = "insert into doctor_config (doctor, workday, worktime, timeunit, price, comment) values ('{$id}', '{$workday}', '{$worktime}', '{$_POST[timeunit]}', '{$_POST[price]}', '{$_POST[comment]}')";
	mysqli_query($db_conn, $sql);	
	
	$sql = "update doctor_list set price = '{$_POST[price]}' where id = '{$id}'";
	mysqli_query($db_conn, $sql);	
	
	echo "<script>alert('예약 설정이 등록되었습니다');document.location.replace('/act/doctor.php');</script>";	
}

if($mode == 'update') {
	$sql = "update doctor_config set workday = '{$workday}', worktime = '{$worktime}', timeunit = '{$_POST[timeunit]}', price = '{$_POST[price]}', comment = '{$_POST[comment]}' where doctor = '{$id}'";
	mysqli_query($db_conn, $sql);	
	
	$sql = "update doctor_list set price = '{$_POST[price]}' where id = '{$id}'";
	mysqli_query($db_conn, $sql);	
	
	echo "<script>alert('예약 설정이 변경되었습니다');document.location.replace('/act/doctor.php');</script>";
}

/*
MariaDB [ssw1990]> desc doctor_config;
+----------+------------------+------+-----+---------+----------------+
| Field    | Type             | Null | Key | Default | Extra          |
+----------+------------------+------+-----+---------+----------------+
| id       | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| doctor   | int(11) unsigned | NO   |     | NULL    |                |
| workday  | varchar(255)     | NO   |     | NULL    |                |
| worktime | varchar(255)     | NO   |     | NULL    |                |
| comment  | text             | NO   |     | NULL    |                |
| timeunit | int(2)           | NO   |     | NULL    |                |
+----------+------------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)

0. 선생님 id 연관키 - 로그인 - 
1. 월화수목금토일 근무요일 필요?
2. 시 ~ 시 근무시간
3. 대략적인 진료시간
4. 안내문구
*/
?>
<div class="container">

<div class="page_name">
	<h3><?=$title?></h3>
</div>

<div class="content">

<form method="post">
<input type="hidden" name="mode" value="<? if($config['id']) { ?>update<? } else { ?>register<? } ?>">
<input type="hidden" name="id" value="<?=$_GET['id']?>">

<table class="table">
<tr>
	<td>요일</td>
	<td>
	<? $config_workday = explode(',', $config['workday']); ?>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="1"<? if(in_array('1', $config_workday)){echo " checked";} ?>>월</label>
    <label class="checkbox-inline"><input type="checkbox" name="workday[]" value="2"<? if(in_array('2', $config_workday)){echo " checked";} ?>>화</label>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="3"<? if(in_array('3', $config_workday)){echo " checked";} ?>>수</label>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="4"<? if(in_array('4', $config_workday)){echo " checked";} ?>>목</label>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="5"<? if(in_array('5', $config_workday)){echo " checked";} ?>>금</label>
	<br>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="6"<? if(in_array('6', $config_workday)){echo " checked";} ?>>토</label>
	<label class="checkbox-inline"><input type="checkbox" name="workday[]" value="7"<? if(in_array('7', $config_workday)){echo " checked";} ?>>일</label>
	</td>
</tr>
<tr>
	<td>시간대</td>
	<td>
	<? list($timeas, $timeae, $timeps, $timepe) = explode(',', $config['worktime']); ?>
	오전 <input type="text" name="timeas" value="<?=$timeas?>" placeholder="9" size="3" required> ~ <input type="text" name="timeae" value="<?=$timeae?>" placeholder="12" size="3" required>
	<br><br>
	오후 <input type="text" name="timeps" value="<?=$timeps?>" placeholder="1" size="3" required> ~ <input type="text" name="timepe" value="<?=$timepe?>" placeholder="6"  size="3" required>
	</td>
</tr>
<tr>
	<td>치료시간</td>
	<td>
	<input type="text" name="timeunit" value="<?=$config['timeunit']?>" placeholder="3" size="3" required>
	</td>
</tr>
<tr>
	<td>치료비용</td>
	<td>
	<input type="text" name="price" value="<?=$config['price']?>" required>원
	</td>
</tr>
<tr>
	<td>안내문구</td>
	<td>
	<textarea class="form-control" rows="5" name="comment"><?=$config['comment']?></textarea>
	</td>
</tr>
</table>

<div style='text-align:center;'>
	<input type="submit" class="btn btn-primary" value="등록">
	<!--<a href="/webtoon/manage.php" class="btn btn-primary" role="button">나가기</a>-->
</div>

</form>

</div>
</div>
<? include_once('_foot.php'); ?>