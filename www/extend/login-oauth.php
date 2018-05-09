<?php
if (!defined('_GNUBOARD_')) exit;

/*
 * APMS Login OAuth Plugin v 1.6 for G5/YC5/APMS
 * Released : 2014/12/31 - http://amina.co.kr
 * Updated  : 2016/06/15 - http://amina.co.kr
 * thanks
 * 도사님 - http://cafe.naver.com/themeplace/106
 * 우성군님 - http://www.wsgvet.com/bbs/board.php?bo_table=home&wr_id=292
 * 제대로 적용하실려면 아래글 참고해서 DB의 필드수정도 필요합니다.
 * http://amina.co.kr/bbs/board.php?bo_table=tip&wr_id=2113
 */

function get_login_oauth($type, $img='') {

	if(!$type) return;
	
	if(!defined('APMS_SNS_LOGIN_OAUTH')) {
		define('APMS_SNS_LOGIN_OAUTH', true);

		global $login_url, $urlencode;

		$return_url = (isset($login_url) && $login_url) ? $login_url : $urlencode;

		echo '<script>'.PHP_EOL;
		echo 'function login_oauth(type,ww,wh) {'.PHP_EOL;
		echo 'var url = "'.G5_PLUGIN_URL.'/login-oauth/login_with_" + type + ".php";'.PHP_EOL;
		echo 'var opt = "width=" + ww + ",height=" + wh + ",left=0,top=0,scrollbars=1,toolbars=no,resizable=yes";'.PHP_EOL;
		echo 'popup_window(url,type,opt);'.PHP_EOL;
		echo '}'.PHP_EOL;
		echo '</script>'.PHP_EOL;
		echo '<input type="hidden" name="slr_url" value="'.$return_url.'">'.PHP_EOL;
	}

	// Size
	switch($type) {
		case 'facebook'	: $ww = 1024; $wh = 640; break;
		case 'twitter'	: $ww = 600; $wh = 600; break;
		case 'google'	: $ww = 460; $wh = 640; break;
		case 'naver'	: $ww = 460; $wh = 517; break;
		case 'kakao'	: $ww = 480; $wh = 680;	break;
		default			: $ww = 600; $wh = 600; break;
	}

	$str = "login_oauth('".$type."','".$ww."','".$wh."');";
	if($img) {
		if ($img == 'none') { // Link
		    return $str; 
		} else {
			$str = '<a href="javascript:'.$str.'"><img src="'.$img.'" alt="Sign in with '.$type.'"></a>';
		}
	} else {
		$img = G5_PLUGIN_URL.'/login-oauth/img/'.$type.'.png';
		$str = '<a href="javascript:'.$str.'"><img src="'.$img.'" alt="Sign in with '.$type.'"></a>';
	}

    return $str;
}

?>