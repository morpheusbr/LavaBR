<?php
header('content-type: image/jpeg');
VerificaConexao();
include($_SERVER['DOCUMENT_ROOT']."/config/config.php");
BancoDeDados();
$msg = $_GET["msg"];
$cid = $_GET["cid"];
$iid = $cid +0;
$cinfo=mysql_fetch_array(mysql_query("SELECT * FROM ibwf_cards WHERE id='".$iid."'"));
list ($r,$g,$b)  = explode(',',$cinfo[6]);
settype($r,"integer");
settype($g,"integer");
settype($b,"integer");
$bgpic = @imagecreatefromjpeg("cartao/$cid.jpg");
$bg = imagecolorallocate ($bgpic, 0, 0, 0);
$textcolor = imagecolorallocate($bgpic,$r,$g,$b);
if(($cinfo[5]==0)&&($cinfo[4]==0))
{
imagestring($bgpic,$cinfo[1],$cinfo[2],$cinfo[3],"$msg",$textcolor);
}else{
$words = explode(' ',$msg);
for($i=0;$i< count($words);$i++)
{
$xl = $cinfo[3]+($cinfo[4]*$i)+$cinfo[2];
$yl = $cinfo[4]+($cinfo[5]*$i)+$cinfo[3];
imagestring($bgpic,$cinfo[1], $xl,$yl,$words[$i],$textcolor);
}
}
imagejpeg($bgpic);
imagedestroy($bgpic);
?>