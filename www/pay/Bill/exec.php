<?

exec("/ssw1990/www/pay/phpexec/test.phpexec -h", $out2, $err2);
echo '<pre>';
var_export( $out2 );
echo '</pre>';
echo '<pre>';
var_export( $err2 );
echo '</pre>';

exec("/ssw1990/www/pay/phpexec/INIreqrealbill.phpexec", $out3, $err3);
echo '<pre>';
var_export( $out3 );
echo '</pre>';
echo '<pre>';
var_export( $err3 );
echo '</pre>';

?>