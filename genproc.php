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
}else if($menu=="newtopic")
{
$fid = $_POST["fid"];
$ntitle = $_POST["ntitle"];
$tpctxt = $_POST["tpctxt"];
if(!AcessoAoForum(GeraID($token), $fid))
{
echo "<p align=\"center\">";
echo "Você não tem permissão para ler o conteúdo deste Fórum<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Criando novo topico","");
echo "<p align=\"center\">";
$crdate = time();
$texst = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$ntitle."' AND fid='".$fid."'"));
if($texst[0]==0)
{
$res = false;
$ltopic = mysql_fetch_array(mysql_query("SELECT crdate FROM fun_topics WHERE authosala='".$usuario_id."' ORDER BY crdate DESC LIMIT 1"));
global $config;
$antiflood = time()-$ltopic[0];
if($antiflood>$config['ANTI_REPETICAO_TOPICO'])
{
if((trim($ntitle)!="")||(trim($tpctxt)!=""))
{
$res = mysql_query("INSERT INTO fun_topics SET name='".$ntitle."', fid='".$fid."', authosala='".$usuario_id."', text='".$tpctxt."', crdate='".$crdate."', lastpost='".$crdate."'");
}
if($res)
{
$usts = mysql_fetch_array(mysql_query("SELECT posts, plusses FROM fun_users WHERE id='".$usuario_id."'"));
$ups = $usts[0]+1;
$upl = $usts[1]+1;
mysql_query("UPDATE fun_users SET posts='".$ups."', plusses='".$upl."' WHERE id='".$usuario_id."'");
$tnm = TextoGeral($ntitle);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico <b>$tnm</b> criado com sucesso";
$tid = mysql_fetch_array(mysql_query("SELECT id FROM fun_topics WHERE name='".$ntitle."' AND fid='".$fid."'"));
echo "<br/><br/><a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid[0]\">";
echo "Ver Topico</a>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}else{
$af = $config['ANTI_REPETICAO_TOPICO'] -$antiflood;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Controle de AntiFlood: $af";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico não existe";
}
$fname = GeraNomeForum($fid);
echo "<br/><br/><a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="post")
{
$tid = $_POST["tid"];
$tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
if(!AcessoAoForum(GeraID($token), $tfid[0]))
{
echo "<p align=\"center\">";
echo "Você não tem permissão para ler o conteúdo deste Fórum<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
$reptxt = $_POST["reptxt"];
$qut = $_POST["qut"];
AdicionarOnline(GeraID($token),"Respondendo Topico","");
echo "<p align=\"center\">";
$crdate = time();
$fid = GeraForumID($tid);
//$usuario_id = GeraID($token);
$res = false;
$closed = mysql_fetch_array(mysql_query("SELECT closed FROM fun_topics WHERE id='".$tid."'"));
if(($closed[0]!='1')||(Moderador($usuario_id)))
{
$lpost = mysql_fetch_array(mysql_query("SELECT dtpost FROM fun_posts WHERE uid='".$usuario_id."' ORDER BY dtpost DESC LIMIT 1"));
global $config;
$antiflood = time()-$lpost[0];
if($antiflood>$config['ANTI_REPETICAO_POSTAGEM'])
{
if(trim($reptxt)!="")
{
$res = mysql_query("INSERT INTO fun_posts SET text='".$reptxt."', tid='".$tid."', uid='".$usuario_id."', dtpost='".$crdate."', quote='".$qut."'");
}
if($res)
{
$usts = mysql_fetch_array(mysql_query("SELECT posts, plusses FROM fun_users WHERE id='".$usuario_id."'"));
$ups = $usts[0]+1;
$upl = $usts[1]+1;
mysql_query("UPDATE fun_users SET posts='".$ups."', plusses='".$upl."' WHERE id='".$usuario_id."'");
mysql_query("UPDATE fun_topics SET lastpost='".$crdate."' WHERE id='".$tid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagem postada com sucesso";
echo "<br/><br/><a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;go=last\">";
echo "Ver Topico/a>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}else{
$af = $config['ANTI_REPETICAO_POSTAGEM'] -$antiflood;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Controle AntiFlood: $af";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico Fechado";
}
$fname = GeraNomeForum($fid);
echo "<br/><br/><a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="uadd")
{
$ucon = $_POST["ucon"];
$ucit = $_POST["ucit"];
$ustr = $_POST["ustr"];
$utzn = $_POST["utzn"];
$uphn = $_POST["uphn"];
AdicionarOnline(GeraID($token),"My Address","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$usuario_id."'"));
if($exs[0]>0)
{
$res = mysql_query("UPDATE fun_xinfo SET country='".$ucon."', city='".$ucit."', street='".$ustr."', timezone='".$utzn."', phoneno='".$uphn."' WHERE uid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Endereço atualizado com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}else{
$res = mysql_query("INSERT INTO fun_xinfo SET uid='".$usuario_id."', country='".$ucon."', city='".$ucit."', street='".$ustr."', timezone='".$utzn."', phoneno='".$uphn."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Endereço atualizado com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configurações</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="gcp")
{
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
$giv = $_POST["giv"];
$pnt = $_POST["pnt"];
AdicionarOnline(GeraID($token),"Gerenciando Comunidade","");
echo "<p align=\"center\">";
$whnick = GeraNickUsuario($usuario);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
$mpt = mysql_fetch_array(mysql_query("SELECT points FROM fun_clubmembers WHERE uid='".$usuario."' AND clid='".$clid."'"));
if($giv=="1")
{
$pnt = $mpt[0]+$pnt;
}else{
$pnt = $mpt[0]-$pnt;
if($pnt<0)$pnt=0;
}
$res = mysql_query("UPDATE fun_clubmembers SET points='".$pnt."' WHERE uid='".$usuario."' AND clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Pontos de comunidade atualizado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Sem Informação!!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="gpl")
{
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
$pnt = $_POST["pnt"];
AdicionarOnline(GeraID($token),"Gerenciando Comunidade","");
echo "<p align=\"center\">";
$whnick = GeraNickUsuario($usuario);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você deve manerar nos pontos de comunidade, pois eles seram debitados dos pontos do seu perfil";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="upre")
{
$usds = $_POST["usds"];
$usds = str_replace('"', "", $usds);
$usds = str_replace("'", "", $usds);
$ubon = $_POST["ubon"];
$usxp = $_POST["usxp"];
AdicionarOnline(GeraID($token),"Preferências","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$usuario_id."'"));
if($exs[0]>0)
{
$res = mysql_query("UPDATE fun_xinfo SET sitedscr='".$usds."', amigosonly='".$ubon."', sexpre='".$usxp."' WHERE uid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Preferências atualizadas com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}else{
$res = mysql_query("INSERT INTO fun_xinfo SET uid='".$usuario_id."', sitedscr='".$usds."', amigosonly='".$ubon."', sexpre='".$usxp."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Preferências atualizadas com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configurações</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="gmset")
{
$ugun = $_POST["ugun"];
$ugpw = $_POST["ugpw"];
$ugch = $_POST["ugch"];
AdicionarOnline(GeraID($token),"Configurações G-Mail","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$usuario_id."'"));
if($exs[0]>0)
{
$res = mysql_query("UPDATE fun_xinfo SET gmailun='".$ugun."', gmailpw='".$ugpw."', gmailchk='".$ugch."', gmaillch='".time()."' WHERE uid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Configurações do Gmail atualizado com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}else{
$res = mysql_query("INSERT INTO fun_xinfo SET uid='".$usuario_id."', gmailun='".$ugun."', gmailpw='".$ugpw."', gmailchk='".$ugch."', gmaillch='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Configurações do Gmail atualizado com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configurações</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="uper")
{
$uhig = $_POST["uhig"];
$uwgt = $_POST["uwgt"];
$urln = $_POST["urln"];
$ueor = $_POST["ueor"];
$ueys = $_POST["ueys"];
$uher = $_POST["uher"];
$upro = $_POST["upro"];
AdicionarOnline(GeraID($token),"Personalidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$usuario_id."'"));
if($exs[0]>0)
{
$res = mysql_query("UPDATE fun_xinfo SET height='".$uhig."', weight='".$uwgt."', realname='".$urln."', eyescolor='".$ueys."', profession='".$upro."', racerel='".$ueor."',hairtype='".$uher."'  WHERE uid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Informações pessoais atualizados com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}else{
$res = mysql_query("INSERT INTO fun_xinfo SET uid='".$usuario_id."', height='".$uhig."', weight='".$uwgt."', realname='".$urln."', eyescolor='".$ueys."', profession='".$upro."', racerel='".$ueor."',hairtype='".$uher."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Informações pessoais atualizados com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configurações</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="umin")
{
$ulik = $_POST["ulik"];
$ulik = str_replace('"', "", $ulik);
$ulik = str_replace("'", "", $ulik);
$udlk = $_POST["udlk"];
$udlk = str_replace('"', "", $udlk);
$udlk = str_replace("'", "", $udlk);
$ubht = $_POST["ubht"];
$ubht = str_replace('"', "", $ubht);
$ubht = str_replace("'", "", $ubht);
$ught = $_POST["ught"];
$ught = str_replace('"', "", $ught);
$ught = str_replace("'", "", $ught);
$ufsp = $_POST["ufsp"];
$ufsp = str_replace('"', "", $ufsp);
$ufsp = str_replace("'", "", $ufsp);
$ufmc = $_POST["ufmc"];
$ufmc = str_replace('"', "", $ufmc);
$ufmc = str_replace("'", "", $ufmc);
$umtx = $_POST["umtx"];
$umtx = str_replace('"', "", $umtx);
$umtx = str_replace("'", "", $umtx);
AdicionarOnline(GeraID($token),"Mais sobre mim","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$usuario_id."'"));
if($exs[0]>0)
{
$res = mysql_query("UPDATE fun_xinfo SET likes='".$ulik."', deslikes='".$udlk."', habitsb='".$ubht."', habitsg='".$ught."', favsport='".$ufsp."', favmusic='".$ufmc."',moretext='".$umtx."'  WHERE uid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Informações atualizadas com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}else{
$res = mysql_query("INSERT INTO fun_xinfo SET uid='".$usuario_id."', likes='".$ulik."', deslikes='".$udlk."', habitsb='".$ubht."', habitsg='".$ught."', favsport='".$ufsp."', favmusic='".$ufmc."',moretext='".$umtx."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Informações atualizadas com sucesso<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"O\"/>Erro!<br/><br/>";
}
}
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configurações</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="signgb")
{
$usuario = $_POST["usuario"];
if(!AcessoLivroVisita(GeraID($token), $usuario))
{
echo "<p align=\"center\">";
echo "Você não pode assinar este livro<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
$msgtxt = $_POST["msgtxt"];
//$qut = $_POST["qut"];
AdicionarOnline(GeraID($token),"Assinando Livro de Visitas","");
echo "<p align=\"center\">";
$crdate = time();
//$usuario_id = GeraID($token);
$res = false;
if(trim($msgtxt)!="")
{
$res = mysql_query("INSERT INTO fun_gbook SET gbowner='".$usuario."', gbsigner='".$usuario_id."', dtime='".$crdate."', gbmsg='".$msgtxt."'");
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagem enviada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="votepl")
{
//$usuario_id = GeraID($token);
$plid = $_GET["plid"];
$ans = $_GET["ans"];
AdicionarOnline(GeraID($token),"Votando na enquete","");
echo "<p align=\"center\">";
$voted = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$usuario_id."' AND pid='".$plid."'"));
if($voted[0]==0)
{
$res = mysql_query("INSERT INTO fun_presults SET uid='".$usuario_id."', pid='".$plid."', ans='".$ans."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Obrigado por votar!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você já votou nesta enquete";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="dlpoll")
{
//$usuario_id = GeraID($token);
AdicionarOnline(GeraID($token),"Removendo Enquete","");
echo "<p align=\"center\">";
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario_id."'"));
$res = mysql_query("UPDATE fun_users SET pollid='0' WHERE id='".$usuario_id."'");
if($res)
{
$res = mysql_query("DELETE FROM fun_presults WHERE pid='".$pid[0]."'");
$res = mysql_query("DELETE FROM fun_pp_pres WHERE pid='".$pid[0]."'");
$res = mysql_query("DELETE FROM fun_polls WHERE id='".$pid[0]."'");
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Poll Deleted";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delan")
{
//$usuario_id = GeraID($token);
AdicionarOnline(GeraID($token),"Removendo Anuncio","");
$clid = $_GET["clid"];
$anid = $_GET["anid"];
$usuario_id = GeraID($token);
echo "<p align=\"center\">";
$pid = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE id='".$anid."' AND clid='".$clid."'"));
if(($usuario_id==$pid[0])&&($exs[0]>0))
{
$res = mysql_query("DELETE FROM fun_announcements WHERE id='".$anid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Anuncio removido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode remover este anuncio!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="dlcl")
{
//$usuario_id = GeraID($token);
AdicionarOnline(GeraID($token),"Removendo Comunidade","");
$clid = $_GET["clid"];
$usuario_id = GeraID($token);
echo "<p align=\"center\">";
$pid = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
if($usuario_id==$pid[0])
{
$res = RemoverComunidade($clid);
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Comunidade Removida";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode remover está comunidade!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="dltpl")
{
//$usuario_id = GeraID($token);
$tid = $_GET["tid"];
AdicionarOnline(GeraID($token),"Removendo Comunidade","");
echo "<p align=\"center\">";
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
$res = mysql_query("UPDATE fun_topics SET pollid='0' WHERE id='".$tid."'");
if($res)
{
$res = mysql_query("DELETE FROM fun_presults WHERE pid='".$pid[0]."'");
$res = mysql_query("DELETE FROM fun_polls WHERE id='".$pid[0]."'");
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Poll Deleted";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="reqjc")
{
//$usuario_id = GeraID($token);
$clid = $_GET["clid"];
AdicionarOnline(GeraID($token),"Entrando em Comunidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$isin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario_id."' AND clid='".$clid."'"));
if($isin[0]==0){
$res = mysql_query("INSERT INTO fun_clubmembers SET uid='".$usuario_id."', clid='".$clid."', accepted='0', points='0', joined='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Pedido enviado! Agora aguarde seu pedido ser aceito pelo dono da comunidade";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você já fez um pedido de participação para está comunidade";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="unjc")
{
//$usuario_id = GeraID($token);
$clid = $_GET["clid"];
AdicionarOnline(GeraID($token),"Saindo de Comunidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$isin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario_id."' AND clid='".$clid."'"));
if($isin[0]>0){
$res = mysql_query("DELETE FROM fun_clubmembers WHERE uid='".$usuario_id."' AND clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Você deixou a comunidade";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não faz parte desta comunidade";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="acm")
{
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Adicionando Usuario a comunidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
if($cowner[0]==$usuario_id){
$res = mysql_query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."' AND uid='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Usuario adicionado a comunidade";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não e dono desta comunidade";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="accall")
{
//$usuario_id = GeraID($token);
$clid = $_GET["clid"];
AdicionarOnline(GeraID($token),"Adding a member to club","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
if($cowner[0]==$usuario_id){
$res = mysql_query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Todos Membros Foram aceitos";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Está comunidade não e sua!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="denall")
{
//$usuario_id = GeraID($token);
$clid = $_GET["clid"];
AdicionarOnline(GeraID($token),"Adicionando Usuario a comunidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
if($cowner[0]==$usuario_id){
$res = mysql_query("DELETE FROM fun_clubmembers WHERE accepted='0' AND clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Todas Solicitações foram negadas";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Está Comunidade não e sua!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="dcm")
{
//$usuario_id = GeraID($token);
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Removendo Usuario da Comunidade","");
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
$cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
if($cowner[0]==$usuario_id){
$res = mysql_query("DELETE FROM fun_clubmembers  WHERE clid='".$clid."' AND uid='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Usuario removido com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Está Comunidade nao e sua!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="crpoll")
{
AdicionarOnline(GeraID($token),"Criando Enquete","");
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
if(GeraPontos(GeraID($token))>=50)
{
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario_id."'"));
if($pid[0] == 0)
{
$pques = $_POST["pques"];
$opt1 = $_POST["opt1"];
$opt2 = $_POST["opt2"];
$opt3 = $_POST["opt3"];
$opt4 = $_POST["opt4"];
$opt5 = $_POST["opt5"];
if((trim($pques)!="")&&(trim($opt1)!="")&&(trim($opt2)!=""))
{
$pex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pqst LIKE '".$pques."'"));
if($pex[0]==0)
{
$res = mysql_query("INSERT INTO fun_polls SET pqst='".$pques."', opt1='".$opt1."', opt2='".$opt2."', opt3='".$opt3."', opt4='".$opt4."', opt5='".$opt5."', pdt='".time()."'");
if($res)
{
$pollid = mysql_fetch_array(mysql_query("SELECT id FROM fun_polls WHERE pqst='".$pques."' "));
mysql_query("UPDATE fun_users SET pollid='".$pollid[0]."' WHERE id='".$usuario_id."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Enquete criada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Eroo!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Já existe uma enquete com este tema";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>A enquete deve ter uma pergunta , e pelo menos 2 respostas";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você já tem uma enquete";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você deve ter pelo menos 50 pontos para criar uma enquete";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Incicial</a>";
echo "</p>";
}
else if($menu=="pltpc")
{
$tid = $_GET["tid"];
AdicionarOnline(GeraID($token),"Criando Enquete","");
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
if((GeraPontos(GeraID($token))>=500)||Moderador($usuario_id))
{
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
if($pid[0] == 0)
{
$pques = $_POST["pques"];
$opt1 = $_POST["opt1"];
$opt2 = $_POST["opt2"];
$opt3 = $_POST["opt3"];
$opt4 = $_POST["opt4"];
$opt5 = $_POST["opt5"];
if((trim($pques)!="")&&(trim($opt1)!="")&&(trim($opt2)!=""))
{
$pex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pqst LIKE '".$pques."'"));
if($pex[0]==0)
{
$res = mysql_query("INSERT INTO fun_polls SET pqst='".$pques."', opt1='".$opt1."', opt2='".$opt2."', opt3='".$opt3."', opt4='".$opt4."', opt5='".$opt5."', pdt='".time()."'");
if($res)
{
$pollid = mysql_fetch_array(mysql_query("SELECT id FROM fun_polls WHERE pqst='".$pques."' "));
mysql_query("UPDATE fun_topics SET pollid='".$pollid[0]."' WHERE id='".$tid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sua Enquete foi criada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Já há uma enquete com a mesma pergunta";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>A enquete deve ter uma pergunta , e pelo menos 2 respostas";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Este Tópico Já tem uma enquete";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você deve ter pelo menos 500 pontos para criar uma enquete";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addblg")
{
if(!GeraPontos(GeraID($token))>50)
{
echo "<p align=\"center\">";
echo "Você Precisa de mais de 50 pontos para criar uma pagina pessoal<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
$msgtxt = $_POST["btitle"];
$msgtxt = $_POST["msgtxt"];
//$qut = $_POST["qut"];
AdicionarOnline(GeraID($token),"Criando pagina pessoal","");
echo "<p align=\"center\">";
$crdate = time();
//$usuario_id = GeraID($token);
$res = false;
if((trim($msgtxt)!="")&&(trim($btitle)!=""))
{
$res = mysql_query("INSERT INTO fun_blogs SET bowner='".$usuario_id."', bname='".$btitle."', bgdate='".$crdate."', btext='".$msgtxt."'");
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagem enviada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addvlt")
{
if(!GeraPontos(GeraID($token))>24)
{
echo "<p align=\"center\">";
echo "Only 25+ plusses can add a downloads item<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
$viname = $_POST["viname"];
$vilink = $_POST["vilink"];
//$qut = $_POST["qut"];
AdicionarOnline(GeraID($token),"Adicionando Arquivo em Downloads","");
echo "<p align=\"center\">";
$crdate = time();
//$usuario_id = GeraID($token);
$res = false;
if((trim($vilink)!="")&&(trim($viname)!=""))
{
$res = mysql_query("INSERT INTO fun_downloads SET uid='".$usuario_id."', title='".LimpaTexto($viname)."', pudt='".$crdate."', itemurl='".$vilink."'");
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Arquivo Adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////shout

//////////////////////////////////////////Announce
else if($menu=="annc")
{
$antx = $_POST["antx"];
$clid = $_GET["clid"];
AdicionarOnline(GeraID($token),"Anunciando na Comunidade","");
$cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
$usuario_id = GeraID($token);
echo "<p align=\"center\">";
if($cow[0]!=$usuario_id)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Esta Comunidade não e sua!";
}else{
$shtxt = $shtxt;
//$usuario_id = GeraID($token);
$shtm = time();
$res = mysql_query("INSERT INTO fun_announcements SET antext='".$antx."', clid='".$clid."', antime='".$shtm."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Anuncio Adicionado com Sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="rateb")
{
$brate = $_POST["brate"];
$bid = $_GET["bid"];
AdicionarOnline(GeraID($token),"Votando na Pagina Pessoal","");
//$usuario_id = GeraID($token);
echo "<p align=\"center\">";
$vb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='".$usuario_id."' AND blogid='".$bid."'"));
if($vb[0]==0)
{
$res = mysql_query("INSERT INTO fun_brate SET uid='".$usuario_id."', blogid='".$bid."', brate='".$brate."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Voto Enviado com Sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você Já votou nesta Pagina Pessoal<br/>";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delfgb")
{
$mid = $_GET["mid"];
AdicionarOnline(GeraID($token),"Removendo Mensagem do Livro de Visitas","");
echo "<p align=\"center\">";
if(PermissaoLivro(GeraID($token), $mid))
{
$res = mysql_query("DELETE FROM fun_gbook WHERE id='".$mid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Mensagem removida com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode excluir esta mensagem";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delvlt")
{
$vid = $_GET["vid"];
AdicionarOnline(GeraID($token),"Removendo Download","");
echo "<p align=\"center\">";
$itemowner = mysql_fetch_array(mysql_query("SELECT uid FROM fun_downloads WHERE id='".$vid."'"));
if(Moderador(GeraID($token))||GeraID($token)==$itemowner[0])
{
$res = mysql_query("DELETE FROM fun_downloads WHERE id='".$vid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Arquivo removido dos downlaods<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode excluir este item";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delbl")
{
$bid = $_GET["bid"];
AdicionarOnline(GeraID($token),"Removendo Pagina Pessoal","");
echo "<p align=\"center\">";
if(PermissaoPaginaPessoal(GeraID($token), $bid))
{
$res = mysql_query("DELETE FROM fun_blogs WHERE id='".$bid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Pagina Pessoal Removida<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Está Pagina pessoal não e sua";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="rpost")
{
$pid = $_GET["pid"];
AdicionarOnline(GeraID($token),"Reportando Postagem","");
echo "<p align=\"center\">";
$pinfo = mysql_fetch_array(mysql_query("SELECT reported FROM fun_posts WHERE id='".$pid."'"));
if($pinfo[0]=="0")
{
$str = mysql_query("UPDATE fun_posts SET reported='1' WHERE id='".$pid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem reportada para equipe";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode reportar está postagem";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Está postagem já foi reportada";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="rtpc")
{
$tid = $_GET["tid"];
AdicionarOnline(GeraID($token),"Reportando topico","");
echo "<p align=\"center\">";
$pinfo = mysql_fetch_array(mysql_query("SELECT reported FROM fun_topics WHERE id='".$tid."'"));
if($pinfo[0]=="0")
{
$str = mysql_query("UPDATE fun_topics SET reported='1' WHERE id='".$tid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico reportado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode reportar este Topico!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Este Topico já foi reportado!";
}
echo "<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="bud")
{
$todo = $_GET["todo"];
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Gerenciando Amigos","");
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$unick = GeraNickUsuario($usuario_id);
$tnick = GeraNickUsuario($usuario);
if($todo=="add")
{
if(SolicitacaoAmizade($usuario_id,$usuario)!=3){
if(VerificaAmizade($usuario_id,$usuario))
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>$tnick já faz parte da sua lista de amigos<br/>";
}else if(SolicitacaoAmizade($usuario_id, $usuario)==0)
{
$res = mysql_query("INSERT INTO fun_buddies SET uid='".$usuario_id."', tid='".$usuario."', reqdt='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Solicitação enviada para $tnick<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick como amigos<br/>";
}
}
else if(SolicitacaoAmizade($usuario_id, $usuario)==1)
{
$res = mysql_query("UPDATE fun_buddies SET agreed='1' WHERE uid='".$usuario."' AND tid='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick adicionado a sua lista de amigos com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick para sua lista de amigos<br/>";
}
}
else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick para sua lista de amigos<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick para sua lista de amigos<br/>";
}
}else if($todo="del")
{
$res= mysql_query("DELETE FROM fun_buddies WHERE (uid='".$usuario_id."' AND tid='".$usuario."') OR (uid='".$usuario."' AND tid='".$usuario_id."')");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick removido da sua lista de amigos<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Não é possível remover $tnick de sua lista de amigos<br/>";
}
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Update buddy message
else if($menu=="upbmsg")
{
AdicionarOnline(GeraID($token),"Atualizando Mensagem da Lista de Amigos","");
$bmsg = $_POST["bmsg"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$res = mysql_query("UPDATE fun_users SET budmsg='".$bmsg."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Mensagem da lista de amigos atualizada com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Não é possível atualizar a sua mensagem<br/>";
}
echo "<br/>";
echo "<a href=\"lists.php?menu=amigos&amp;token=$token\">";
echo "Lista de Amigos</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Select Avatar
else if($menu=="upav")
{
AdicionarOnline(GeraID($token),"Atualizando Avatar","");
$avid = $_GET["avid"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$avlnk = mysql_fetch_array(mysql_query("SELECT avlink FROM fun_avatars WHERE id='".$avid."'"));
$res = mysql_query("UPDATE fun_users SET avatar='".$avlnk[0]."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Avatar Selecionado<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
echo "<br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Select Avatar
else if($menu=="upcm")
{
AdicionarOnline(GeraID($token),"Atualizando Sala de Chat","");
$cmid = $_GET["cmid"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$res = mysql_query("UPDATE fun_users SET chmood='".$cmid."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Modificação feita com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
echo "<br/>";
echo "<a href=\"sistema.php?menu=chat&amp;token=$token\">";
echo "Salas de Chat</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Give GPs
else if($menu=="givegp")
{
AdicionarOnline(GeraID($token),"Atualizando Pontos de Jogo","");
$usuario = $_GET["usuario"];
$ptg = $_POST["ptg"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$gpsf = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$usuario_id."'"));
$gpst = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$usuario."'"));
if($gpsf[0]>=$ptg){
$gpsf = $gpsf[0]-$ptg;
$gpst = $gpst[0]+$ptg;
$res = mysql_query("UPDATE fun_users SET gplus='".$gpst."' WHERE id='".$usuario."'");
if($res)
{
$res = mysql_query("UPDATE fun_users SET gplus='".$gpsf."' WHERE id='".$usuario_id."'");
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Pontos de Jogo atualizado<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode atualizar os pontos de jogo<br/>";
}
echo "<br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial </a>";
echo "</p>";
}
//////////////////// add club
else if($menu=="addcl")
{
AdicionarOnline(GeraID($token),"Adicionando Comunidade","");
$clnm = trim($_POST["clnm"]);
$clnm = str_replace("$", "", $clnm);
$clds = trim($_POST["clds"]);
$clds = str_replace("$", "", $clds);
$clrl = trim($_POST["clrl"]);
$clrl = str_replace("$", "", $clrl);
$cllg = trim($_POST["cllg"]);
$cllg = str_replace("$", "", $cllg);
echo "<p align=\"center\">";
$usuario_id = GeraID($token);
if(GeraPontos($usuario_id)>=500)
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."'"));
if($noi[0]<3)
{
if(($clnm=="")||($clds=="")||($clrl==""))
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Por favor, não se esqueça de preencher,nome da comunidade, descrição e regras";
}else{
$nmex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE name LIKE '".$clnm."'"));
if($nmex[0]>0)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Nome da comunidade já existe";
}else{
$res = mysql_query("INSERT INTO fun_clubs SET name='".$clnm."', owner='".$usuario_id."', description='".$clds."', rules='".$clrl."', logo='".$cllg."', plusses='0', created='".time()."'");
if($res)
{
$clid = mysql_fetch_array(mysql_query("SELECT id FROM fun_clubs WHERE owner='".$usuario_id."' AND name='".$clnm."'"));
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Parabéns! você tem o sua propria comunidade, as suas próprias regras, fórum, sala de chat, anúncios , Você tambem ganhou 50 pontos de comunidade para atividades junto aos usuarios.";
mysql_query("INSERT INTO fun_clubmembers SET uid='".$usuario_id."', clid='".$clid[0]."', accepted='1', points='50', joined='".time()."'");
//$ups = GeraPontos($usuario_id);
//$ups += 5;
//mysql_query("UPDATE fun_users SET plusses='".$ups."' WHERE id='".$usuario_id."'");
$fnm = $clnm;
$cnm = $clnm;
mysql_query("INSERT INTO fun_forums SET name='".$fnm."', position='0', cid='0', clubid='".$clid[0]."'");
mysql_query("INSERT INTO fun_rooms SET name='".$cnm."', pass='', static='1', mage='0', chposts='0', perms='0', censord='0', freaky='0', lastmsg='".time()."', clubid='".$clid[0]."'");
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro!";
}
}
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você já tem 3 comunidades";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode adicionar novas comunidades";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Give GPs
else if($menu=="batp")
{
AdicionarOnline(GeraID($token),"Adicionando Pontos de Jogo","");
$usuario = $_GET["usuario"];
$ptg = $_POST["ptbp"];
$giv = $_POST["giv"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".GeraID($token)."'"));
$gpst = mysql_fetch_array(mysql_query("SELECT battlep FROM fun_users WHERE id='".$usuario."'"));
if(Moderador(GeraID($token))||$judg[0]>0)
{
if ($giv=="1")
{
$gpst = $gpst[0]+$ptg;
}else{
$gpst = $gpst[0]-$ptg;
if($gpst<0)$gpst=0;
}
$res = mysql_query("UPDATE fun_users SET battlep='".$gpst."' WHERE id='".$usuario."'");
if($res)
{
$vnick = GeraNickUsuario($usuario);
if ($giv=="1")
{
$ms1 = " Adicionado $ptg pontos para ";
}else{
$ms1 = " Removido $ptg pontos para ";
}
mysql_query("INSERT INTO fun_mlog SET menu='bpoints', details='<b>".GeraNickUsuario(GeraID($token))."</b> $ms1  $vnick', actdt='".time()."'");
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Pontos de Jogo foram atualizados<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Ação não permitida<br/>";
}
echo "<br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////Add remove from ignoire list
else if($menu=="ign")
{
AdicionarOnline(GeraID($token),"Atualizando Lista de Bloquiados","");
$todo = $_GET["todo"];
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$tnick = GeraNickUsuario($usuario);
if($todo=="add")
{
if(Ignorado($usuario_id, $usuario)==1)
{
$res= mysql_query("INSERT INTO fun_ignore SET name='".$usuario_id."', target='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick foi adicionado com sucesso à sua lista de Bloquiados<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick à sua lista de Bloquiados<br/>";
}
}else if($todo="del")
{
if(Ignorado($usuario_id, $usuario)==2)
{
$res= mysql_query("DELETE FROM fun_ignore WHERE name='".$usuario_id."' AND target='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick foi excluído com sucesso da lista de Bloquiados<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>$tnick não foi bloquiado por você<br/>";
}
}
echo "<br/><a href=\"lists.php?menu=ignl&amp;token=$token\">";
echo "Lista de Bloquiados</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Update profile
else if($menu=="uprof")
{
AdicionarOnline(GeraID($token),"Atualizando Configurações","");
$savat = $_POST["savat"];
$semail = $_POST["semail"];
$usite = $_POST["usite"];
$ubday = $_POST["ubday"];
$uloc = $_POST["uloc"];
$usig = $_POST["usig"];
$usex = $_POST["usex"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Seu perfil foi atualizado com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
echo "<br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Update profile
else if($menu=="shsml")
{
AdicionarOnline(GeraID($token),"Atualizando Smilies","");
$act = $_GET["act"];
$acts = ($act=="dis" ? 0 : 1);
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
$res = mysql_query("UPDATE fun_users SET hvia='".$acts."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Visualização de Smilies atualizada com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
echo "<br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Change Password
else if($menu=="upwd")
{
AdicionarOnline(GeraID($token),"Configurações de atualização","");
$npwd = $_POST["npwd"];
$cpwd = $_POST["cpwd"];
echo "<p align=\"center\">";
//$usuario_id = GeraID($token);
if($npwd!=$cpwd)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>A Sua Senha nova e a senha de confirmação nao conferem<br/>";
}else if((strlen($npwd)<4) || (strlen($npwd)>15)){
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Sua senha deve ter no minimo 4 caracteres.<br/>";
}else{
$pwd = md5($npwd);
$res = mysql_query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$usuario_id."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Sua senha foi atualizada com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}
echo "<br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
echo "<p align=\"center\">";
echo "A página solicitada não existe mais<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>