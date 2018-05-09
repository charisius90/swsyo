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
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<?php echo '<link rel="stylesheet" type="text/css" href="/style/iv_style.css?' . filemtime('/ssw1990/www/style/iv_style.css') . '" />'; ?> 
<title><? echo $title; ?></title>
<link rel="icon" type="image/png" sizes="32x32" href="/img/png/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/img/png/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/png/favicon-16x16.png">
</head>
<body>

<script>
$(function(){
	var menuBtn=$(".head_menu_btn");
	var menuBox=$(".head_menu_box");

	menuBtn.click(function() {
		menuBox.slideToggle();
	});
	
	menu_pattern = [];
	menu_pattern[0] = /^http[s]*:\/\/(www.shallwesay.co.kr|shallwesay.co.kr)[/]*$/i;
	menu_pattern[1] = /act\/my.php/i;	
	menu_pattern[2] = /act\/search.php/i;
	menu_pattern[3] = /bbs\/board.php\?bo_table=notice/i;
	menu_pattern[4] = /bbs\/board.php\?bo_table=qna/i;
	menu_pattern[5] = /bbs\/board.php\?bo_table=bbs/i;
	menu_pattern[6] = /bbs\/board.php\?bo_table=column/i;

	for(var i=0,len=menu_pattern.length;i<len;i++) {

		if(menu_pattern[i].test(document.location.href) === true) {
			$('.head_menu_unit').eq(i).addClass('on_target');
			break;
		}
	}
});
</script>

<header>
	<div class="header_box">
		<div class="header_bar">
			<div class="col-xs-2">
				<div class="head_menu_btn"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></div>
			</div>
			<div class="col-xs-8">
				<div class="header_logo"><a href="/"><img src="/img/logo01.png"></a><!--<span class="header_txt">beta service</span>--></div>
			</div>
			<div class="col-xs-2"></div>
		</div>

		<div class="head_menu_box">
			<a href="/"><div class="head_menu_unit">Home</div></a>
			<a href="/act/my.php"><div class="head_menu_unit">My Page</div></a>			
			<a href="/act/search.php"><div class="head_menu_unit">선생님 찾기</div></a>
			
			<?// 게시판 추가 ?>
			
			<a href="/bbs/board.php?bo_table=notice"><div class="head_menu_unit" style="border-top:1px solid #cccccc;">공지사항</div></a>
			<a href="/bbs/board.php?bo_table=qna"><div class="head_menu_unit">문의 게시판</div></a>
			<a href="/bbs/board.php?bo_table=bbs"><div class="head_menu_unit">자유 게시판</div></a>
			<a href="/bbs/board.php?bo_table=column"><div class="head_menu_unit">언어치료 칼럼</div></a>
			
			<? if(!$is_doctor) { ?>
			<a href="/act/register.php"><div class="head_menu_unit" style="border-top:1px solid #cccccc;">선생님 등록</div></a>
			<? } ?>
			<? if($is_doctor) { ?>
			<a href="/act/doctor.php"><div class="head_menu_unit" style="border-top:1px solid #cccccc;">선생님 페이지</div></a>
			<? } ?>
			<? if($is_admin) { ?>
			<a href="/act/list.php"><div class="head_menu_unit" style="border-top:1px solid #cccccc;">관리자 페이지</div></a>
			<? } ?>
		</div>
	</div>
</header>