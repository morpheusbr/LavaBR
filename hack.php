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
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>Ta de Brincadeira né lamer essa não cola!!! :)</div>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>