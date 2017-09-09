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
}else if($menu=="members")
{
AdicionarOnline(GeraID($token),"Visualizando Lista de Usuários","");
$view = $_GET["view"];
if($view=="")$view="date";
echo "<p align=\"center\">";
echo "<img src=\"images/bdy.gif\" alt=\"*\"/><br/>";
echo "<a href=\"lists.php?menu=members&amp;view=name&amp;token=$token\">Por Nome</a><br/>";
echo "<a href=\"lists.php?menu=members&amp;view=date&amp;token=$token\">Data de Cadastro</a><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
if($view=="name")
{
$sql = "SELECT id, name, regdate FROM fun_users ORDER BY name LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT id, name, regdate FROM fun_users ORDER BY regdate DESC LIMIT $limit_start, $items_per_pagina";
}
echo "<p>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$jdt = date("d-m-y", $item[2]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Cadastrou: $jdt</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=members&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=members&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////List users by IP
if($menu=="byip")
{
AdicionarOnline(GeraID($token),"Painel do Moderador","");
//////ALL LISTS SCRIPT <<
$usuario = $_GET["usuario"];
$usuarioinfo = mysql_fetch_array(mysql_query("SELECT ipadd, browserm FROM fun_users WHERE id='".$usuario."'"));
if(Moderador(GeraID($token))){
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE ipadd='".$usuarioinfo[0]."' AND browserm='".$usuarioinfo[1]."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE ipadd='".$usuarioinfo[0]."' AND browserm='".$usuarioinfo[1]."' ORDER BY name  LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets .= "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}else{
echo "<p align=\"center\">";
echo "Você não pode exibir esta lista";
echo "</p>";
}
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($menu=="topp")
{
AdicionarOnline(GeraID($token),"Top Postadores","");
echo "<p align=\"center\">";
echo "<b>Top Postadores</b><br/><small>Obrigado a todos para manter este site vivo<br/>";
$weekago = time();
$weekago -= 7*24*60*60;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid) FROM fun_posts WHERE dtpost>'".$weekago."';"));
echo "<a href=\"lists.php?menu=tpweek&amp;token=$token\">Esta semana($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;"));
echo "<a href=\"lists.php?menu=tptime&amp;token=$token\">Geral($noi[0])</a></small><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, posts FROM fun_users ORDER BY posts DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Postagens: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=topp&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=topp&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Most online daily list
else if($menu=="moto")
{
AdicionarOnline(GeraID($token),"Numero maior de Usuarios online","");
echo "<p align=\"center\">";
echo "<small>Numero Maximo de Usuarios Online nos Ultimos 10 Dias<br/>";
echo "</small>";
echo "</p>";
//////ALL LISTS SCRIPT <<
//changable sql
$sql = "SELECT ddt, dtm, ppl FROM fun_mpot ORDER BY id DESC LIMIT 10";
echo "<p>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<small>$item[0]($item[1]) Usuarios: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($menu=="tchat")
{
AdicionarOnline(GeraID($token),"Top Chat","");
echo "<p align=\"center\">";
echo "<b>Top Chat</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, chmsgs FROM fun_users ORDER BY chmsgs DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Mensagens: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=tchat&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=tchat&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input name=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input name=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($menu=="smc")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Mais Beijados","");
echo "<p align=\"center\">";
echo "<small>Usuarios que <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a> Beijou";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='smooch'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.target, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
WHERE a.uid='".$usuario."' AND a.menu='smooch'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="smd")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Beijos","");
echo "<p align=\"center\">";
echo "<small>Usuarios que Beijaram <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='smooch'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.uid, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
WHERE a.target='".$usuario."' AND a.menu='smooch'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proxima&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($menu=="kck")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Chutados","");
echo "<p align=\"center\">";
echo "<small>Usuarios Chutados por <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='kick'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.target, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
WHERE a.uid='".$usuario."' AND a.menu='kick'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="kcd")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Chutados","");
echo "<p align=\"center\">";
echo "<small>Usuarios que Chutaram <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='kick'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.uid, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
WHERE a.target='".$usuario."' AND a.menu='kick'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($menu=="pok")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Cutucos","");
echo "<p align=\"center\">";
echo "<small>Usuarios Cutucados por <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='poke'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.target, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
WHERE a.uid='".$usuario."' AND a.menu='poke'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="pkd")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Cutucos","");
echo "<p align=\"center\">";
echo "<small>Usuarios que cutucaram <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='poke'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.uid, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
WHERE a.target='".$usuario."' AND a.menu='poke'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($menu=="hgs")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Abraços","");
echo "<p align=\"center\">";
echo "<small>Usuarios Abraçados por <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='hug'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.target, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
WHERE a.uid='".$usuario."' AND a.menu='hug'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="hgd")
{
$usuario = $_GET["usuario"];
$wnick = GeraNickUsuario($usuario);
AdicionarOnline(GeraID($token),"Lista de Abraços","");
echo "<p align=\"center\">";
echo "<small>Usuarios que abraçaram <a href=\"perfil.php?usuario=$usuario&amp;token=$token\">$wnick</a>";
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='hug'")); //changable
$num_items = $noi[0];
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "
SELECT a.uid, b.name
FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
WHERE a.target='".$usuario."' AND a.menu='hug'
ORDER BY a.actime DESC LIMIT $limit_start, $items_per_pagina
;";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1)
{
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>"; 
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////requists
else if($menu=="solicitacao")
{
AdicionarOnline(GeraID($token),"Solicitações de amizade","");
echo "<p align=\"center\">";
global $config;
$usuario_id = GeraID($token);
echo "<small>Os Usuarios abaixo gostariam de fazer parte da sua lista de amigos<br/>";
$remp = $config['NUMERO_MAX_AMIGOS'] - TotalDeAmigos($usuario_id);
echo "Você tem <b>$remp</b> Diposniveis</small>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$nor = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE tid='".$usuario_id."' AND agreed='0'"));
$num_items = $nor[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid  FROM fun_buddies WHERE tid='".$usuario_id."' AND agreed='0' ORDER BY reqdt DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$rnick = GeraNickUsuario($item[0]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$rnick</a>: <a href=\"genproc.php?menu=bud&amp;usuario=$item[0]&amp;token=$token&amp;todo=add\">Aceitar</a>, <a href=\"genproc.php?menu=bud&amp;usuario=$item[0]&amp;token=$token&amp;todo=del\">Negar</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////shouts
else if($menu=="shouts")
{
AdicionarOnline(GeraID($token),"Vendo Recados","");
$usuario = $_GET["usuario"];
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
if($usuario=="")
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts"));
}else{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='".$usuario."'"));
}
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
if($usuario =="")
{
$sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts ORDER BY shtime DESC LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts  WHERE shouter='".$usuario."'ORDER BY shtime DESC LIMIT $limit_start, $items_per_pagina";
}
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$shnick = GeraNickUsuario($item[2]);
$sht =TextoGeral($item[1],$token);
$shdt = date("d m y-H:i", $item[3]);
$lnk = "<a href=\"perfil.php?usuario=$item[2]&amp;token=$token\">$shnick</a>: $sht<br/>$shdt";
if(Moderador(GeraID($token)))
{
$dlsh = "<a href=\"modproc.php?menu=delsh&amp;token=$token&amp;shid=$item[0]\">[x]</a>";
}else{
$dlsh = "";
}
echo "$lnk $dlsh<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=shouts&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=shouts&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////User Clubs
else if($menu=="ucl")
{
AdicionarOnline(GeraID($token),"Usuarios em comunidade","");
$usuario = $_GET["usuario"];
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id  FROM fun_clubs  WHERE owner='".$usuario."' ORDER BY id LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$nom = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$item[0]."' AND accepted='1'"));
$clinfo = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'"));
$lnk = "<a href=\"sistema.php?menu=gocl&amp;clid=$item[0]&amp;token=$token\">".TextoGeral($clinfo[0])."</a>($nom[0])<br/>".TextoGeral($clinfo[1])."<br/>";
echo $lnk;
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1){
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$usuarionick = GeraNickUsuario($usuario);
echo "<a href=\"perfil.php?usuario=$usuario&amp;token=$token\">Perfil de $usuarionick</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////User Clubs
else if($menu=="clm")
{
AdicionarOnline(GeraID($token),"Usuarios em Comunidade","");
$usuario = $_GET["usuario"];
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."' AND accepted='1'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT  clid  FROM fun_clubmembers  WHERE uid='".$usuario."' AND accepted='1' ORDER BY joined DESC  LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$clnm = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$item[0]."'"));
$lnk = "<a href=\"sistema.php?menu=gocl&amp;clid=$item[0]&amp;token=$token\">".TextoGeral($clnm[0])."</a><br/>";
echo $lnk;
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1){
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$usuarionick = GeraNickUsuario($usuario);
echo "<a href=\"perfil.php?usuario=$usuario&amp;token=$token\">Perfil de $usuarionick</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Popular clubs
else if($menu=="pclb")
{
AdicionarOnline(GeraID($token),"Comunidades Mais Populares","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT clid, COUNT(*) as notl FROM fun_clubmembers WHERE accepted='1' GROUP BY clid ORDER BY notl DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$clnm = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'"));
$lnk = "<a href=\"sistema.php?menu=gocl&amp;clid=$item[0]&amp;token=$token\">".TextoGeral($clnm[0])."</a>($item[1])<br/>".TextoGeral($clnm[1])."<br/>";
echo $lnk;
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1){
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=comunidade&amp;token=$token\">Comunidades</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Active clubs
else if($menu=="aclb")
{
AdicionarOnline(GeraID($token),"Comunidades com mais Atividade","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT COUNT(*) as notp, b.clubid FROM fun_topics a INNER JOIN fun_forums b ON a.fid = b.id WHERE b.clubid >'0'  GROUP BY b.clubid ORDER BY notp DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$clnm = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[1]."'"));
$lnk = "<a href=\"sistema.php?menu=gocl&amp;clid=$item[1]&amp;token=$token\">".TextoGeral($clnm[0])."</a>($item[0] Topicos)<br/>".TextoGeral($clnm[1])."<br/>";
echo $lnk;
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
if($num_paginas>1){
echo "<br/>$pagina/$num_paginas<br/>";
}
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=comunidade&amp;token=$token\">Comunidades</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Random clubs
else if($menu=="rclb")
{
AdicionarOnline(GeraID($token),"Comunidades Aleatorios","");
//////ALL LISTS SCRIPT <<
$sql = "SELECT id, name, description FROM fun_clubs ORDER BY RAND()  LIMIT 5";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"sistema.php?menu=gocl&amp;clid=$item[0]&amp;token=$token\">".TextoGeral($item[1])."</a><br/>".TextoGeral($item[2])."<br/>";
echo $lnk;
}
}
echo "</small></p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=comunidade&amp;token=$token\">Comunidades</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////shouts
else if($menu=="annc")
{
AdicionarOnline(GeraID($token),"Vendo anuncios","");
$clid = $_GET["clid"];
//////ALL LISTS SCRIPT <<
$usuario_id = GeraID($token);
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE clid='".$clid."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, antext, antime  FROM fun_announcements WHERE clid='".$clid."' ORDER BY antime DESC LIMIT $limit_start, $items_per_pagina";
$cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
echo "<p><small>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if($cow[0]==$usuario_id)
{
$dlan = "<a href=\"genproc.php?menu=delan&amp;token=$token&amp;anid=$item[0]&amp;clid=$clid\">[x]</a>";
}else{
$dlan = "";
}
$annc = TextoGeral($item[1])."<br/>".date("d/m/y (H:i)", $item[2]);
echo "$annc $dlan<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;clid=$clid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;clid=$clid\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if($cow[0]==$usuario_id)
{
$dlan = "<a href=\"sistema.php?menu=annc&amp;token=$token&amp;clid=$clid\">Anunciar!</a><br/><br/>";
echo $dlan;
}
echo "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$clid\">";
echo "Voltar a Comunidade</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////clubs requests
else if($menu=="clreq")
{
AdicionarOnline(GeraID($token),"Solicitações de Comunidade","");
$clid = $_GET["clid"];
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
//////ALL LISTS SCRIPT <<
if($cowner[0]==$usuario_id)
{
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0' ORDER BY joined DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$shnick = GeraNickUsuario($item[0]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$shnick</a>: <a href=\"genproc.php?menu=acm&amp;usuario=$item[0]&amp;token=$token&amp;clid=$clid\">Aceitar</a>, <a href=\"genproc.php?menu=dcm&amp;usuario=$item[0]&amp;token=$token&amp;clid=$clid\">Negar</a><br/>";
echo "$lnk";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;clid=$clid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;clid=$clid\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/><a href=\"genproc.php?menu=accall&amp;clid=$clid&amp;token=$token\">Aceitar Todos</a>, ";
echo "<a href=\"genproc.php?menu=denall&amp;clid=$clid&amp;token=$token\">Negar Todos</a>";
echo "</p>";
}else{
echo "<p align=\"center\">This club isnt yours</p>";
}
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$clid\">";
echo "Voltar a Comunidade</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////clubs members
else if($menu=="clmem")
{
AdicionarOnline(GeraID($token),"Vendo Usuarios em comunidade","");
$clid = $_GET["clid"];
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, joined, points  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1' ORDER BY joined DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if($cowner[0]==$usuario_id)
{
$oop = ": <a href=\"sistema.php?menu=clmop&amp;token=$token&amp;usuario=$item[0]&amp;clid=$clid\">Configurações</a>";
}else{
$oop = "";
}
$shnick = GeraNickUsuario($item[0]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$shnick</a>$oop<br/>";
$lnk .= "Entrou em: ".date("d/m/y", $item[1])." - Pontos: $item[2]";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;clid=$clid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;clid=$clid\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$clid\">";
echo "Voltar a Comunidade</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////User topics
else if($menu=="tbuid")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Usuarios que esta no topico","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authosala='".$usuario."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, crdate  FROM fun_topics  WHERE authosala='".$usuario."'ORDER BY crdate DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if(AcessoAoForum(GeraID($token),GeraIDForum($item[0])))
{
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$item[0]\">".TextoGeral($item[1])."</a> <small>".date("d m y-H:i:s",$item[2])."</small><br/>";
}else{
echo "Topico Privado<br/>";
}
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">";
echo "Perfil de $unick</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////User topics
else if($menu=="uposts")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Postagem de Usuario","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$usuario."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, dtpost  FROM fun_posts  WHERE uid='".$usuario."'ORDER BY dtpost DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$tid = GeraIdTopico($item[0]);
$tname = GeraNomeTopico($tid);
if(AcessoAoForum(GeraID($token),GeraIDForum($tid)))
{
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;go=$item[0]\">".TextoGeral($tname)."</a> <small>".date("d m y-H:i:s",$item[1])."</small><br/>";
}else{
echo "Private Post<br/>";
}
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">";
echo "Perfil de $unick</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Gamers
else if($menu=="tgame")
{
AdicionarOnline(GeraID($token),"Top Jogadores","");
echo "<p align=\"center\">";
echo "<b>Top Jogadores</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, gplus FROM fun_users ORDER BY gplus DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Pontos de Jogo: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=tgame&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=tgame&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($menu=="topb")
{
AdicionarOnline(GeraID($token),"Top Lutadores","");
echo "<p align=\"center\">";
echo "<b>Top Battlers</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, battlep FROM fun_users ORDER BY battlep DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Pontos de Batalha: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=topb&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=topb&amp;pagina=$npagina&amp;token=$token\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Banned
else if($menu=="banned")
{
AdicionarOnline(GeraID($token),"Usuarios Penalizados","");
echo "<p align=\"center\">";
echo "<b>Lista de Banidos</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='1' OR penalty='2'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, penalty, pnreas, exid FROM fun_penalties WHERE penalty='1' OR penalty='2' ORDER BY timeto LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">".GeraNickUsuario($item[0])."</a> (".TextoGeral($item[2]).")";
if($item[1]=="1")
{
$bt = "Nick Banido";
}else{
$bt = "IP Banido";
}
if(Moderador(GeraID($token)))
{
$bym = "Por ".GeraNickUsuario($item[3]);
}else{
$bym = "";
}
echo "<small>$lnk $bt $bym</small><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=banned&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=banned&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Trashed
else if($menu=="trashed")
{
AdicionarOnline(GeraID($token),"Usuarios Bloquiados","");
echo "<p align=\"center\">";
echo "<b>Lista de Bloquiados</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if(Moderador(GeraID($token)))
{
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='0'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, penalty, pnreas, exid FROM fun_penalties WHERE penalty='0' ORDER BY timeto LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">".GeraNickUsuario($item[0])."</a> (".TextoGeral($item[2]).")";
if(Moderador(GeraID($token)))
{
$bym = "POR ".GeraNickUsuario($item[3]);
}else{
$bym = "";
}
echo "<small>$lnk $bym</small><br/>";
}
}
}else{
echo "Você não pode exibir esta lista";
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=trashed&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=trashed&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Trashed
else if($menu=="ipban")
{
AdicionarOnline(GeraID($token),"Lista de Usuarios Banidos","");
echo "<p align=\"center\">";
echo "<b>Lista de Banidos</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if(Moderador(GeraID($token)))
{
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='2'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, penalty, pnreas, exid, ipadd FROM fun_penalties WHERE penalty='2' ORDER BY timeto LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">".TextoGeral($item[0])."</a> (".TextoGeral($item[2]).")";
if(Moderador(GeraID($token)))
{
$bym = "Por ".GeraNickUsuario($item[3]);
}else{
$bym = "";
}
$ipl = "IP:<a href=\"lists.php?menu=byip&amp;token=$token&amp;usuario=$item[0]\">$item[4]</a>";
echo "<small>$lnk $bym ($ipl)</small><br/>";
}
}
}else{
echo "Você não pode exibir esta lista";
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Smilies :)
else if($menu=="smilies")
{
AdicionarOnline(GeraID($token),"vendo Lista de Smilies","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_smilies"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, scode, imgsrc FROM fun_smilies ORDER BY id DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if(Administrador(GeraID($token)))
{
$delsl = "<a href=\"admproc.php?menu=delsm&amp;token=$token&amp;smid=$item[0]\">[x]</a>";
}else{
$delsl = "";
}
echo "$item[1] &#187; ";
echo "<img src=\"$item[2]\" alt=\"$item[1]\"/> $delsl<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=smilies&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=smilies&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Moods :)
else if($menu=="chmood")
{
AdicionarOnline(GeraID($token),"Lista de Modulos","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_moods"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT text, img, dscr, id FROM fun_moods ORDER BY id DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
echo "<small><a href=\"genproc.php?menu=upcm&amp;token=$token&amp;cmid=$item[3]\">$item[0]</a> &#187; ";
echo "<img src=\"$item[1]\" alt=\"$item[0]\"/> &#187; $item[2] </small><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"genproc.php?menu=upcm&amp;token=$token&amp;cmid=0\">Desabilitar Modo Chat</a><br/><br/>";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=chmood&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=chmood&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=chat&amp;token=$token\">";
echo "Salas de Chat</a><br/>";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Avatars
else if($menu=="avatars")
{
AdicionarOnline(GeraID($token),"Vendo Lista de Avatar","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_avatars"));
$num_items = $noi[0]; //changable
$items_per_pagina= 2;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, avlink FROM fun_avatars ORDER BY id DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
echo "<img src=\"$item[1]\" alt=\"avatar\"/><br/>";
echo "<a href=\"genproc.php?menu=upav&amp;token=$token&amp;avid=$item[0]\">Selecionar</a><br/>";
echo "<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=avatars&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=avatars&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////E-cards
else if($menu=="ecards")
{
AdicionarOnline(GeraID($token),"Lista de Cartões","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_cards"));
$num_items = $noi[0]; //changable
$items_per_pagina= 2;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, category FROM fun_cards ORDER BY id DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$sl = strlen($item[0]);
$cid="";
if($sl<3)
{
for($i=$sl;$i<3;$i++)
{
$cid .= "0";
}
}
$cid .= $item[0];
$msg = "Texto Exemplo";
echo "<img src=\"pmcard.php?cid=$cid&amp;msg=$msg\" alt=\"$cid\"/><br/>";
echo "<small>[cartao=$cid]$msg"."[/cartao]</small>";
echo "<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Buddies
else if($menu=="amigos")
{
AdicionarOnline(GeraID($token),"Vendo Lista de Amigos","");
$usuario_id = GeraID($token);
echo "<p align=\"center\">";
echo "Lista de Amigos<br/>";
echo TextoGeral(MensagemAmigos($usuario_id), $token);
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalDeAmigos($usuario_id); //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT a.lastact, a.name, a.id, b.uid, b.tid, b.reqdt FROM fun_users a INNER JOIN fun_buddies b ON (a.id = b.uid) OR (a.id=b.tid) WHERE (b.uid='".$usuario_id."' OR b.tid='".$usuario_id."') AND b.agreed='1' AND a.id!='".$usuario_id."' GROUP BY 1,2  ORDER BY a.lastact DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if(VerificaOnline($item[2]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
$uact = "Local: ";
$plc = mysql_fetch_array(mysql_query("SELECT place FROM fun_online WHERE usesala='".$item[2]."'"));
$uact .= $plc[0];
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
$uact = "Ultima Vez Ativo: ";
$ladt = date("d m y-H:i:s", $item[0]);
$uact .= $ladt;
}
$lnk = "<a href=\"perfil.php?usuario=$item[2]&amp;token=$token\">$iml$item[1]</a>";
echo "$lnk<br/>";
echo "<small>";
$bs = date("d m y-H:i:s",$item[5]);
echo "Amigos desde:$bs<br/>";
echo "$uact<br/>";
echo "Mensagem: ";
$bmsg = TextoGeral(MensagemAmigos($item[2]), $token);
echo "$bmsg<br/>";
echo "</small>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=amigos&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=amigos&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=chbmsg&amp;token=$token\">";
echo "Mensagem da Lista de Amigos</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Buddies
else if($menu=="gbook")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Vendo Livros de Visita","");
$usuario_id = GeraID($token);
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$usuario."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT gbowner, gbsigner, gbmsg, dtime, id FROM fun_gbook WHERE gbowner='".$usuario."' ORDER BY dtime DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if(VerificaOnline($item[1]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$snick = GeraNickUsuario($item[1]);
$lnk = "<a href=\"perfil.php?usuario=$item[1]&amp;token=$token\">$iml$snick</a>";
$bs = date("d m y-H:i:s",$item[3]);
echo "$lnk<br/><small>";
if(PermissaoLivro($usuario_id, $item[4]))
{
$delnk = "<a href=\"genproc.php?menu=delfgb&amp;token=$token&amp;mid=$item[4]\">[x]</a>";
}else{
$delnk = "";
}
$text = TextoMensagens($item[2], $token);
echo "$text<br/>$bs $delnk<br/>";
echo "</small>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if(AcessoLivroVisita($usuario_id, $usuario))
{
echo "<a href=\"sistema.php?menu=signgb&amp;token=$token&amp;usuario=$usuario\">";
echo "Adicionar Mensagem</a><br/>";
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Buddies
else if($menu=="downloads")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Lista de Downloads","");
$usuario_id = GeraID($token);
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
if($usuario!="")
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads WHERE uid='".$usuario."'"));
}else{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads"));
}
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($usuario!="")
{
$sql = "SELECT id, title, itemurl FROM fun_downloads WHERE uid='".$usuario."' ORDER BY pudt DESC LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT id, title, itemurl, uid FROM fun_downloads  ORDER BY pudt DESC LIMIT $limit_start, $items_per_pagina";
}
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$ext = GeraExtencao($item[2]);
$ime = GeraImgExtencao($ext);
$lnk = "<a href=\"$item[2]\">$ime".TextoGeral($item[1])."</a>";
if(PermissaoDow($usuario_id, $item[0]))
{
$delnk = "<a href=\"genproc.php?menu=delvlt&amp;token=$token&amp;vid=$item[0]\">[x]</a>";
}else{
$delnk = "";
}
if($usuario!="")
{
$byusr="";
}else{
$unick = GeraNickUsuario($item[3]);
$ulnk = "<a href=\"perfil.php?token=$token&amp;usuario=$item[3]\">$unick</a>";
$byusr = "- Por $ulnk";
}
echo "$lnk $byusr $delnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterioe</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if($usuario_id==$usuario && GeraPontos($usuario_id)>25)
{
echo "<a href=\"sistema.php?menu=addvlt&amp;token=$token\">";
echo "Adicionar Download</a><br/>";
}
if($usuario!="")
{
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">";
$usuarionick = GeraNickUsuario($usuario);
echo "Perfil de $usuarionick</a><br/>";
}else{
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Status</a><br/>";
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Ignore list
else if($menu=="ignl")
{
AdicionarOnline(GeraID($token),"Lista de Ignorados","");
$usuario_id = GeraID($token);
echo "<p align=\"center\">";
echo "<b>Lista de Ignorados</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE name='".$usuario_id."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
/*
$sql = "SELECT
a.name, b.place, b.usesala FROM fun_users a
INNER JOIN fun_online b ON a.id = b.usesala
GROUP BY 1,2
LIMIT $limit_start, $items_per_pagina
";
*/
$sql = "SELECT target FROM fun_ignore WHERE name='".$usuario_id."' LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$tnick = GeraNickUsuario($item[0]);
if(VerificaOnline($item[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$iml$tnick</a>";
echo "$lnk: ";
echo "<small><a href=\"genproc.php?menu=ign&amp;usuario=$item[0]&amp;token=$token&amp;todo=del\">Remover</a></small><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=ignl&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=ignl&amp;pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Blogs
else if($menu=="blogs")
{
AdicionarOnline(GeraID($token),"Vendo Paginas Pessoais","");
$usuario_id = GeraID($token);
$usuario = $_GET["usuario"];
$tnick = GeraNickUsuario($usuario);
echo "<p align=\"center\">";
echo "<b>Paginas de $tnick</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='".$usuario."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT id, bname FROM fun_blogs WHERE bowner='".$usuario."' ORDER BY bgdate DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$bname = TextoGeral($item[1]);
if(PermissaoPaginaPessoal($usuario_id,$item[0]))
{
$dl = "<a href=\"genproc.php?menu=delbl&amp;token=$token&amp;bid=$item[0]\">[X]</a>";
}else{
$dl = "";
}
$lnk = "<a href=\"sistema.php?menu=viewblog&amp;bid=$item[0]&amp;token=$token\">&#187;$bname</a>";
echo "$lnk $dl<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if($usuario==$usuario_id)
{
echo "<a href=\"sistema.php?menu=addblg&amp;token=$token\">";
echo "Adicionar Pagina Pessoal</a><br/>";
echo "<a href=\"sistema.php?menu=cpanel&amp;token=$token\">";
echo "Painel de Administração</a><br/>";
}
echo "<a href=\"lists.php?menu=allbl&amp;token=$token\">";
echo "Todas Paginas Pessoas</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Blogs
else if($menu=="allbl")
{
AdicionarOnline(GeraID($token),"vendo Paginas Pessoais","");
$usuario_id = GeraID($token);
$view = $_GET["view"];
if($view =="")$view="time";
echo "<p align=\"center\"><small>";
if($view!="time")
{
echo "<a href=\"lists.php?menu=allbl&amp;token=$token&amp;view=time\">Ver mais novo</a><br/>";
}
if($view!="points")
{
echo "<a href=\"lists.php?menu=allbl&amp;token=$token&amp;view=points\">Ver com mais pontos</a><br/>";
}
if($view!="rate")
{
echo "<a href=\"lists.php?menu=allbl&amp;token=$token&amp;view=rate\">Ver mais visitado</a><br/>";
}
if($view!="votes")
{
echo "<a href=\"lists.php?menu=allbl&amp;token=$token&amp;view=votes\">Ver mais votado</a>";
}
echo "</small></p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs"));
$num_items = $noi[0]; //changable
$items_per_pagina= 7;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($view=="time")
{
$ord = "a.bgdate";
}else if($view=="votes")
{
$ord = "nofv";
}else if($view=="rate")
{
$ord = "avv";
}else if($view=="points")
{
$ord = "nofp";
}
if ($view=="time"){
$sql = "SELECT id, bname, bowner FROM fun_blogs ORDER by bgdate DESC LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT a.id, a.bname, a.bowner, COUNT(b.id) as nofv, SUM(b.brate) as nofp, AVG(b.brate) as avv FROM fun_blogs a INNER JOIN fun_brate b ON a.id = b.blogid GROUP BY a.id ORDER BY $ord DESC LIMIT $limit_start, $items_per_pagina";
}
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$bname = TextoGeral($item[1]);
if($view=="time")
{
$bonick = GeraNickUsuario($item[2]);
$byview = "Por <a href=\"perfil.php?token=$token&amp;usuario=$item[2]\">$bonick</a>";
}else if($view=="votes")
{
$byview = "Votos: $item[3]";
}else if($view=="rate")
{
$byview = "Nivel: $item[5]";
}else if($view=="points")
{
$byview = "Pontos: $item[4]";
}
$lnk = "<a href=\"sistema.php?menu=viewblog&amp;bid=$item[0]&amp;token=$token\">&#187;$bname</a> $byview";
echo "$lnk<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}//////////////////////////////////Blogs
else if($menu=="polls")
{
AdicionarOnline(GeraID($token),"Enquetes","");
$usuario_id = GeraID($token);
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE pollid>'0'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT id, name FROM fun_users WHERE pollid>'0' ORDER by pollid DESC LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
echo "By <a href=\"sistema.php?menu=viewpl&amp;usuario=$item[0]&amp;token=$token\">$item[1]</a><br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($menu=="tshout")
{
AdicionarOnline(GeraID($token),"Top Postadores do Mural","");
echo "<p align=\"center\">";
echo "<b>Top Mural</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = TotalRegistros(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, shouts FROM fun_users ORDER BY shouts DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Postagens: $item[2]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=tshout&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=tshout&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($menu=="bbcode")
{
AdicionarOnline(GeraID($token),"Lista de BBCodes","");
echo "<p align=\"center\">";
echo "<b>BBcode</b>";
echo "</p>";
echo "<p>";
echo "<b>Atenção:</b> O mau uso dos bbcodes pode causar erros de exibição<br/><br/>";
echo "[b]TEXTO[/b]: <b>TEXTO</b><br/>";
echo "[i]TEXTO[/i]: <i>TEXTO</i><br/>";
echo "[u]TEXTO[/u]: <u>TEXTO</u><br/>";
echo "[big]TEXTO[/big]: <big>TEXTO</big><br/>";
echo "[small]TEXTO[/small]: <small>TEXTO</small><br/>";
echo "[url=<i>http://link_do_site.com</i>]<i>Titulo</i>[/url]: <a href=\"http://link.com.br\">Titulo</a><br/>";
echo "<br/>";
echo "[topico=<i>1501</i>]<i>Nome do Topico</i>[/topico]: <a href=\"sistema.php?menu=viewtpc&amp;tid=1501&amp;token=$token\">Nome do Topico</a><br/>";
echo "<br/>";
echo "[pagina=<i>1</i>]<i>Nome da Pagina</i>[/pagina]: <a href=\"sistema.php?menu=viewblog&amp;bid=1&amp;token=$token\">Nome da Pagina</a><br/>";
echo "<br/>";
echo "[comunidade=<i>1</i>]<i>Nome da Comunidade</i>[/comunidade]: <a href=\"sistema.php?menu=gocl&amp;clid=1501&amp;token=$token\">Nome da Comunidade</a><br/>";
echo "<br/>";
echo "[br/]: Pular Linha:";
echo "Texto[br/]Texto!:<br/>Texto<br/>Texto<br/><br/>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($menu=="faqs")
{
AdicionarOnline(GeraID($token),"Ajuda","");
echo "<p align=\"center\">";
echo "<b>Ajuda</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_faqs"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT question, answer FROM fun_faqs ORDER BY id LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$item[0] = TextoMensagens($item[0], $token);
$item[1] = TextoMensagens($item[1], $token);
echo "<b>Q. $item[0]</b><br/>";
echo "A. $item[1]<br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Staff
else if($menu=="staff")
{
AdicionarOnline(GeraID($token),"Vendo Equipe Online","");
echo "<p align=\"center\">";
echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
echo "<b>Equipe Online</b><br/><small>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='2'"));
echo "<a href=\"lists.php?menu=admns&amp;token=$token\">Administradores($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='1'"));
echo "<a href=\"lists.php?menu=modr&amp;token=$token\">Moderadores($noi[0])</a></small>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm>'0'"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, perm FROM fun_users WHERE perm>'0' ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if($item[2]=='1')
{
$tit = "Moderador";
}else{
$tit = "Administrador";
}
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>$tit</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=staff&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=staff&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Staff
else if($menu=="admns")
{
AdicionarOnline(GeraID($token),"Lista de Adminis","");
echo "<p align=\"center\">";
echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
echo "<b>Lista de Admins</b><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='2'"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE perm='2' ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=admns&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=admns&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////judges
else if($menu=="judg")
{
AdicionarOnline(GeraID($token),"Lista de Juizes","");
echo "<p align=\"center\">";
echo "<b>Juizes de Batalha</b><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_judges"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid FROM fun_judges LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">".GeraNickUsuario($item[0])."</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=judg&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=judg&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Staff
else if($menu=="modr")
{
AdicionarOnline(GeraID($token),"Lista de Moderadores","");
echo "<p align=\"center\">";
echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
echo "<b>Moderators List</b><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='1'"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE perm='1' ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=modr&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=modr&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($menu=="tpweek")
{
AdicionarOnline(GeraID($token),"Top Postadores da semana","");
echo "<p align=\"center\">";
echo "Top Postadores da Semana<br/><small>Obrigado, você trouxe a vida a este site nos últimos 7 dias</small>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$weekago = time();
$weekago -= 7*24*60*60;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts WHERE dtpost>'".$weekago."';"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, COUNT(*) as nops FROM fun_posts  WHERE dtpost>'".$weekago."'  GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$unick = GeraNickUsuario($item[0]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$unick</a> <small>Postagens: $item[1]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=tpweek&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=tpweek&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($menu=="tptime")
{
AdicionarOnline(GeraID($token),"Top Postadores ","");
echo "<p align=\"center\">";
echo "Top Postador";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT uid, COUNT(*) as nops FROM fun_posts   GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$unick = GeraNickUsuario($item[0]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$unick</a> <small>Postagem: $item[1]</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=tptime&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=tptime&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Males List
else if($menu=="males")
{
AdicionarOnline(GeraID($token),"Lista de Homens","");
echo "<p align=\"center\">";
echo "<img src=\"images/male.gif\" alt=\"*\"/><br/>";
echo "<b>Homens</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='M'"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, birthday FROM fun_users WHERE sex='M' ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$uage = GeraIdade($item[2]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Idade: $uage</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=males&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=males&amp;pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Males List
else if($menu=="fems")
{
AdicionarOnline(GeraID($token),"Lista de Mulheres","");
echo "<p align=\"center\">";
echo "<img src=\"images/female.gif\" alt=\"*\"/><br/>";
echo "<b>Mulheres</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='F'"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, birthday FROM fun_users WHERE sex='F' ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$uage = GeraIdade($item[2]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Idade: $uage</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=fems&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=fems&amp;pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////Today's Birthday'
else if($menu=="bdy")
{
AdicionarOnline(GeraID($token),"Lista de Aniversariantes","");
echo "<p align=\"center\">";
echo "<img src=\"images/cake.gif\" alt=\"*\"/><br/>";
echo "Aniversriantes do Dia:";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi =mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, name, birthday  FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$uage = GeraIdade($item[2]);
$lnk = "<a href=\"perfil.php?usuario=$item[0]&amp;token=$token\">$item[1]</a> <small>Idade: $uage</small>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=bdy&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=bdy&amp;pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Iniciar</a>";
echo "</p>";
}
//////////////////////////////////Browsers
else if($menu=="brows")
{
AdicionarOnline(GeraID($token),"Lista de Navegadores","");
echo "<p align=\"center\">";
echo "<b>Navegadores</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL "));
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT browserm, COUNT(*) as notl FROM fun_users    WHERE browserm!='' GROUP BY browserm ORDER BY notl DESC LIMIT $limit_start, $items_per_pagina";
//$moderatorz=mysql_query("SELECT tlphone, COUNT(*) as notl FROM users GROUP BY tlphone ORDER BY notl DESC LIMIT  ".$paginast.",5");
$cou = $limit_start;
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$cou++;
$lnk = "$cou-$item[0] <b>$item[1]</b>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"lists.php?menu=brows&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"lists.php?menu=brows&amp;pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>