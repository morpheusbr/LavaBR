<?php
/**
 * @author FABIO VIEIRA
 * @copyright 2015
 */
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
$uip = GeraIP();
$menu = LimpaTexto($_GET["menu"]);
$token = LimpaTexto($_GET["token"]);
$pagina = LimpaTexto($_GET["pagina"]);
$usuario =LimpaTexto($_GET["usuario"]);
$usuario_id = GeraID($token);
LimpaDados();
VerificaLogin();
VerificaBanNick();
VerificaBanIP();
SalvaInfoLog($token);
switch (LimpaTexto($_GET["menu"])):
case 'inicio':
AdicionarOnline(GeraID($token),"Vendo Categoria do Forum","forum.php?menu=".LimpaTexto($_GET["menu"]));
$fcats = $db->query("SELECT id, name FROM fun_fcats ORDER BY position, id");
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";
while ($fcat = $fcats->fetch(PDO::FETCH_ASSOC)) {
$catlink = "<a href=\"sistema.php?menu=categoria_f&amp;token={$token}&amp;cid={$fcat['id']}\">{$iml}{$fcat['name']}</a>";
echo "<br/>{$catlink}";
$forums = $db->query("SELECT id, name FROM fun_forums WHERE cid='".$fcat['id']."' AND clubid='0' ORDER BY position, id, name");
if(EstiloForum()==0)
{
echo "<br/>";
while ($forum = $forums->fetch(PDO::FETCH_ASSOC)) {
if(AcessoAoForum(GeraID($token),$forum['id']))
{
echo "<a href=\"sistema.php?menu=ver_f&amp;token={$token}&amp;fid={$forum['id']}\">{$forum[name]}</a>, ";
}
}
echo "";
}else if(EstiloForum()==20)
{
echo "<form action=\"forum.php\" method=\"get\">";
echo "<br/>Foruns: <select name=\"fid\">";
while ($forum = $forums->fetch(PDO::FETCH_ASSOC)) {
if(AcessoAoForum(GeraID($token),$forum[0]))
{
echo "<option value=\"{$forum['id']}\">{$forum['id']}(".ContaDadosTabela('fun_topics','fid',$forum['id']).")</option>";
}
}
echo "</select>";
echo "<input type=\"submit\" value=\"IR\"/>";
echo "<input type=\"hidden\" name=\"menu\" value=\"ver_f\"/>";
echo "<input type=\"hidden\" name=\"token\" value=\"{$token}\"/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"{$fid}\"/>";
echo "</form>";
}
}
break;
endswitch;
echo '<center><a href="inicio.php?token='.$token.'"><img src="images/home.gif" alt="*"/>Pagina Inicial</a></center>';
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>