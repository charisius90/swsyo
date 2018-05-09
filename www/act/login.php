<?
$title = "로그인";
include_once('_head.php');
?>


<link rel="stylesheet" href="https://shallwesay.co.kr/css/apms.css?ver=171013">
<div class="container">
<div class="content">


<div class="row">
	<div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
		<div class="form-box">
			<div class="form-header">
				<h2><b><i class="fa fa-smile-o"></i> 로그인 <a href="#" onclick="$('.form-body').toggle();" title="로그인 폼을 토글합니다"><i class="fas fa-search-plus"></i></a></b></h2>
			</div>
			<div class="form-body" style="display: none;">
			    <form class="form" role="form" name="flogin" action="https://shallwesay.co.kr/bbs/login_check.php" onsubmit="return flogin_submit(this);" method="post">
			    <input type="hidden" name="url" value='<?=$_GET['url']?>'>
					<div class="form-group has-feedback">
						<label for="login_id"><b>아이디</b><strong class="sound_only"> 필수</strong></label>
						<input type="text" name="mb_id" id="login_id" required class="form-control input-sm" size="20" maxLength="20">
						<span class="fa fa-user form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
				        <label for="login_pw"><b>비밀번호</b><strong class="sound_only"> 필수</strong></label>
				        <input type="password" name="mb_password" id="login_pw" required class="form-control input-sm" size="20" maxLength="20">
						<span class="fa fa-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<label class="remember-me">
								<input type="checkbox" name="auto_login" id="login_auto_login"> 자동로그인
							</label>
						</div>
						<div class="col-xs-6">
							<button type="submit" class="btn btn-color pull-right">Sign In</button>
						</div>
					</div>
				</form>
			</div>
			<div style="text-align:center;">
			<div class="div-title-underline-thin en">
				<b>SNS LOGIN</b>
			</div>
			</div>
			<div style="text-align:center;">
				<a href="javascript:login_oauth('naver','460','517');"><img src="https://shallwesay.co.kr/plugin/login-oauth/img/naver.png" alt="Sign in with naver"></a>
				<a href="javascript:login_oauth('google','460','640');"><img src="https://shallwesay.co.kr/plugin/login-oauth/img/google.png" alt="Sign in with google"></a>
				<a href="javascript:login_oauth('kakao','480','680');"><img src="https://shallwesay.co.kr/plugin/login-oauth/img/kakao.png" alt="Sign in with kakao"></a>		
			</div>
			<div class="h20"></div>
			<div class="form-footer">
				<p class="text-center">
					<!--<a href="./register.php"><i class="fa fa-sign-in"></i> 회원가입</a>-->
					<a href="https://shallwesay.co.kr/bbs/password_lost.php" target="_blank" id="login_password_lost"><i class="fa fa-search"></i> 정보찾기</a>
				</p>
			</div>
		</div>
	</div>
</div>

<div class="text-center" style="margin:30px 0px;">
	<a href="https://shallwesay.co.kr/" class="btn btn-black btn-sm" role="button">메인으로</a>
</div>
<script src="https://shallwesay.co.kr/js/common.js?ver=171013"></script>
<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f) {
    return true;
}
</script>

<script>
function login_oauth(type,ww,wh) {
var url = "https://shallwesay.co.kr/plugin/login-oauth/login_with_" + type + ".php";
var opt = "width=" + ww + ",height=" + wh + ",left=0,top=0,scrollbars=1,toolbars=no,resizable=yes";
popup_window(url,type,opt);
}
</script>

</div>
</div>



<? include_once('_foot.php'); ?>