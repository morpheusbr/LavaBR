<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
LimpaDados();
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>Ops! A Página solicitada não existe ou foi removida</div>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>