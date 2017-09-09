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
}else if($menu=="tpc")
{
AdicionarOnline(GeraID($token),"Pesquisando Topicos","");
echo "<p>";
echo "<form action=\"search.php?menu=stpc&amp;token=$token\" method=\"post\">";
echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
echo "Em: <select name=\"sin\">";
echo "<option value=\"1\">Postagens</option>";
echo "<option value=\"2\">Assunto</option>";
echo "<option value=\"3\">Nome</option>";
echo "</select><br/>";
echo "Ordem: <select name=\"sor\">";
echo "<option value=\"1\">Mais Novas</option>";
echo "<option value=\"2\">Mais Antigos</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="blg")
{
AdicionarOnline(GeraID($token),"Pesquisando Pagina Pessoal","");
echo "<p>";
echo "<form action=\"search.php?menu=sblg&amp;token=$token\" method=\"post\">";
echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
echo "Em: <select name=\"sin\">";
echo "<option value=\"1\">Texto</option>";
echo "<option value=\"2\">Nome</option>";
echo "</select><br/>";
echo "Ordem: <select name=\"sor\">";
echo "<option value=\"1\">Por Nome</option>";
echo "<option value=\"2\">Data</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="clb")
{
AdicionarOnline(GeraID($token),"Pesquisando Comunidades","");
echo "<p>";
echo "<form action=\"search.php?menu=sclb&amp;token=$token\" method=\"post\">";
echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
echo "Em: <select name=\"sin\">";
echo "<option value=\"1\">Descrição</option>";
echo "<option value=\"2\">Nome</option>";
echo "</select><br/>";
echo "Ordem: <select name=\"sor\">";
echo "<option value=\"1\">Nome</option>";
echo "<option value=\"2\">Mais Antiga</option>";
echo "<option value=\"3\">Mais Nova</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="nbx")
{
AdicionarOnline(GeraID($token),"Pesquisando Mensagem","");
echo "<p>";
echo "<form action=\"search.php?menu=snbx&amp;token=$token\" method=\"post\">";
echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
echo "Em: <select name=\"sin\">";
echo "<option value=\"1\">Recebidas</option>";
echo "<option value=\"2\">Enviadas</option>";
echo "<option value=\"3\">Remetente</option>";
echo "</select><br/>";
echo "Ordem: <select name=\"sor\">";
echo "<option value=\"1\">Mais Novas</option>";
echo "<option value=\"2\">Mais Antigas</option>";
echo "<option value=\"2\">Remetente</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="mbrn")
{
AdicionarOnline(GeraID($token),"Buscando Usuarios","");
echo "<p>";
echo "<form action=\"search.php?menu=smbr&amp;token=$token\" method=\"post\">";
echo "Nick: <input name=\"stext\" maxlength=\"15\"/><br/>";
echo "Ordem: <select name=\"sor\">";
echo "<option value=\"1\">Nick</option>";
echo "<option value=\"2\">Ultima vez ativo</option>";
echo "<option value=\"3\">Data de Cadastro</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="stpc")
{
$stext = $_POST["stext"];
$sin = $_POST["sin"];
$sor = $_POST["sor"];
AdicionarOnline(GeraID($token),"Pesquisando Topicos","");
echo "<p>";
if(trim($stext)=="")
{
echo "<br/>Por favor Especifique o texto a procurar";
}else{
//begin search
if($pagina=="" || $pagina<1)$pagina=1;
if($sin=="1")
{
$where_table = "fun_posts";
$cond = "text";
$select_fields = "id, tid";
if($sor=="1")
{
$ord_fields = "dtpost DESC";
}else{
$ord_fields = "dtpost";
}
}else if($sin=="2")
{
$where_table = "fun_topics";
$cond = "text";
$select_fields = "name, id";
if($sor=="1")
{
$ord_fields = "crdate DESC";
}else{
$ord_fields = "crdate";
}
}else if($sin=="3")
{
$where_table = "fun_topics";
$cond = "name";
$select_fields = "name, id";
if($sor=="1")
{
$ord_fields = "crdate DESC";
}else{
$ord_fields = "crdate";
}
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
$num_items = $noi[0];
$items_per_pagina = 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while($item=mysql_fetch_array($items))
{
if($sin=="1")
{
$tname = TextoGeral(GeraNomeTopico($item[1]));
if($tname=="" || !AcessoAoForum(GeraID($token),GeraIDForum($item[1]))){
$tlink = "Unreachable<br/>";
}else{
$tlink = "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$item[1]&amp;go=$item[0]\">".$tname."</a><br/>";
}
echo  $tlink;
}
else
{
$tname = TextoGeral($item[0]);
if($tname=="" || !AcessoAoForum(GeraID($token),GeraIDForum($item[1]))){
$tlink = "Unreachable<br/>";
}else{
$tlink = "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$item[1]\">".$tname."</a><br/>";
}
echo  $tlink;
}
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Anterior\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Proximo\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$pagina\" method=\"post\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="sblg")
{
$stext = $_POST["stext"];
$sin = $_POST["sin"];
$sor = $_POST["sor"];
AdicionarOnline(GeraID($token),"Pesquisando Pagina Pessoal","");
echo "<p>";
if(trim($stext)=="")
{
echo "<br/>Não foi possivel pesquisar!";
}else{
//begin search
if($pagina=="" || $pagina<1)$pagina=1;
if($sin=="1")
{
$where_table = "fun_blogs";
$cond = "btext";
$select_fields = "id, bname";
if($sor=="1")
{
$ord_fields = "bname";
}else{
$ord_fields = "bgdate DESC";
}
}else if($sin=="2")
{
$where_table = "fun_blogs";
$cond = "bname";
$select_fields = "id, bname";
if($sor=="1")
{
$ord_fields = "bname";
}else{
$ord_fields = "bgdate DESC";
}
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
$num_items = $noi[0];
$items_per_pagina = 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while($item=mysql_fetch_array($items))
{
$tlink = "<a href=\"sistema.php?menu=viewblog&amp;token=$token&amp;bid=$item[0]&amp;go=$item[0]\">".TextoGeral($item[1])."</a><br/>";
echo  $tlink;
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Anterior\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Proximo\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$pagina\" method=\"post\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="sclb")
{
$stext = $_POST["stext"];
$sin = $_POST["sin"];
$sor = $_POST["sor"];
AdicionarOnline(GeraID($token),"Pesquisando Comunidade","");
echo "<p>";
if(trim($stext)=="")
{
echo "<br/>Não foi possivel procurar comunidade";
}else{
//begin search
if($pagina=="" || $pagina<1)$pagina=1;
if($sin=="1")
{
$where_table = "fun_clubs";
$cond = "description";
$select_fields = "id, name";
if($sor=="1")
{
$ord_fields = "name";
}else if($sor=="2"){
$ord_fields = "created";
}else if($sor=="3"){
$ord_fields = "created DESC";
}
}else if($sin=="2")
{
$where_table = "fun_clubs";
$cond = "name";
$select_fields = "id, name";
if($sor=="1")
{
$ord_fields = "name";
}else if($sor=="2"){
$ord_fields = "created";
}else if($sor=="3"){
$ord_fields = "created DESC";
}
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
$num_items = $noi[0];
$items_per_pagina = 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while($item=mysql_fetch_array($items))
{
$tlink = "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$item[0]&amp;go=$item[0]\">".TextoGeral($item[1])."</a><br/>";
echo  $tlink;
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Anterior\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Proximo\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$pagina\" method=\"post\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="snbx")
{
$stext = $_POST["stext"];
$sin = $_POST["sin"];
$sor = $_POST["sor"];
AdicionarOnline(GeraID($token),"Pesquisando em Mensagens Privadas","");
echo "<p>";
$myid = GeraID($token);
if(trim($stext)=="")
{
echo "<br/>Erro ao pesquisar mensagem";
}else{
//begin search
if($pagina=="" || $pagina<1)$pagina=1;
if($sin==1)
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND touid='".$myid."'"));
}else if($sin==2)
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND byuid='".$myid."'"));
}else{
$stext = GeraIdPorNick($stext);
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE byuid ='".$stext."' AND touid='".$myid."'"));
}
$num_items = $noi[0];
$items_per_pagina = 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($sin=="1")
{
/*
$where_table = "fun_blogs";
$cond = "btext";
$select_fields = "id, bname";*/
if($sor=="1")
{
//$ord_fields = "bname";
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_pagina";
//echo $sql;
}else if($sor=="2"){
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY b.timesent 
LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY a.name
LIMIT $limit_start, $items_per_pagina";
}
}
else if($sin=="2")
{
if($sor=="1")
{
//$ord_fields = "bname";
$sql = "SELECT
a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_pagina";
//echo $sql;
}else if($sor=="2"){
$sql = "SELECT
a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY b.timesent 
LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT
a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.touid
WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
ORDER BY a.name
LIMIT $limit_start, $items_per_pagina";
}
}
else if($sin=="3")
{
if($sor=="1")
{
//$ord_fields = "bname";
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_pagina";
}else if($sor=="2"){
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
ORDER BY b.timesent
LIMIT $limit_start, $items_per_pagina";
}else{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
ORDER BY a.name
LIMIT $limit_start, $items_per_pagina";
}
}
$items = mysql_query($sql);
echo mysql_error();
while($item=mysql_fetch_array($items))
{
if($item[3]=="1")
{
$iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";
}else{
if($item[4]=="1")
{
$iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";
}else{
$iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";
}
}
$lnk = "<a href=\"mensagens.php?menu=readpm&amp;pmid=$item[1]&amp;token=$token\">$iml ".GeraNickUsuario($item[2])."</a>";
echo "$lnk<br/>";
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Anterior\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Proximo\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$pagina\" method=\"post\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="smbr")
{
$stext = $_POST["stext"];
$sin = $_POST["sin"];
$sor = $_POST["sor"];
AdicionarOnline(GeraID($token),"Pesquisando Comunidade","");
echo "<p>";
if(trim($stext)=="")
{
echo "<br/>Erro ao Pesquisar Comunidade";
}else{
//begin search
if($pagina=="" || $pagina<1)$pagina=1;
$where_table = "fun_users";
$cond = "name";
$select_fields = "id, name";
if($sor=="1")
{
$ord_fields = "name";
}else if($sor=="2"){
$ord_fields = "lastact DESC";
}else if($sor=="3"){
$ord_fields = "regdate";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
$num_items = $noi[0];
$items_per_pagina = 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while($item=mysql_fetch_array($items))
{
$tlink = "<a href=\"perfil.php?token=$token&amp;usuario=$item[0]\">".TextoGeral($item[1])."</a><br/>";
echo  $tlink;
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Anterior\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$ppagina\" method=\"post\">";
$rets .= "<input type=\"submit\" value=\"Proxima\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form> ";
echo $rets;
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"search.php?menu=$menu&amp;token=$token&amp;pagina=$pagina\" method=\"post\">";
$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
$rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
$rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=search&amp;token=$token\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Pesquisa</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
AdicionarOnline(GeraID($token),"Pagina Não existe","");
echo "<p align=\"center\">";
echo "A Pagina solicita não existe <br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>