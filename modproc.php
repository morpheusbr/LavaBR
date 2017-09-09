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
AdicionarOnline(GeraID($token),"Painel do Moderador","");
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
}else if($menu=="delp")
{
$pid = $_GET["pid"];
$tid = GeraIdTopico($pid);
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$res = mysql_query("DELETE FROM fun_posts WHERE id='".$pid."'");
if($res)
{
$tname = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='".$tid."'"));
mysql_query("INSERT INTO fun_mlog SET menu='Postagens', details='<b>".GeraNickUsuario(GeraID($token))."</b> Deletada Postagem de numero $pid do topico ".LimpaTexto($tname[0])." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem removida com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;pagina=1000\">";
echo "Ver Topico</a><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////////Edit Post
else if($menu=="edtpst")
{
$pid = $_GET["pid"];
$ptext = $_POST["ptext"];
$tid = GeraIdTopico($pid);
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$res = mysql_query("UPDATE fun_posts SET text='"
.$ptext."' WHERE id='".$pid."'");
if($res)
{
$tname = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='".$tid."'"));
mysql_query("INSERT INTO fun_mlog SET menu='Postagens', details='<b>".GeraNickUsuario(GeraID($token))."</b> editou a postagem numero $pid do topico ".LimpaTexto($tname[0])." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem Editada";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid\">";
echo "Ver Topico</a><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////////Edit Post
else if($menu=="edttpc")
{
$tid = $_GET["tid"];
$ttext = $_POST["ttext"];
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$res = mysql_query("UPDATE fun_topics SET text='"
.$ttext."' WHERE id='".$tid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> Editou o texto do topico ".LimpaTexto(GeraNomeTopico($tid))." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico Editado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid\">";
echo "Ver Topico</a><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Close/ Open Topic
else if($menu=="clot")
{
$tid = $_GET["tid"];
$tdo = $_GET["tdo"];
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$res = mysql_query("UPDATE fun_topics SET closed='"
.$tdo."' WHERE id='".$tid."'");
if($res)
{
if($tdo==1)
{
$msg = "Fechado";
}else{
$msg = "Aberto";
}
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> Closed The thread ".LimpaTexto(GeraNomeTopico($tid))." at the forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic $msg";
$tpci = mysql_fetch_array(mysql_query("SELECT name, authosala FROM fun_topics WHERE id='".$tid."'"));
$tname = TextoGeral($tpci[0]);
$msg = "O Topico foi [topico=$tid]$tname"."[/topico] is $msg"."[br/][small][i]Mensagem do Sistema[/i][/small]";
MsgAutomatica($msg, $tpci[1]);
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Untrash user
else if($menu=="untr")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$res = mysql_query("DELETE FROM fun_penalties WHERE penalty='0' AND uid='".$usuario."'");
if($res)
{
$unick = GeraNickUsuario($usuario);
mysql_query("INSERT INTO fun_mlog SET menu='Penalidades', details='<b>".GeraNickUsuario(GeraID($token))."</b> desbloquiou o perfil de <b>".$unick."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>$unick foi desbloquiado";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Unban user
else if($menu=="unbn")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$res = mysql_query("DELETE FROM fun_penalties WHERE (penalty='1' OR penalty='2') AND uid='".$usuario."'");
if($res)
{
$unick = GeraNickUsuario($usuario);
mysql_query("INSERT INTO fun_mlog SET menu='Penalidades', details='<b>".GeraNickUsuario(GeraID($token))."</b> Desbaniu o perfil de <b>".$unick."</b>', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>$unick Desbanido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Delete shout

///////////////////////////////////////Unban user
else if($menu=="shld")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$res = mysql_query("Update fun_users SET shield='1' WHERE id='".$usuario."'");
if($res)
{
$unick = GeraNickUsuario($usuario);
mysql_query("INSERT INTO fun_mlog SET menu='Penalidades', details='<b>".GeraNickUsuario(GeraID($token))."</b> Protegeu <b>".$unick."</b>', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>$unick Protegido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Unban user
else if($menu=="ushld")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
$res = mysql_query("Update fun_users SET shield='0' WHERE id='".$usuario."'");
if($res)
{
$unick = GeraNickUsuario($usuario);
mysql_query("INSERT INTO fun_mlog SET menu='Penalidades', details='<b>".GeraNickUsuario(GeraID($token))."</b> desprotegeu <b>".$unick."</b>', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>$unick desprotegido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////Pin/ Unpin Topic
else if($menu=="pint")
{
$tid = $_GET["tid"];
$tdo = $_GET["tdo"];
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$pnd = VerificaDestaqueForum($fid);
if($pnd<=5)
{
$res = mysql_query("UPDATE fun_topics SET pinned='"
.$tdo."' WHERE id='".$tid."'");
if($res)
{
if($tdo==1)
{
$msg = "Destacou";
}else{
$msg = "Tirou dos Destaques";
}
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> $msgo Topico ".LimpaTexto(GeraNomeTopico($tid))." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico $msg";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Voce pode destacar 5 topicos em cada forum";
}
echo "<br/><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Delete the damn thing
else if($menu=="delt")
{
$tid = $_GET["tid"];
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$tname=GeraNomeTopico($tid);
$res = mysql_query("DELETE FROM fun_topics WHERE id='".$tid."'");
if($res)
{
mysql_query("DELETE FROM fun_posts WHERE tid='".$tid."'");
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> Removeu o topico ".LimpaTexto($tname)." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico Removido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////////Edit Post
else if($menu=="rentpc")
{
$tid = $_GET["tid"];
$tname = $_POST["tname"];
$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$otname = GeraNomeTopico($tid);
if(trim($tname!=""))
{
$not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$fid."'"));
if($not[0]==0)
{
$res = mysql_query("UPDATE fun_topics SET name='"
.$tname."' WHERE id='".$tid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> renomeado de ".LimpaTexto($otname)." para ".LimpaTexto($tname)." do forum ".GeraNomeForum($fid)."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico Renomeado";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico nao existe";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você deve especificar um nome para o tópico";
}
echo "<br/><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid\">";
echo "Ver Topico</a><br/>";
$fname = GeraNomeForum($fid);
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////////////////Move topic
else if($menu=="mvt")
{
$tid = $_GET["tid"];
$mtf = $_POST["mtf"];
$fname = TextoGeral(GeraNomeForum($mtf));
//$fid = GeraIDForum($tid);
echo "<p align=\"center\">";
$not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$mtf."'"));
if($not[0]==0)
{
$res = mysql_query("UPDATE fun_topics SET fid='"
.$mtf."', moved='1' WHERE id='".$tid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Topicos', details='<b>".GeraNickUsuario(GeraID($token))."</b> moveu o topico ".LimpaTexto($tname)." para o forum ".GeraNomeForum($fid)."', actdt='".time()."'");
$tpci = mysql_fetch_array(mysql_query("SELECT name, authosala FROM fun_topics WHERE id='".$tid."'"));
$tname = TextoGeral($tpci[0]);
$msg = "O topico [topico=$tid]$tname"."[/topico] para o forum $fname [br/][small][i]Mensagem do Sistema[/i][/small]";
MsgAutomatica($msg, $tpci[1]);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico Movido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico não existe";
}
echo "<br/><br/>";
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$mtf\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Handle PM
else if($menu=="hpm")
{
$pid = $_GET["pid"];
echo "<p align=\"center\">";
$info = mysql_fetch_array(mysql_query("SELECT byuid, touid FROM fun_private WHERE id='".$pid."'"));
$res = mysql_query("UPDATE fun_private SET reported='2' WHERE id='".$pid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Tratamento', details='<b>".GeraNickUsuario(GeraID($token))."</b> tratou a mensagem ".$pid."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagem tratada";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$info[0]\">Perfil de quem enviou</a><br/>";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$info[1]\">Perfil de quem reportou</a><br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatorio do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Handle Post
else if($menu=="hps")
{
$pid = $_GET["pid"];
echo "<p align=\"center\">";
$info = mysql_fetch_array(mysql_query("SELECT uid, tid FROM fun_posts WHERE id='".$pid."'"));
$res = mysql_query("UPDATE fun_posts SET reported='2' WHERE id='".$pid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Tratamento', details='<b>".GeraNickUsuario(GeraID($token))."</b> tratou a postagem ".$pid."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem Tratada";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
$poster = GeraNickUsuario($info[0]);
echo "<a href=\"perfil.php?token=$token&amp;usuario=$info[0]\">Perfil de $poster</a><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$info[1]\">Ver Topico</a><br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatorio do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Handle Topic
else if($menu=="htp")
{
$pid = $_GET["tid"];
echo "<p align=\"center\">";
$info = mysql_fetch_array(mysql_query("SELECT authosala FROM fun_topics WHERE id='".$pid."'"));
$res = mysql_query("UPDATE fun_topics SET reported='2' WHERE id='".$pid."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Tratamento', details='<b>".GeraNickUsuario(GeraID($token))."</b> tratou o topico ".LimpaTexto(GeraNomeTopico($pid))."', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico Tratado";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
$poster = GeraNickUsuario($info[0]);
echo "<a href=\"perfil.php?token=$token&amp;usuario=$info[0]\">Perfil de $poster</a><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$pid\">Ver Topico</a><br/><br/>";
echo "<a href=\"modcp.php?menu=inicio&amp;token=$token\">";
echo "Relatorio do Sistema</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////Punish
else if($menu=="pun")
{
$pid = $_POST["pid"];
$usuario = $_POST["usuario"];
$pres = $_POST["pres"];
$pds = $_POST["pds"];
$phr = $_POST["phr"];
$pmn = $_POST["pmn"];
$psc = $_POST["psc"];
echo "<p align=\"center\">";
$uip = "";
$navegador = "";
$pmsg[0]="Bloquiou";
$pmsg[1]="Baniu";
$pmsg[2]="Baniu IP";
if($pid=='2')
{
//ip ban
$uip = GeraIPUsuario($usuario);
$navegador = GeraNavUsuario($usuario);
}
if(trim($pres)=="")
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você deve especificar um motivo para punir o usuário";
}else{
$timeto = $pds*24*60*60;
$timeto += $phr*60*60;
$timeto += $pmn*60;
$timeto += $psc;
$ptime = $timeto + time();
$unick = GeraNickUsuario($usuario);
$res = mysql_query("INSERT INTO fun_penalties SET uid='".$usuario."', penalty='".$pid."', exid='".GeraID($token)."', timeto='".$ptime."', pnreas='".LimpaTexto($pres)."', ipadd='".$uip."', browserm='".$navegador."'");
if($res)
{
mysql_query("UPDATE fun_users SET lastpnreas='".$pmsg[$pid].": ".LimpaTexto($pres)."' WHERE id='".$usuario."'");
mysql_query("INSERT INTO fun_mlog SET menu='Penalidades', details='<b>".GeraNickUsuario(GeraID($token))."</b> $pmsg[$pid] de <b>".$unick."</b> por ".$timeto." segundos', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Você $pmsg[$pid] $unick por $timeto Segundos";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////Punish
else if($menu=="pls")
{
$pid = $_POST["pid"];
$usuario = $_POST["usuario"];
$pres = $_POST["pres"];
$pval = $_POST["pval"];
echo "<p align=\"center\">";
$unick = GeraNickUsuario($usuario);
$opl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_users WHERE id='".$usuario."'"));
if($pid=='0')
{
$npl = $opl[0] - $pval;
}else{
$npl = $opl[0] + $pval;
}
if($npl<0)
{
$npl=0;
}
if(trim($pres)=="")
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você deve especificar uma razão para dar pontos há $unick";
}else{
$res = mysql_query("UPDATE fun_users SET lastplreas='".LimpaTexto($pres)."', plusses='".$npl."' WHERE id='".$usuario."'");
if($res)
{
mysql_query("INSERT INTO fun_mlog SET menu='Pontos', details='<b>".GeraNickUsuario(GeraID($token))."</b> Atualizou os pontos de <b>".$unick."</b> de ".$opl[0]." para $npl', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Poontos de $unick foram atualizados de $opl[0] para $npl";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
echo "<p align=\"center\">";
echo "A Pagina solicitada não existe!<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>