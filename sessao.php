<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
LimpaDados();
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>Você não esta mais logado ou devido a falta de movimentação no site sua sessão expirou!!<br/><br/><a href=\"index.php\">Logar Novamente</a></div>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>