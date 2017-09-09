<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
VerificaBanIP();
$nav = explode(" ",$_SERVER['HTTP_USER_AGENT']);
$navegador = $nav[0];
$uip = GeraIP();
$menu = LimpaTexto($_GET["menu"]);
$token = LimpaTexto($_GET["token"]);
$pagina = LimpaTexto($_GET["pagina"]);
$usuario = LimpaTexto($_GET["usuario"]);
$usuario_id = GeraID($token);
LimpaDados();
VerificaLogin();
VerificaBanNick();
if($menu=="inicio")
{
AdicionarOnline(GeraID($token),"ok","");
echo "<p align=\"center\">";
echo "<b>Reportadas</b>";
echo "</p>";

echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}else if($menu=="guessgm")
{
AdicionarOnline(GeraID($token),"Jogando Descubra o Numero","");
echo "<p align=\"center\">";
$gid = $_POST["gid"];
$un = $_POST["un"];
if($gid=="")
{
mysql_query("DELETE FROM fun_games WHERE uid='".$usuario_id."'");
mt_srand((double)microtime()*1000000);
$rn = mt_rand(1,100);
mysql_query("INSERT INTO fun_games SET uid='".$usuario_id."', gvar1='8', gvar2='".$rn."'");
$tries = 8;
$gameid = mysql_fetch_array(mysql_query("SELECT id FROM fun_games WHERE uid='".$usuario_id."'"));
$gid=$gameid[0];
}else{
$ginfo = mysql_fetch_array(mysql_query("SELECT gvar1,gvar2 FROM fun_games WHERE id='".$gid."' AND uid='".$usuario_id."'"));
$tries = $ginfo[0]-1;
mysql_query("UPDATE fun_games SET gvar1='".$tries."' WHERE id='".$gid."'");
$rn = $ginfo[1];
}
if ($tries>0)
{
$gmsg = "<small>Descubra o Numero que esta entre 1 a 100</small><br/><br/>";
echo $gmsg;
$tries = $tries-1;
$gpl = $tries*3;
echo "Tentativas:$tries, Pontos:$gpl<br/><br/>";
if ($un==$rn){
$gpl = $gpl+3;
$ugpl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$usuario_id."'"));
$ugpl = $gpl + $ugpl[0];
mysql_query("UPDATE fun_users SET gplus='".$ugpl."' WHERE id='".$usuario_id."'");
echo "<small>Parabéns Você acertou o número erá $rn, $gpl pontos de jogos foram adicionados ao seu perfil, <a href=\"games.php?menu=guessgm&amp;token=$token\">Jogar Novamente </a></small><br/><br/>";
}else{
if($un <$rn)
{
echo "Tente número maior do que $un !<br/><br/>";
}else{
echo "Tente número menor do que $un !<br/><br/>";
}
echo "<form action=\"games.php?menu=guessgm&amp;token=$token\" method=\"post\">";
echo "Número: <input type=\"text\" name=\"un\" format=\"*N\" size=\"3\" value=\"$un\"/>";
echo "<input type=\"submit\" value=\"Jogar\"/>";
echo "<input type=\"hidden\" name=\"gid\" value=\"$gid\"/>";
echo "</form";
echo "<br/>";
}
}else{
$gmsg = "<small>Ops! Você Perdeu, <a href=\"games.php?menu=guessgm&amp;token=$token\">Jogar Novamente </a></small><br/><br/>";
echo $gmsg;
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p></card>";
}else if($menu == "hangman")
{
AdicionarOnline(GeraID($token),"Jogando Forca","");
echo "<card id=\"inicio\" title=\"Forca\">";
echo "<p align=\"center\">";
$gid = $_GET["gid"];
$gchr = $_GET["gchr"];
$alpha = "abcdefghijklmnopqrstuvwxyz";
if($gid=="")
{
mysql_query("DELETE FROM fun_games WHERE uid='".$usuario_id."'");
$hmid = mysql_fetch_array(mysql_query("SELECT id FROM fun_hangman ORDER BY RAND() LIMIT 1"));
mysql_query("INSERT INTO fun_games SET uid='".$usuario_id."', gvar1='abcdefghijklmnopqrstuvwxyz', gvar2='', gvar3='', gvar4='".$hmid[0]."'");
$gameid = mysql_fetch_array(mysql_query("SELECT id FROM fun_games WHERE uid='".$usuario_id."'"));
$gid=$gameid[0];
}else{
$ginfo = mysql_fetch_array(mysql_query("SELECT gvar1,gvar2, gvar3, gvar4 FROM fun_games WHERE id='".$gid."' AND uid='".$usuario_id."'"));
}
if(strlen($ginfo[1])<6)
{
$txg = mysql_fetch_array(mysql_query("SELECT text, dscr FROM fun_hangman WHERE id='".$ginfo[3]."'"));
$tofn = getchars($txg[0]);
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}else{
AdicionarOnline(GeraID($token),"erro,","");
echo "<p align=\"center\">";
echo "Eu não sei como você entrou aqui, mas não há nada para mostrar<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
function getchars($text)
{
$text = strtolower($text);
$abc = "abcdefghijklmnopqrstuvwxyz";
$rts = "";
for ($i=0; $i<strlen($text); $i++)
{
$onc = substr($text,$i, 1);
$pos = strpos($abc,$onc);
if($pos===false)
{
//meh
}else{
$pos = strpos($rts, $onc);
if($pos===false)
{
$rts .= $onc;
}
}
}
return $rts;
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>