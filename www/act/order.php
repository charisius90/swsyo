<?
$title = "이용권 구입";
include_once('_head.php');

$mode = $_POST['mode'];

if($is_member) { 

	if($mode=="register") {
		
		if(!$_POST['subject']) {
			alert("상품내용이 없습니다");
		}
		
		if($_POST['subject']=="회원 이용권") {
			$length = "";
		} else if($_POST['subject']=="선생님 1개월 이용권") {
			$length = "1m";
		} else if($_POST['subject']=="선생님 2개월 이용권") {
			$length = "2m";
		} else if($_POST['subject']=="선생님 3개월 이용권") {
			$length = "3m";
		} else if($_POST['subject']=="선생님 4개월 이용권") {
			$length = "4m";
		} else if($_POST['subject']=="선생님 5개월 이용권") {
			$length = "5m";
		} else if($_POST['subject']=="선생님 6개월 이용권") {
			$length = "6m";
		} else if($_POST['subject']=="선생님 7개월 이용권") {
			$length = "7m";
		} else if($_POST['subject']=="선생님 8개월 이용권") {
			$length = "8m";
		} else if($_POST['subject']=="선생님 9개월 이용권") {
			$length = "9m";
		} else if($_POST['subject']=="선생님 10개월 이용권") {
			$length = "10m";
		} else if($_POST['subject']=="선생님 11개월 이용권") {
			$length = "11m";
		} else if($_POST['subject']=="선생님 1년 이용권") {
			$length = "1y";
		}		

		$query = "select * from ticket where mb_id='{$member['mb_id']}' order by id desc limit 1";
		$result = mysqli_query($db_conn, $query);
		$row = mysqli_fetch_assoc($result);

		if($row) {

			$expiredtime = strtotime($row['expired']); 
			$nowtime = time();			

			if($nowtime > $expiredtime && $expiredtime > 0) { // expired 가 있고 현재시점이 지났다면
			
				$query = "insert into ticket (subject, mb_id, length) values ('{$_POST['subject']}','{$member['mb_id']}','{$length}')";
				$result = mysqli_query($db_conn, $query); 

				$inserted_id = mysqli_insert_id($db_conn);

				if ($inserted_id) {
					alert("구입하였습니다", "/");
				}

			} else {
				alert("이미 구입하신 이용권이 있습니다", "/");
			} 
		
		}else{

			$query = "insert into ticket (subject, mb_id, length) values ('{$_POST['subject']}','{$member['mb_id']}','{$length}')";
			$result = mysqli_query($db_conn, $query); 

			$inserted_id = mysqli_insert_id($db_conn);

			if ($inserted_id) {
				alert("구입하였습니다", "/");
			}
		}		
 
	}

} else {
	alert("로그인 정보가 필요한 페이지입니다", "/act/login.php?url=/act/order.php");	
}
?>
<div class="container">
<div class="content">

<style>
.row{
/* padding:0 15px; */
}
.ticket_unit> label{
border:1px solid #eee;
border-radius:5px;
background:#fff;
padding:15px;
width:100%;
}
.ticket_unit> label>div{
margin-left:15px;
}
</style>

<div class="page_name" style='margin-bottom:30px;'>
	<h3>말해요 이용권 구입</h3>
</div>

<h4>회원 이용권</h4><br>

* 회원 이용권 구입시 [선생님 예약] 이용이 가능합니다 <br><br>

<form method="post" action="/pay/INIStdPay/INIStdPayRequest.php" enctype="multipart/form-data"><!--form method="post" enctype="multipart/form-data"-->
	<input type="hidden" name="mode" value="register">
	<?
	// 결제모듈에 넘기기 위해 필요한 데이터 mb_id / 상품명 / 가격 / 이름 / 연락처 / 이메일 추가	
	// 이후 상품선택체크
	?>
	<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
	<input type="hidden" name="mb_name" value="<?=$member['mb_name']?>">
	<input type="hidden" name="mb_tel" value="<?=$member['mb_tel']?>">
	<input type="hidden" name="mb_email" value="<?=$member['mb_email']?>">
	
	<div class="row">
		<div class="ticket_unit col-xs-12" style='margin-bottom:10px;'>
			<label class="radio-inline" style="border: 1px solid #999999;">			
			  <div>
			  <input type="radio" name="subject" id="" value="회원 1개월 이용권|9900" required> <span class="">회원 1개월 이용권</span> <span class="">(9,900원)</span> 
			  <!--input type="radio" name="subject" id="" value="회원 이용권"> <span class="">회원 이용권</span> <span class="">(매달 9,900원)</span--> 
			  </div>			  
			</label>			
		</div>		
	</div>
	<div class="col-xs-12" style='text-align:center;'>
		<input type="submit" class="btn btn-primary" value="구입">
	</div>
</form>
<br><br>

<? if($is_admin) { ?>
<br>

<form method="post" action="/pay/INIStdPay/INIStdPayBill.php" enctype="multipart/form-data"><!--form method="post" enctype="multipart/form-data"-->
	<input type="hidden" name="mode" value="register">	
	<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
	<input type="hidden" name="mb_name" value="<?=$member['mb_name']?>">
	<input type="hidden" name="mb_tel" value="<?=$member['mb_tel']?>">
	<input type="hidden" name="mb_email" value="<?=$member['mb_email']?>">	
	<div class="row">
		<div class="ticket_unit col-xs-12" style='margin-bottom:10px;'>
			<label class="radio-inline" style="border: 1px solid #999999;">			
			  <div>
			  <input type="radio" name="subject" id="" value="회원 정기 이용권|9900" required> <span class="">회원 정기 이용권</span> <span class="">(매달 9,900원)</span> 			  
			  </div>			  
			</label>			
		</div>		
	</div>
	<div class="col-xs-12" style='text-align:center;'>
		<input type="submit" class="btn btn-primary" value="구입">
	</div>
</form>
<br><br>

<? } ?>

<? if($is_doctor) { ?>

<br><hr><br>
		
<h4>선생님 이용권</h4><br>

* 선생님 전용 이용권입니다. 선생님 등록후 구입해주세요.<br>
* 선생님 이용권 구입시 [선생님 찾기]로 회원들에게 검색이 되며, 예약을 받으실 수 있습니다. <br><br>

<form method="post" action="/pay/INIStdPay/INIStdPayRequest.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="register">
	<div class="row">
	
	<div class="ticket_unit col-xs-12" style='margin-bottom:10px;'>
	
		<div class="form-group">
		  <label for="sel1">이용권 기간을 선택하세요</label>
		  <select name="subject" class="form-control" id="sel1" size="6">			
			<option value="선생님 1개월 이용권|13900">1개월 (13,900원)</option>
			<!--
			<option value="선생님 2개월 이용권">2개월 (매달 13,900원)</option>
			<option value="선생님 3개월 이용권">3개월 (매달 12,600원)</option>
			<option value="선생님 4개월 이용권">4개월 (매달 12,600원)</option>
			<option value="선생님 5개월 이용권">5개월 (매달 12,600원)</option>
			<option value="선생님 6개월 이용권">6개월 (매달 11,300원)</option>
			<option value="선생님 7개월 이용권">7개월 (매달 11,300원)</option>
			<option value="선생님 8개월 이용권">8개월 (매달 11,300원)</option>
			<option value="선생님 9개월 이용권">9개월 (매달 11,300원)</option>
			<option value="선생님 10개월 이용권">10개월 (매달 11,300원)</option>
			<option value="선생님 11개월 이용권">11개월 (매달 11,300원)</option>
			<option value="선생님 1년 이용권">1년 (매달 9,900원)</option>
			-->
		  </select>    
		</div>	
	
	</div>

	</div>
	<div class="col-xs-12" style='text-align:center;'>
		<input type="submit" class="btn btn-primary" value="구입">
	</div>
</form>		
<br><br>	

<? } ?>

</div>
</div>
<? include_once('_foot.php'); ?>