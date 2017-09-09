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
VerificaBanNick();
VerificaLogin();

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
}else if($menu=="showfrss")
{
AdicionarOnline(GeraID($token),"Lendo RSS","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss WHERE fid='".$fid."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, title, dscr, imgsrc, pubdate FROM fun_rss WHERE fid='".$fid."' ORDER BY id LIMIT $limit_start, $items_per_pagina";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if(trim($item[3]!=""))
{
$img = "<img src=\"$item[3]\" alt=\"*\"/>";
}else{
$img="";
}
$lnk = "$img<a href=\"rwrss.php?menu=readrss&amp;token=$token&amp;rstoken=$item[0]&amp;fid=$fid\">".TextoGeral($item[1])."</a><br/>";
$feedsc = TextoGeral($item[2]);
echo $lnk;
echo $feedsc;
echo "<br/>Data de Publicação: $item[4]<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"rwrss.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;fid=$fid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"rwrss.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;fid=$fid\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"rwrss.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo TextoGeral(GeraNomeForum($fid))."</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="readrss")
{
AdicionarOnline(GeraID($token),"Lendo RSS","");
echo "<p align=\"center\">";
$rssinfo = mysql_fetch_array(mysql_query("SELECT lupdate, link FROM fun_rss WHERE id='".$rstoken."'"));
$updt = time() - 3600;
if($rssinfo[0]<$updt)
{
///code to refresh info
$rss = new lastRSS;
$rss->cache_dir = './rss';
$rss->cache_time = 3600;
$rss->date_format = 'd m y - H:i';
$rss->stripHTML = true;
$rssurl = $rssinfo[1];
if ($rs = $rss->get($rssurl))
{
$title = $rs["title"];
$pgurl = $rs["link"];
$srcd = $rs["description"];
$pubdate = $rs["lastBuildDate"];
mysql_query("UPDATE fun_rss SET lupdate='".time()."', title='".$title."', pgurl='".$pgurl."', srcd='".$srcd."', pubdate='".$pubdate."' WHERE id='".$rstoken."'");
mysql_query("DELETE FROM fun_rssdata WHERE rstoken='".$rstoken."'");
$rssitems = $rs["items"];
for($i=0;$i<count($rssitems);$i++)
{
$rssitem = $rssitems[$i];
mysql_query("INSERT INTO fun_rssdata SET rstoken='".$rstoken."', title='".mysql_real_escape_string($rssitem["title"])."', link='".$rssitem["link"]."', text='".mysql_real_escape_string($rssitem["description"])."', pubdate='".$rssitem["pubDate"]."'");
}
}
else {
$errt = "Erro: Não é possível obter o serviço ...";
mysql_query("INSERT INTO fun_rssdata SET rstoken='".$rstoken."', title='ERRO!', link='', text='".mysql_real_escape_string($errt)."', pubdate='".time()."'");
}
}
$rssinfo = mysql_fetch_array(mysql_query("SELECT pgurl, title, srcd, imgsrc FROM fun_rss WHERE id='".$rstoken."'"));
echo "<img src=\"$rssinfo[3]\" alt=\"*\"/><br/>";
echo "<b>$rssinfo[1]</b><br/><small>";
echo $rssinfo[2];
echo "</small></p>";
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rssdata WHERE rstoken='".$rstoken."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT id, title,  text, pubdate FROM fun_rssdata WHERE rstoken='".$rstoken."' ORDER BY id LIMIT $limit_start, $items_per_pagina";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
$lnk = "<img src=\"images/star.gif\" alt=\"*\"/><b>".$item[1]."</b><br/>";
$feedsc = $item[2];
echo $lnk;
echo $feedsc;
echo "<br/>Data de Publicação: $item[3]<br/><img src=\"images/line.jpg\" alt=\"*\"/><br/>";
}
}
echo "</small></p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"rwrss.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;rstoken=$rstoken&amp;fid=$fid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"rwrss.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;rstoken=$rstoken&amp;fid=$fid\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"rwrss.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"submit\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"submit\" name=\"rstoken\" value=\"$rstoken\"/>";
$rets .= "<input type=\"submit\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"submit\" name=\"fid\" value=\"$fid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
echo "<p align=\"center\">";
if($fid!=""||$fid>0)
{
$fname = TextoGeral(GeraNomeForum($fid));
echo "<a href=\"rwrss.php?menu=showfrss&amp;token=$token&amp;fid=$fid\"><img src=\"images/rss.gif\" alt=\"rss\"/>$fname Mais...</a><br/>";
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo $fname."</a><br/>";
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
AdicionarOnline(GeraID($token),"Sem informações","");
echo "<p align=\"center\">";
echo "Pagina Solicitada não existe<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_sistema.php?menu=diversao&amp;");
?>