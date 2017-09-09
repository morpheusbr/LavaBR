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
VerificaAdmin(); AdicionarOnline(GeraID($token),"Administrando Site","");
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
}else if($menu=="general")
{
$xtm = $_POST["sesp"];
$fmsg = $_POST["fmsg"];
$areg = $_POST["areg"];
$pmaf = $_POST["pmaf"];
$fvw = $_POST["fvw"];
if($areg=="d")
{
$arv = 0;
}else{
$arv = 1;
}
echo "<p align=\"center\">";
$res = mysql_query("UPDATE fun_settings SET value='".$fmsg."' WHERE name='4ummsg'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagem Fixa Atualizada com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao atualizar mensagem fixa<br/>";
}
$res = mysql_query("UPDATE fun_settings SET value='".$xtm."' WHERE name='sesxp'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Período da sessão foram atualizados com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao atualizar sessão<br/>";
}
$res = mysql_query("UPDATE fun_settings SET value='".$pmaf."' WHERE name='pmaf'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Antiflood das mensagens privadas foram atualizadas para $pmaf segundos<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao atualizar Antiflood<br/>";
}
$res = mysql_query("UPDATE fun_settings SET value='".$arv."' WHERE name='reg'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Registro foi atualizado com sucesso<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao atualizar registro<br/>";
}
$res = mysql_query("UPDATE fun_settings SET value='".$fvw."' WHERE name='fview'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Visão do forum foi modificada<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao modificar visão do forum<br/>";
}
echo "<br/>";
echo "<a href=\"painel_admin.php?menu=general&amp;token=$token\">";
echo "Editar Configurações Gerais</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel do Administrador</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////Add moderating
else if($menu=="addfmod")
{
$mid = $_POST["mid"];
$fid = $_POST["fid"];
echo "<p align=\"center\">";
$res = mysql_query("INSERT INTO fun_modr SET name='".$mid."', forum='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Privilégios modificados<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados<br/>";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=manmods&amp;token=$token\">";
echo "Modificar Moderadores</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel do Administrador</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delclub")
{
$clid = $_GET["clid"];
echo "<p align=\"center\">";
$res = RemoverComunidade($clid);
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Comunidade removida<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados <br/>";
}
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}
else if($menu=="gccp")
{
$clid = $_GET["clid"];
$plss = $_POST["plss"];
echo "<p align=\"center\">";
$nop = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_clubs WHERE id='".$clid."'"));
$newpl = $nop[0] + $plss;
$res = mysql_query("UPDATE fun_clubs SET plusses='".$newpl."' WHERE id='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Pontos de comunidade foram atualizados<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados<br/>";
}
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel do Administrador</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delfmod")
{
$mid = $_POST["mid"];
$fid = $_POST["fid"];
echo "<p align=\"center\">";
$res = mysql_query("DELETE FROM fun_modr WHERE name='".$mid."' AND forum='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Modificação do Moderador <br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados<br/>";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=manmods&amp;token=$token\">";
echo "Modificar Moderadores</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////////
else if($menu=="addcat")
{
$fcname = $_POST["fcname"];
$fcpos = $_POST["fcpos"];
echo "<p align=\"center\">";
echo $fcname;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_fcats SET name='".$fcname."', position='".$fcpos."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria de forum adicionada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar categoria";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categoria do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addfrm")
{
$frname = $_POST["frname"];
$frpos = $_POST["frpos"];
$fcid = $_POST["fcid"];
echo "<p align=\"center\">";
echo $frname;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar Forum";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Fóruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addsml")
{
$smlcde = $_POST["smlcde"];
$smlsrc = $_POST["smlsrc"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("INSERT INTO fun_smilies SET scode='".$smlcde."', imgsrc='".$smlsrc."', hidden='0'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Smilie adicionado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar smilie";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=addsml&amp;token=$token\">";
echo "Adicionar Smilie</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addavt")
{
$avtsrc = $_POST["avtsrc"];
echo "<p align=\"center\">";
echo "Fonte: ".$avtsrc;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_avatars SET avlink='".$avtsrc."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Avatar adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar avatar";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=addavt&amp;token=$token\">";
echo "Adicionar Avatar</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addjdg")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("INSERT INTO fun_judges SET uid='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>juiz de jogo adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar juiz de Jogo";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informação do usuário</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="deljdg")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_judges WHERE uid='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Juiz removido com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao remover Juiz ";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuário</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delsm")
{
$smid = $_GET["smid"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_smilies WHERE id='".$smid."'");
if($res)
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Smilie removido com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Erro ao remover Smilie</div>";
}
}
else if($menu=="addrss")
{
$rssnm = $_POST["rssnm"];
$rsslnk = $_POST["rsslnk"];
$rssimg = $_POST["rssimg"];
$rssdsc = $_POST["rssdsc"];
$fid = $_POST["fid"];
echo "<p align=\"center\">";
echo $rssnm;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_rss SET title='".$rssnm."', link='".$rsslnk."', imgsrc='".$rssimg."', dscr='".$rssdsc."', fid='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Fonte de notícia adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=manrss&amp;token=$token\">";
echo "Gerenciar fontes RSS</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addchr")
{
$chrnm = $_POST["chrnm"];
$chrage = $_POST["chrage"];
$chrpst = $_POST["chrpst"];
$chrprm = $_POST["chrprm"];
$chrcns = $_POST["chrcns"];
$chrfun = $_POST["chrfun"];
echo "<p align=\"center\">";
echo $chrnm;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_rooms SET name='".$chrnm."', static='1', pass='', mage='".$chrage."', chposts='".$chrpst."', perms='".$chrprm."', censord='".$chrcns."' , freaky='".$chrfun."'");
echo mysql_error();
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sala de chat adicionada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chrooms&amp;token=$token\">";
echo "Salas de Chat</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtrss")
{
$rssnm = $_POST["rssnm"];
$rsslnk = $_POST["rsslnk"];
$rssimg = $_POST["rssimg"];
$rssdsc = $_POST["rssdsc"];
$fid = $_POST["fid"];
$rstoken = $_POST["rstoken"];
echo "<p align=\"center\">";
echo $rssnm;
echo "<br/>";
$res = mysql_query("UPDATE fun_rss SET title='".$rssnm."', link='".$rsslnk."', imgsrc='".$rssimg."', dscr='".$rssdsc."', fid='".$fid."' WHERE id='".$rstoken."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Fonte de Notícia atualizado";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=manrss&amp;token=$token\">";
echo "Gerenciar RSS</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addperm")
{
$fid = $_POST["fid"];
$gid = $_POST["gid"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("INSERT INTO fun_acc SET fid='".$fid."', gid='".$gid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Permissão adicionada com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=addperm&amp;token=$token\">";
echo "Adicionar Permissões</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Update profile
else if($menu=="uprof")
{
$usuario = $_GET["usuario"];
$unick = $_POST["unick"];
$perm = $_POST["perm"];
$savat = $_POST["savat"];
$semail = $_POST["semail"];
$usite = $_POST["usite"];
$ubday = $_POST["ubday"];
$uloc = $_POST["uloc"];
$usig = $_POST["usig"];
$usex = $_POST["usex"];
echo "<p align=\"center\">";
$onk = mysql_fetch_array(mysql_query("SELECT name FROM fun_users WHERE id='".$usuario."'"));
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE name='".$unick."'"));
if($onk[0]!=$unick)
{
if($exs[0]>0)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Este Nick já existe , escolha outro<br/>";
}else
{
$res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Perfil de $unick foi atualizado.<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}
}else
{
$res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Perfil de $unick foi atualizado.<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}
echo "<br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Users Info</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}
/////////////user password
else if($menu=="upwd")
{
$npwd = $_POST["npwd"];
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
if((strlen($npwd)<4) || (strlen($npwd)>15)){
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Senha deve ter 4 caracteres ou mais<br/>";
}else{
$pwd = md5($npwd);
$res = mysql_query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Senha atualizada <br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro<br/>";
}
}
echo "<br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuário</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////add group
else if($menu=="addgrp")
{
$frname = $_POST["ugname"];
$ugaa = $_POST["ugaa"];
$allus = $_POST["allus"];
$mage = $_POST["mage"];
$mpst = $_POST["mpst"];
$mpls = $_POST["mpls"];
echo "<p align=\"center\">";
echo $ugname;
echo "<br/>";
$res = mysql_query("INSERT INTO fun_groups SET name='".$ugname."', autoass='".$ugaa."', userst='".$allus."', mage='".$mage."', posts='".$mpst."', plusses='".$mpls."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Grupo adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=ugroups&amp;token=$token\">";
echo "Gerenciar Grupos</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtfrm")
{
$fid = $_POST["fid"];
$frname = $_POST["frname"];
$frpos = $_POST["frpos"];
$fcid = $_POST["fcid"];
echo "<p align=\"center\">";
echo $frname;
echo "<br/>";
$res = mysql_query("UPDATE fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."' WHERE id='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum atualizado";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtcat")
{
$fcid = $_POST["fcid"];
$fcname = $_POST["fcname"];
$fcpos = $_POST["fcpos"];
echo "<p align=\"center\">";
echo $fcname;
echo "<br/>";
$res = mysql_query("UPDATE fun_fcats SET name='".$fcname."', position='".$fcpos."' WHERE id='".$fcid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria do  Forum atualizada";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categoria do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delfrm")
{
$fid = $_POST["fid"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_forums WHERE id='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum removido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delpms")
{
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_private WHERE reported!='1' AND starred='0' AND unread='0'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mensagens privadas removidas";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/> Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=clrdta&amp;token=$token\">";
echo "Limpeza da site</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="clrmlog")
{
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_mlog");
echo mysql_error();
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Registro do sistema foi limpo";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/> Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=clrdta&amp;token=$token\">";
echo "Limpeza do Sistema</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delsht")
{
echo "<p align=\"center\">";
$altm = time()-(5*24*60*60);
echo "<br/>";
$res = mysql_query("DELETE FROM fun_shouts WHERE shtime<'".$altm."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Recados dos últimos 5 dias foram removidos";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=clrdta&amp;token=$token\">";
echo "Limpeza do Sistema</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delgrp")
{
$ugid = $_POST["ugid"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_groups WHERE id='".$ugid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Grupo removido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=ugroups&amp;token=$token\">";
echo "Grupo de Usuários</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delrss")
{
$rstoken = $_POST["rstoken"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_rss WHERE id='".$rstoken."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Fonte removida";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=manrss&amp;token=$token\">";
echo "Gerenciar RSS</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delchr")
{
$chsala = $_POST["chsala"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_rooms WHERE id='".$chsala."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sala removida";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/> Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chrooms&amp;token=$token\">";
echo "Salas de Chat</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Inicial</a>";
echo "</p>";
}
else if($menu=="delu")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_buddies WHERE tid='".$usuario."' OR uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_gbook WHERE gbowner='".$usuario."' OR gbsigner='".$usuario."'");
$res = mysql_query("DELETE FROM fun_ignore WHERE name='".$usuario."' OR target='".$usuario."'");
$res = mysql_query("DELETE FROM fun_mangr WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_modr WHERE name='".$usuario."'");
$res = mysql_query("DELETE FROM fun_penalties WHERE uid='".$usuario."' OR exid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_posts WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_private WHERE byuid='".$usuario."' OR touid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_shouts WHERE shouter='".$usuario."'");
$res = mysql_query("DELETE FROM fun_topics WHERE authosala='".$usuario."'");
$res = mysql_query("DELETE FROM fun_brate WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_games WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_presults WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_downloads WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_blogs WHERE bowner='".$usuario."'");
$res = mysql_query("DELETE FROM fun_chat WHERE chatter='".$usuario."'");
$res = mysql_query("DELETE FROM fun_chat WHERE usuario='".$usuario."'");
$res = mysql_query("DELETE FROM fun_chonline WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_online WHERE usesala='".$usuario."'");
$res = mysql_query("DELETE FROM fun_ses WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_xinfo WHERE uid='".$usuario."'");
LimpaComu($usuario);
$res = mysql_query("DELETE FROM fun_users WHERE id='".$usuario."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Usuário removido ";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuário</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////// Delete users posts
else if($menu=="delxp")
{
$usuario = $_GET["usuario"];
echo "<p align=\"center\">";
echo "<br/>";
$res = mysql_query("DELETE FROM fun_posts WHERE uid='".$usuario."'");
$res = mysql_query("DELETE FROM fun_topics WHERE authosala='".$usuario."'");
if($res)
{
mysql_query("UPDATE fun_users SET plusses='0' where id='".$usuario."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagens do usuário removida";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuário</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delcat")
{
$fcid = $_POST["fcid"];
echo "<p align=\"center\">";
echo $fcname;
echo "<br/>";
$res = mysql_query("DELETE FROM fun_fcats WHERE id='".$fcid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria do Forum removido";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro";
}
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categoria do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
echo "<p align=\"center\">";
echo "A pagina solicitada não existe<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>