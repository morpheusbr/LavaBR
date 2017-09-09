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
VerificaModerador();
if($menu=="inicio")
{
AdicionarOnline(GeraID($token),"Moderação","");
echo "<p align=\"center\">";
echo "<b>Reportadas</b>";
echo "</p>";
echo "<p>";
$nrpm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported='1'"));
echo "<a href=\"modcp.php?menu=rpm&amp;token=$token\">&#187; Mensagens Privadas($nrpm[0])</a><br/>";
$nrps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'"));
echo "<a href=\"modcp.php?menu=rps&amp;token=$token\">&#187;Postagem no Forum($nrps[0])</a><br/>";
$nrtp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'"));
echo "<a href=\"modcp.php?menu=rtp&amp;token=$token\">&#187;Tópicos do Forum($nrtp[0])</a>";
echo "</p>";
echo "<p align=\"center\">";
echo "<b>Logs</b>";
echo "</p>";
echo "<p>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog"));
if($noi[0]>0){
$nola = mysql_query("SELECT DISTINCT (menu)  FROM fun_mlog ORDER BY actdt DESC");
while($act=mysql_fetch_array($nola))
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE menu='".$act[0]."'"));
echo "<a href=\"modcp.php?menu=log&amp;token=$token&amp;view=$act[0]\">$act[0]($noi[0])</a><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////Reported PMs
else if($menu=="rpm")
{
$pagina = $_GET["pagina"];
echo "<p align=\"center\">";
echo "<b>Mensagem Privada Reportadas</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported ='1'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT id, text, byuid, touid, timesent FROM fun_private WHERE reported='1' ORDER BY timesent DESC LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while ($item=mysql_fetch_array($items))
{
$fromnk = GeraNickUsuario($item[2]);
$tonick = GeraNickUsuario($item[3]);
$dtop = date("d m y - H:i:s", $item[4]);
$text = TextoMensagens($item[1]);
$flk = "<a href=\"perfil.php?token=$token&amp;usuario=$item[2]\">$fromnk</a>";
$tlk = "<a href=\"perfil.php?token=$token&amp;usuario=$item[3]\">$tonick</a>";
echo "From: $flk To: $tlk<br/>Time: $dtop<br/>";
echo $text;
echo "<br/>";
echo "<a href=\"modproc.php?menu=hpm&amp;token=$token&amp;pid=$item[0]\">Tratar</a><br/><br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$npagina&amp;token=$token\">Próximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo " Relatório do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////Reported Posts
else if($menu=="rps")
{
$pagina = $_GET["pagina"];
echo "<p align=\"center\">";
echo "<b>Postagens Reportadas</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported ='1'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT id, text, tid, uid, dtpost FROM fun_posts WHERE reported='1' ORDER BY dtpost DESC LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while ($item=mysql_fetch_array($items))
{
$poster = GeraNickUsuario($item[3]);
$tname = TextoGeral(GeraNomeTopico($item[3]));
$dtop = date("d m y - H:i:s", $item[4]);
$text = TextoGeral($item[1]);
$flk = "<a href=\"perfil.php?token=$token&amp;usuario=$item[3]\">$poster</a>";
$tlk = "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$item[2]\">$tname</a>";
echo "Postagem: $flk<br/>por: $tlk<br/>Data: $dtop<br/>";
echo $text;
echo "<br/>";
echo "<a href=\"modproc.php?menu=hps&amp;token=$token&amp;pid=$item[0]\">Tratar</a><br/><br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$npagina&amp;token=$token\">Próximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatório do sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////Reported Posts
else if($menu=="log")
{
$pagina = $_GET["pagina"];
$view = $_GET["view"];
echo "<p align=\"center\">";
echo "<b>$view</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE  menu='".$view."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT  actdt, details FROM fun_mlog WHERE menu='".$view."' ORDER BY actdt DESC LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while ($item=mysql_fetch_array($items))
{
echo "Data: ".date("d m y-H:i:s", $item[0])."<br/>";
echo $item[1];
echo "<br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$npagina&amp;token=$token&amp;view=$view\">Próximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatório do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}
/////////////////////////////////Reported Topics
else if($menu=="rtp")
{
$pagina = $_GET["pagina"];
echo "<p align=\"center\">";
echo "<b>Tópicos Reportados</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($pagina=="" || $pagina<=0)$pagina=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported ='1'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 5;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
$sql = "SELECT id, name, text, authosala, crdate FROM fun_topics WHERE reported='1' ORDER BY crdate DESC LIMIT $limit_start, $items_per_pagina";
$items = mysql_query($sql);
while ($item=mysql_fetch_array($items))
{
$poster = GeraNickUsuario($item[3]);
$tname = TextoGeral($item[1]);
$dtop = date("d m y - H:i:s", $item[4]);
$text = TextoGeral($item[2]);
$flk = "<a href=\"perfil.php?token=$token&amp;usuario=$item[3]\">$poster</a>";
$tlk = "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$item[0]\">$tname</a>";
echo "Postado: $flk<br/>em: $tlk<br/>data: $dtop<br/>";
echo $text;
echo "<br/>";
echo "<a href=\"modproc.php?menu=htp&amp;token=$token&amp;tid=$item[0]\">Handle</a><br/><br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"modcp.php?menu=$menu&amp;pagina=$npagina&amp;token=$token\">Proxima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatório do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////////////Mod a user
else if($menu=="user")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
echo "<b>Moderando $unick</b>";
echo "</p>";
echo "<p>";
echo "<a href=\"modcp.php?menu=penopt&amp;token=$token&amp;usuario=$usuario\">&#187;Penalidade</a><br/>";
echo "<a href=\"modcp.php?menu=plsopt&amp;token=$token&amp;usuario=$usuario\">&#187;Pontos</a><br/><br/>";
if(VerificaBloqueio($usuario))
{
echo "<a href=\"modproc.php?menu=untr&amp;token=$token&amp;usuario=$usuario\">&#187;Ativar Conta</a><br/>";
}
if(VerificaBan($usuario))
{
echo "<a href=\"modproc.php?menu=unbn&amp;token=$token&amp;usuario=$usuario\">&#187;Desbanir</a><br/>";
}
if(!Protegido($usuario))
{
echo "<a href=\"modproc.php?menu=shld&amp;token=$token&amp;usuario=$usuario\">&#187;Proteger</a><br/>";
}else{
echo "<a href=\"modproc.php?menu=ushld&amp;token=$token&amp;usuario=$usuario\">&#187;Remover Proteção</a><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////Penalties Options
else if($menu=="penopt")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
echo "Oque você que fazer com $unick";
echo "</p>";
echo "<p>";
$pen[0]="Desativar Conta";
$pen[1]="Banir Nick";
$pen[2]="Banir Ip";
echo "<form action=\"modproc.php?menu=pun&amp;token=$token\" method=\"post\">";
echo "Penalidade: <select name=\"pid\">";
for($i=0;$i<count($pen);$i++)
{
echo "<option value=\"$i\">$pen[$i]</option>";
}
echo "</select><br/>";
echo "Motivo: <input name=\"pres\" maxlength=\"100\"/><br/>";
echo "Dias: <input name=\"pds\" format=\"*N\" maxlength=\"4\"/><br/>";
echo "Horas: <input name=\"phr\" format=\"*N\" maxlength=\"4\"/><br/>";
echo "Minutos: <input name=\"pmn\" format=\"*N\" maxlength=\"2\"/><br/>";
echo "Segundos: <input name=\"psc\" format=\"*N\" maxlength=\"2\"/><br/>";
echo "<input type=\"submit\" value=\"Executar\"/>";
echo "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////Penalties Options
else if($menu=="plsopt")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
echo "Adicionar ou remover pontos de $unick";
echo "</p>";
echo "<p>";
$pen[0]="Remover";
$pen[1]="Adicionar";
echo "<form action=\"modproc.php?menu=pls&amp;token=$token\" method=\"post\">";
echo "Você quer: <select name=\"pid\">";
for($i=0;$i<count($pen);$i++)
{
echo "<option value=\"$i\">$pen[$i]</option>";
}
echo "</select><br/>";
echo "Motivo: <input name=\"pres\" maxlength=\"100\"/><br/>";
echo "Pontos: <input name=\"pval\" format=\"*N\" maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar Pontos\"/>";
echo "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}
else{
echo "<p align=\"center\">";
echo "A pagina solicitada não existe!<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>