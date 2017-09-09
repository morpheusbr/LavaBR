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
VerificaAdmin();
AdicionarOnline(GeraID($token)," Painel do Admin","");
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
$xtm = TempoSessao();
$paf = VerificaL();
$fvw = EstiloForum();
$fmsg = TextoGeral(GeraMsgFixa());
if( Registro())
{
$arv = "e";
}else{
$arv= "d";
}
echo "<p align=\"center\">";
echo "<b>Configurações Gerais</b><br/>";
echo "</p>";
echo "<p>";
echo "<form action=\"admproc.php?menu=general&amp;token=$token\" method=\"post\">";
echo "Período de sessão: ";
echo "<input name=\"sesp\" format=\"*N\" maxlength=\"3\" size=\"3\ value=\"$xtm\"/>";
echo "<br/>Sistema Antiflood:<input name=\"pmaf\" format=\"*N\" maxlength=\"3\" size=\"3\" value=\"$paf\"/>";
echo "<br/>Mensagem da Pagina Inicial: ";
echo "<input name=\"fmsg\"  maxlength=\"255\" value=\"$fmsg\"/>";
echo "<br/>Cadastro:";
echo "<select name=\"areg\" value=\"$arv\">";
echo "<option value=\"e\">Ativado</option>";
echo "<option value=\"d\">Desativado</option>";
echo "</select><br/>";
echo "Visão do Link do Forum:";
echo "<select name=\"fvw\" value=\"$fvw\">";
$vname[0]="Varios Links";
$vname[1]="Sem Links";
for($i=0;$i<count($vname);$i++)
{
echo "<option value=\"$i\">$vname[$i]</option>";
}
echo "</select>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addperm")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Permissões</b>";
$forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?menu=addperm&amp;token=$token\" method=\"post\">";
echo "<br/><br/>Forum: <select name=\"fid\">";
while ($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select>";
$forums = mysql_query("SELECT id, name FROM fun_groups ORDER BY  name, id");
echo "<br/>Grupo de Usuarios: <select name=\"gid\">";
while ($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="fcats")
{
echo "<p>";
echo "<a href=\"painel_admin.php?menu=addcat&amp;token=$token\">&#187;Adicionar Categoria</a><br/>";
echo "<a href=\"painel_admin.php?menu=edtcat&amp;token=$token\">&#187;Editar Categoria</a><br/>";
echo "<a href=\"painel_admin.php?menu=delcat&amp;token=$token\">&#187;Remover Categoria</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="club")
{
$clid = $_GET["clid"];
echo "<p>";
echo "<a href=\"painel_admin.php?menu=gccp&amp;token=$token&amp;clid=$clid\">&#187;Dar Pontos de Comunidade</a><br/>";
echo "<a href=\"admproc.php?menu=delclub&amp;token=$token&amp;clid=$clid\">&#187;Remover Comunidade</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="manrss")
{
echo "<p>";
echo "<a href=\"painel_admin.php?menu=addrss&amp;token=$token\">&#187;Adicionar Fonte RSS</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss"));
if($noi[0]>0)
{
$rss = mysql_query("SELECT title, id FROM fun_rss");
echo "<form action=\"painel_admin.php?menu=edtrss&amp;token=$token\" method=\"post\">";
echo "<br/><select name=\"rstoken\">";
while($rs=mysql_fetch_array($rss))
{
echo "<option value=\"$rs[1]\">$rs[0]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "<br/>";
echo "</form>";
}
$noe = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss"));
if($noe[0]>0)
{
$rss1 = mysql_query("SELECT title, id FROM fun_rss");
echo "<form action=\"admproc.php?menu=delrss&amp;token=$token\" method=\"post\">";
echo "<br/><select name=\"rstoken\">";
while($rs1=mysql_fetch_array($rss1))
{
echo "<option value=\"$rs1[1]\">$rs1[0]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Remover\"/>";
echo "<br/>";
echo "</form>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="chrooms")
{
echo "<p>";
echo "<a href=\"painel_admin.php?menu=addchr&amp;token=$token\">&#187;Adicionar Sala de Chat</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rooms"));
if($noi[0]>0)
{
echo "<form action=\"admproc.php?menu=delchr&amp;token=$token\" method=\"post\">";
$rss = mysql_query("SELECT name, id FROM fun_rooms");
echo "<br/><select name=\"chsala\">";
while($rs=mysql_fetch_array($rss))
{
echo "<option value=\"$rs[1]\">$rs[0]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Remover Sala\"/>";
echo "</form>";
echo "<br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="forums")
{
echo "<p>";
echo "<a href=\"painel_admin.php?menu=addfrm&amp;token=$token\">&#187;Adicionar Forum</a><br/>";
echo "<a href=\"painel_admin.php?menu=edtfrm&amp;token=$token\">&#187;Editar Forum</a><br/>";
echo "<a href=\"painel_admin.php?menu=delfrm&amp;token=$token\">&#187;Remover Forum</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="clrdta")
{
echo "<p>";
echo "<a href=\"admproc.php?menu=delpms&amp;token=$token\">&#187;Remover Mensagens Privadas</a><br/>";
echo "<a href=\"admproc.php?menu=clrmlog&amp;token=$token\">&#187;Limpar os registros do Sistema</a><br/>";
echo "<a href=\"admproc.php?menu=delsht&amp;token=$token\">&#187;Limpar Mural de Recados</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="ugroups")
{
echo "<p>";
echo "<a href=\"painel_admin.php?menu=addgrp&amp;token=$token\">&#187;Adicionar Usuario ao Grupo</a><br/>";
echo "<a href=\"painel_admin.php?menu=edtgrp&amp;token=$token\">&#187;Editar Usuario do Grupo</a><br/>";
echo "<a href=\"painel_admin.php?menu=delgrp&amp;token=$token\">&#187;Remover Usuario do Grupo</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addcat")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Categoria no Forum</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addcat&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"fcname\" maxlength=\"30\"/><br/>";
echo "Posição:<input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categorias do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addfrm")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Forum</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addfrm&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"frname\" maxlength=\"30\"/><br/>";
echo "Posição:<input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "Categoria: <select name=\"fcid\">";
while ($fcat=mysql_fetch_array($fcats))
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="gccp")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Pontos de Comunidade</b><br/><br/>";
$clid = $_GET["clid"];
echo "<form action=\"admproc.php?menu=gccp&amp;token=$token&amp;clid=$clid\" method=\"post\">";
echo "Pontos:<input name=\"plss\" maxlength=\"3\" size=\"3\" format=\"*N\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addsml")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Smilies</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addsml&amp;token=$token\" method=\"post\">";
echo "Codigo:<input name=\"smlcde\" maxlength=\"30\"/><br/>";
echo "URL da Imagem:<input name=\"smlsrc\" maxlength=\"200\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addavt")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Avatar</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addavt&amp;token=$token\" method=\"post\">";
echo "URL do Avatar:<input name=\"avtsrc\" maxlength=\"30\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addrss")
{
echo "<p align=\"center\">";
echo "<b>Adicionar RSS</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addrss&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"rssnm\" maxlength=\"50\"/><br/>";
echo "Fonte:<input name=\"rsslnk\" maxlength=\"255\"/><br/>";
echo "Imagem:<input name=\"rssimg\" maxlength=\"255\"/><br/>";
echo "Descrição:<input name=\"rssdsc\"  maxlength=\"255\"/><br/>";
$forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
echo "Forum: <select name=\"fid\">";
echo "<option value=\"0\">Sem Forum</option>";
while ($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=manrss&amp;token=$token\">";
echo "<img src=\"images/rss.gif\" alt=\"rss\"/>Gerenciar RSS</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addchr")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Sala</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addchr&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"chrnm\" maxlength=\"30\"/><br/>";
echo "Idade Minima:<input name=\"chrage\" format=\"*N\" maxlength=\"3\" size=\"3\"/><br/>";
echo "Postagens Minimas no Forum:<input name=\"chrpst\" format=\"*N\" maxlength=\"4\" size=\"4\"/><br/>";
echo "Permissão:<select name=\"chrprm\">";
echo "<option value=\"0\">Normal</option>";
echo "<option value=\"1\">Moderadores</option>";
echo "<option value=\"2\">Administradores</option>";
echo "</select><br/>";
echo "Censura:<select name=\"chrcns\">";
echo "<option value=\"1\">Sim</option>";
echo "<option value=\"0\">Não</option>";
echo "</select><br/>";
echo "Modo:<select name=\"chrfun\">";
echo "<option value=\"0\">Normal</option>";
echo "<option value=\"1\">Diversão</option>";
echo "<option value=\"2\">Intel. Artificial</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "<form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=chrooms&amp;token=$token\">";
echo "<img src=\"images/chat.gif\" alt=\"chat\"/>Salas de Chat</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtrss")
{
$rstoken = $_POST["rstoken"];
$rsinfo = mysql_fetch_array(mysql_query("SELECT title, link, imgsrc, fid, dscr FROM fun_rss WHERE id='".$rstoken."'"));
echo "<p align=\"center\">";
echo "<b>Editar RSS</b><br/><br/>";
echo "<form action=\"admproc.php?menu=edtrss&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"rssnm\" maxlength=\"50\" value=\"$rsinfo[0]\"/><br/>";
echo "Fonte:<input name=\"rsslnk\" maxlength=\"255\" value=\"$rsinfo[1]\"/><br/>";
echo "Imagem:<input name=\"rssimg\" maxlength=\"255\" value=\"$rsinfo[2]\"/><br/>";
echo "Descrição:<input name=\"rssdsc\"  maxlength=\"255\" value=\"$rsinfo[4]\"/><br/>";
$forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
echo "Forum: <select name=\"fid\" value=\"$rsinfo[3]\">";
echo "<option value=\"0\">Sem Forum</option>";
while ($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"hidden\" name=\"rstoken\" value=\"$rstoken\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=manrss&amp;token=$token\">";
echo "<img src=\"images/rss.gif\" alt=\"rss\"/>Gerenciar RSS</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addgrp")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Grupo</b><br/><br/>";
echo "<form action=\"admproc.php?menu=addgrp&amp;token=$token\" method=\"post\">";
echo "Nome:<input name=\"ugname\" maxlength=\"30\"/><br/>";
echo "Assinatura Automatica:<select name=\"ugaa\">";
echo "<option value=\"1\">Sim</option>";
echo "<option value=\"0\">Não</option>";
echo "</select><br/>";
echo "<br/><small><b>Apenas para Assinatura Automatica</b></small><br/>";
echo "Permitir:<select name=\"allus\">";
echo "<option value=\"0\">Todos Usuarios</option>";
echo "<option value=\"1\">Moderadores</option>";
echo "<option value=\"2\">Administradores</option>";
echo "</select><br/>";
echo "Idade Minima:";
echo "<input name=\"mage\" format=\"*N\" maxlength=\"3\" size=\"3\"/>";
echo "<br/>Postagem Minima:";
echo "<input name=\"mpst\" format=\"*N\" maxlength=\"3\" size=\"3\"/>";
echo "<br/>Ponsto Minimo:";
echo "<input name=\"mpls\" format=\"*N\" maxlength=\"3\" size=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=ugroups&amp;token=$token\">";
echo "Grupo de Usuarios</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtfrm")
{
echo "<p align=\"center\">";
echo "<b>Editar Forum</b><br/><br/>";
$forums = mysql_query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?menu=edtfrm&amp;token=$token\" method=\"post\">";
echo "Forum: <select name=\"fid\">";
while($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select>";
echo "<br/>Nome:<input name=\"frname\" maxlength=\"30\"/><br/>";
echo "Posição:<input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "Category: <select name=\"fcid\">";
while ($fcat=mysql_fetch_array($fcats))
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="delfrm")
{
echo "<p align=\"center\">";
echo "<b>Remover Forum</b><br/><br/>";
$forums = mysql_query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?menu=delfrm&amp;token=$token\" method=\"post\">";
echo "Forum: <select name=\"fid\">";
while($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Remover\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}else if($menu=="delgrp")
{
echo "<p align=\"center\">";
echo "<b>Remover Grupo</b><br/><br/>";
$forums = mysql_query("SELECT id,name FROM fun_groups ORDER BY name, id");
echo "<form action=\"admproc.php?menu=delgrp&amp;token=$token\" method=\"post\">";
echo "Grupo: <select name=\"ugid\">";
while($forum=mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Remover\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=forums&amp;token=$token\">";
echo "Foruns</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="edtcat")
{
echo "<p align=\"center\">";
echo "<b>Editar Categoria do Forum</b><br/><br/>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?menu=edtcat&amp;token=$token\" method=\"post\">";
echo "Edit: <select name=\"fcid\">";
while ($fcat=mysql_fetch_array($fcats))
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "Nome:<input name=\"fcname\" maxlength=\"30\"/><br/>";
echo "Posição:<input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categoria do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}else if($menu=="delcat")
{
echo "<p align=\"center\">";
echo "<b>Remover Categoria</b><br/><br/>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?menu=delcat&amp;token=$token\" method=\"post\"/>";
echo "Remover: <select name=\"fcid\">";
while ($fcat=mysql_fetch_array($fcats))
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Remover\"/>";
echo "</form>";
echo "<br/><br/><a href=\"painel_admin.php?menu=fcats&amp;token=$token\">";
echo "Categorias do Forum</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////user info
else if($menu=="chuinfo")
{
echo "<p align=\"center\">";
echo "Selecionar o Usuario<br/><br/>";
echo "<form action=\"painel_admin.php?menu=acui&amp;token=$token\" method=\"post\">";
echo "Nick: <input name=\"unick\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////Change User info
else if($menu=="acui")
{
echo "<p align=\"center\">";
$unick = $_POST["unick"];
$tid = GeraIdPorNick($unick);
if($tid==0)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Usuario Não existe<br/>";
}else{
echo "</p>";
echo "<p>";
echo "<a href=\"painel_admin.php?menu=chubi&amp;token=$token&amp;usuario=$tid\">&#187;Ver Perfil $unick</a><br/>";
$judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".$tid."'"));
if($judg[0]>0)
{
echo "<a href=\"admproc.php?menu=deljdg&amp;token=$token&amp;usuario=$tid\">&#187;Remover Juiz de $unick</a><br/>";
}else{
echo "<a href=\"admproc.php?menu=addjdg&amp;token=$token&amp;usuario=$tid\">&#187;Adicionar Juiz $unick</a><br/>";
}
echo "<a href=\"painel_admin.php?menu=addtog&amp;token=$token&amp;usuario=$tid\">&#187;Adicionar  $unick a um grupo</a><br/>";
echo "<a href=\"painel_admin.php?menu=umset&amp;token=$token&amp;usuario=$tid\">&#187;Moderar $unick</a><br/>";
echo "<a href=\"admproc.php?menu=delxp&amp;token=$token&amp;usuario=$tid\">&#187;Remover Postagens de $unick</a><br/>";
echo "<a href=\"admproc.php?menu=delu&amp;token=$token&amp;usuario=$tid\">&#187;Remover Conta de $unick</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
}
echo "<a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuario</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////////
else if($menu=="chubi")
{
$usuario = $_GET["usuario"];
$unick = GeraNickUsuario($usuario);
$avat = GeraAvatar($usuario);
$email = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$usuario."'"));
$site = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='".$usuario."'"));
$bdy = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='".$usuario."'"));
$uloc = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='".$usuario."'"));
$usig = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='".$usuario."'"));
$sx = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$usuario."'"));
$perm = mysql_fetch_array(mysql_query("SELECT perm FROM fun_users WHERE id='".$usuario."'"));
echo "<p>";
echo "<form action=\"admproc.php?menu=uprof&amp;token=$token&amp;usuario=$usuario\" method=\"post\">";
echo "Nick: <input name=\"unick\" maxlength=\"15\" value=\"$unick\"/><br/>";
echo "Avatar: <input name=\"savat\" maxlength=\"100\" value=\"$avat\"/><br/>";
echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br/>";
echo "Site: <input name=\"usite\" maxlength=\"100\" value=\"$site[0]\"/><br/>";
echo "Data de Nascimento <small>(YYYY-MM-DD)</small>: <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br/>";
echo "Localização: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br/>";
echo "Assinatura: <input name=\"usig\" maxlength=\"100\" value=\"$usig[0]\"/><br/>";
echo "Sexo: <select name=\"usex\" value=\"$sx[0]\">";
echo "<option value=\"M\">Masculino</option>";
echo "<option value=\"F\">Feminino</option>";
echo "</select><br/>";
echo "Privilegios: <select name=\"perm\" value=\"$perm[0]\">";
echo "<option value=\"0\">Normal</option>";
echo "<option value=\"1\">Moderador</option>";
echo "<option value=\"2\">Administrador</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "<br/><br/>";
echo "<form action=\"admproc.php?menu=upwd&amp;token=$token&amp;usuario=$usuario\" method=\"post\">";
echo "Senha: <input name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">";
echo "Informações do Usuario</a><br/>";
echo "<a href=\"sistema.php?menu=painel_admin&amp;token=$token\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Painel de Administração</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else{
echo "<p align=\"center\">";
echo "A Pagina Solicitada não existe<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>