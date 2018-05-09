<?// print_r($_SERVER); ?>
<style>
.footer_box{
	<? if( $_SERVER[SCRIPT_NAME] == '/index.html') { ?>	
	position:absolute; bottom:0;
	<? } ?>
    width:100%;
    padding:20px;   
	text-align:center;	
	background:#333333;
	color:white;
}
.textlink, .textlink:hover, .textlink:active, .textlink:visited {
	color:white;	
}
.textfoot {
	margin-top:10px;
}
</style>

<? if(!is_mobile()) { ?>
<div class="footer_box">	
	<a href="/policy.html" class="textlink">이용약관</a> | <a href="/private.html" class="textlink">개인정보 보호정책</a>		
	<div class="textfoot">에스더블유에스요(SWSYO) 사업자등록번호: 854-06-00772 / 대표: 선승원 / 주소: 서울시 서초구 방배로 63 1-302 / 연락처: 010-9996-0756 (02-585-0756)</div>
</div>	
<? } ?>

</body>
</html>