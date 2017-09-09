<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
$nav = explode(" ",$_SERVER['HTTP_USER_AGENT']);
$navegador = $nav[0];
$uip = GeraIP();
$token = LimpaTexto($_GET["token"]);
$usuario_id = GeraID($token);
LimpaDados();
VerificaLogin();
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>Você está <b>Banido</b>!!<br/>";
$banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='".$usuario_id."' AND penalty='1'"));
$banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='".$usuario_id."'"));
$reinicio = $banto[0]- time();
$rmsg = GeraTextoTempo($reinicio);
echo "Tempo para termino do Ban: $rmsg<br/>";
echo "Motivo: $banres[0]";
echo "</div>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>