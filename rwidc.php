<?php
header("Content-type: image/jpeg");
$id = $_GET["id"];
VerificaConexao();
include($_SERVER['DOCUMENT_ROOT']."/config/config.php");
BancoDeDados();
$uinfo = mysql_fetch_array(mysql_query("SELECT plusses, posts, name, avatar FROM fun_users WHERE id='".$id."'"));
$bgpic = imagecreatefromjpeg("images/rwidc.jpg");
$textcolor = imagecolorallocate($bgpic,0x00,0,0x99);
$infcolor = imagecolorallocate($bgpic,0,0,0);
$stscolor = imagecolorallocate($bgpic,0x00,0x55,0x00);
imagestring($bgpic,2,10,1,$uinfo[2],$textcolor);
imagestring($bgpic,1,82,15,$id,$infcolor);
imagestring($bgpic,1,82,24,$uinfo[1],$infcolor);
imagestring($bgpic,1,82,33,$uinfo[0],$infcolor);
imagestring($bgpic,1,53,45,GeraStatus($id),$stscolor);
$avl = $uinfo[3];
if(trim($avl!=""))
{
$avl = strtolower($avl);
if(substr_count($avl,"rwidc.php")==0)
$imgi = getimagesize($avl);
if($imgi[0]>0)
{
if($imgi[2]==1)
{
$av = imagecreatefromgif($avl);
imagecopyresized($bgpic, $av,10,16,0,0,40,40,$imgi[0], $imgi[1]);
}else if($imgi[2]==2)
{
$av = imagecreatefromjpeg($avl);
imagecopyresized($bgpic, $av,10,16,0,0,40,40,$imgi[0], $imgi[1]);
}
}
}
imagejpeg($bgpic,"",100);
imagedestroy($bgpic);
?>