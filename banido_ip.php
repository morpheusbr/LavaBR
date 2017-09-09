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
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>Seu Ip Esta Bloquiado em nosso site!!!<br/>";
echo "Alguns usuarios de nosso site possiu protenção contra ban ip, tente logar novamente, se esta mensagem voltar a aparecer você não  poderá entrar até que o tempo de ban termine<br/><br/>";
$banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty='2' AND ipadd='".$uip."' AND browserm='".$navegador."' LIMIT 1 "));
$reinicio =  $banto[0] - time();
$rmsg = GeraTextoTempo($reinicio);
echo " IP: $rmsg<br/>";
echo "<form action=\"logar.php\" enctype=\"multipart/form-data\" method=\"GET\">";
echo "Login:<br/> <input name=\"usuario\" size=\"8\" maxlength=\"30\"/><br/>";
echo "Senha:<br/> <input type=\"password\" name=\"senha\" size=\"8\" maxlength=\"30\"/><br/>";
echo "<input type=\"submit\" value=\"Entrar no Site\"/>";
echo " <br/><a href=\"cadastro.php\">Registre-se</a><br/>";
echo "</form>"; 
echo "</div>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>