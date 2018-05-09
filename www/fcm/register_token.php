<?
include_once('../common.php');
$token = $_POST["Token"] or $_GET["Token"];
$_SESSION["dtkn"] = $token;
$query = "INSERT INTO users(Token) Values ('$token') ON DUPLICATE KEY UPDATE Token = '$token' ";
sql_query($query);
?>