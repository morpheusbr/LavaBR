<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
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
VerificaBanIP();
$res = mysql_query("UPDATE fun_users SET browserm='".$navegador."', ipadd='".$uip."' WHERE id='".GeraID($token)."'");
////////////////////////////////////////inicio pagina
if($menu=="clmop")
{
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Gerenciando Comunidades","");
echo "<p align=\"center\">";
$whnick = GeraNickUsuario($usuario);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<a href=\"genproc.php?menu=dcm&amp;token=$token&amp;usuario=$usuario&amp;clid=$clid\">&#187;Remover $whnick </a><br/>";
echo "<a href=\"sistema.php?menu=gcp&amp;token=$token&amp;usuario=$usuario&amp;clid=$clid\">&#187; Pontos de Comunidade de$whnick </a><br/>";
echo "<a href=\"sistema.php?menu=gpl&amp;token=$token&amp;usuario=$usuario&amp;clid=$clid\">&#187;Dar Pontos para $whnick</a><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Sem Informa��o!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="gcp")
{
$clid = $_GET["clid"];
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Moderando membro de comunidade","");
echo "<p align=\"center\">";
$whnick = GeraNickUsuario($usuario);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<form action=\"genproc.php?menu=gcp&amp;token=$token&amp;usuario=$usuario&amp;clid=$clid\" method=\"post\">";
echo "menu: <select name=\"giv\">";
echo "<option value=\"1\">Adicionar</option>";
echo "<option value=\"0\">Remover</option>";
echo "</select><br/>";
echo "Pontos: <input name=\"pnt\" format=\"*N\" size=\"2\" maxlength=\"2\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Sem Informa��o!";
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
AdicionarOnline(GeraID($token),"Moderando membro de comunidade","");
echo "<p align=\"center\">";
$whnick = GeraNickUsuario($usuario);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<img src=\"images/point.gif\" alt=\"!\"/>Voc� s� pode dar pontos positivos, estes s�o pontos positivos reais, voc� n�o pode somar pontos positivos<br/>";
$cpl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_clubs WHERE id='".$clid."'"));
echo "<img src=\"images/point.gif\" alt=\"!\"/>Seu pontos de Comunidade s�o $cpl[0]<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>N�o abuse dos pontos positivos que d� para os �suarios, sua comunidade pode ser exclu�do<br/><br/>";
echo "<form action=\"genproc.php?menu=gpl&amp;token=$token&amp;usuario=$usuario&amp;clid=$clid\" method=\"post\">";
echo "Pontos: <input name=\"pnt\" format=\"*N\" size=\"2\" maxlength=\"2\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";    
echo "</form>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Sem Informa��o!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Control Panel
else if($menu=="cpanel")
{
AdicionarOnline(GeraID($token),"Painel do �suario","");
echo "<p align=\"center\">";
echo "<img src=\"images/cpanel.gif\" alt=\"CPanel\"/><br/>";
echo "<b>Painel do �suario</b>";
echo "</p>";
echo "<p>";
$tmsg = TodasPms(GeraID($token));
$umsg = PmsNaoLidas(GeraID($token));
echo "<a href=\"mensagens.php?menu=inicio&amp;token=$token\">&#187;Mensagens Privadas($umsg/$tmsg)</a><br/>";
$usuario_id =GeraID($token);
echo "<a href=\"sistema.php?menu=rwidc&amp;token=$token\">&#187;Cart�o ID.</a><br/>";
echo "<a href=\"sistema.php?menu=myclub&amp;token=$token\">&#187;Minhas Comunidades</a><br/>";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario_id\">&#187;Perfil</a><br/>";
echo "<a href=\"sistema.php?menu=uset&amp;token=$token\">&#187;Configura��es</a><br/>";
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">&#187;Mais Configura��es</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads WHERE uid='".$usuario_id."'"));
echo "<a href=\"lists.php?menu=downloads&amp;token=$token&amp;usuario=$usuario_id\">&#187;Meus Downloads($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE name='".$usuario_id."'"));
echo "<a href=\"lists.php?menu=ignl&amp;token=$token\">&#187;Lista de Bloquiados($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$usuario_id."'"));
echo "<a href=\"lists.php?menu=gbook&amp;token=$token&amp;usuario=$usuario_id\">&#187;Livro de Visitas($noi[0])</a><br/>";
echo "<a href=\"sistema.php?menu=poll&amp;token=$token\">&#187;Minhas Enquetes</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='".$usuario_id."'"));
echo "<a href=\"lists.php?menu=blogs&amp;token=$token&amp;usuario=$usuario_id\">&#187;Minhas Paginas($noi[0])</a><br/>";
echo "<a href=\"lists.php?menu=chmood&amp;token=$token\">&#187;Minhas Salas</a><br/>";
echo "<a href=\"lists.php?menu=avatars&amp;token=$token\">&#187;Lista de Avatars</a><br/>";
echo "<a href=\"lists.php?menu=ecards&amp;token=$token\">&#187;Cart�es</a><br/>";
echo "<a href=\"lists.php?menu=bbcode&amp;token=$token\">&#187;BBCode</a><br/>";
echo "<a href=\"lists.php?menu=faqs&amp;token=$token\">&#187;Ajuda</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Control Panel
else if($menu=="comunidade")
{
AdicionarOnline(GeraID($token),"Clubs Menu","");
echo "<p align=\"center\">";
echo "<b>Menu Comunidades</b>";
echo "</p>";
echo "<p>";
$myid = GeraID($token);
echo "<a href=\"sistema.php?menu=clubs&amp;token=$token\">&#187;Todas Comunidades</a><br/>";
echo "<a href=\"sistema.php?menu=myclub&amp;token=$token\">&#187;Minhas Comunidades</a><br/>";
echo "<a href=\"lists.php?menu=clm&amp;usuario=$myid&amp;token=$token&amp;usuario=$usuario_id\">&#187;Comunidades que participo</a><br/>";
echo "<a href=\"lists.php?menu=pclb&amp;token=$token&amp;usuario=$usuario_id\">&#187;Comunidades mais populares</a><br/>";
echo "<a href=\"lists.php?menu=aclb&amp;token=$token&amp;usuario=$usuario_id\">&#187;Comunidades com mais atividade</a><br/>";
echo "<a href=\"lists.php?menu=rclb&amp;token=$token&amp;usuario=$usuario_id\">&#187;5 Comunidades Aleat�ria</a><br/><br/>";
$ncl = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_clubs ORDER BY created DESC LIMIT 1"));
echo "Comunidade Mais Nova: <a href=\"sistema.php?menu=gocl&amp;clid=$ncl[0]&amp;token=$token\">".TextoGeral($ncl[1])."</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="rwidc")
{
AdicionarOnline(GeraID($token),"My  ID","");
echo "<p align=\"center\">";
echo "<b>Cart�o ID</b><br/>";
$usuario_id = GeraID($token);
echo "<img src=\"rwidc.php?id=$usuario_id\" alt=\"ll id\"/><br/><br/>";
echo "Seu Cart�o ID � atualizado automaticamente<br/><br/>";
echo "Ele pode ser usado em outros sites como avatar<br/><br/>";
echo "Em seu Cart�o ID voc� encontra dados de sua conta e outros detalhes que podem ser compartilhados pela rede , basta passar o link http://nome_do_site.com/rwidc.php?id={$usuario_id}";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////My Clubs
else if($menu=="myclub")
{
AdicionarOnline(GeraID($token),"My Clubs","");
echo "<p align=\"center\">";
echo "<b>Minhas Comunidades</b>";
echo "</p>";
echo "<p>";
$usuario_id = GeraID($token);
if(GeraPontos($usuario_id)<500)
{
echo "As Comunidades s�o pequenos clubes que os usu�rios podem criar, cada comunidade deve ter coisas em comum, por exemplo: uma comunidade para programadores, s� meninas, alco�licos an�nimos, rappers e qualquer outra coisa que sua mente seja capaz de pensar, para que voc� passa criar sua comunidade voc� dever� ter mais de 500 pontos, cada usu�rio pode criar at� 3 comunidades, em sua comunidades voc� poder� criar t�picos , fazer reuni�es nas salas de chat e muito mais";
}else{
$uclubs = mysql_query("SELECT id, name FROM fun_clubs WHERE owner='".$usuario_id."'");
while($club=mysql_fetch_array($uclubs))
{
echo "<a href=\"sistema.php?menu=gocl&amp;clid=$club[0]&amp;token=$token\">$club[1]</a>";
echo ", <a href=\"genproc.php?menu=dlcl&amp;clid=$club[0]&amp;token=$token\">[Remover]</a><br/><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."'"));
if($noi[0]<3)
{
echo "<a href=\"sistema.php?menu=addcl&amp;token=$token\">Criar Comunidade</a>";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////My Clubs
else if($menu=="gocl")
{
$clid = $_GET["clid"];
$clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='".$clid."'"));
AdicionarOnline(GeraID($token),"Viewing A Club","");
$clnm = TextoGeral($clinfo[0]);
echo "<p align=\"center\">";
echo "<b>$clnm</b><br/>";
if(trim($clinfo[4])=="")
{
echo "<img src=\"images/logo.png\" alt=\"logo\"/>";
}else{
echo "<img src=\"$clinfo[4]\" alt=\"logo\"/>";
}
echo "</p>";
echo "<p>";
echo "ID da Comunidade: <b>$clid</b><br/>";
$usuario_id = GeraID($token);
$cango = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND uid='".$usuario_id."' AND accepted='1'"));
echo "Propriet�rio: <a href=\"perfil.php?usuario=$clinfo[1]&amp;token=$token\">".GeraNickUsuario($clinfo[1])."</a><br/>";
$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1'"));
echo "Membros: <a href=\"lists.php?menu=clmem&amp;token=$token&amp;clid=$clid\">$mems[0]</a><br/>";
echo "Data de Cria��o: ".date("d/m/y", $clinfo[6])."<br/>";
echo "Cr�ditos de Pontos: $clinfo[5]<br/>";
$fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='".$clid."'"));
$sala = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='".$clid."'"));
$tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid[0]."'"));
$pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$fid[0]."'"));
if(($cango[0]>0)||Moderador($usuario_id))
{
$noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE clid='".$clid."'"));
echo "<br/><a href=\"lists.php?menu=annc&amp;token=$token&amp;clid=$clid\"><img src=\"images/annc.gif\" alt=\"!\"/>An�ncios($noa[0])</a><br/>";
$noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chat WHERE sala='".$sala[0]."'"));
echo "<a href=\"chat.php?token=$token&amp;sala=$sala[0]\"><img src=\"images/chat.gif\" alt=\"*\"/>$clnm Chat($noa[0])</a><br/>";
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid[0]\"><img src=\"images/1.gif\" alt=\"*\"/>$clnm Forum($tps[0]/$pss[0])</a><br/><br/>";
$ismem = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND uid='".GeraID($token)."'"));
if($ismem[0]>0)
{
//unjoin 
if($clinfo[1]!=$usuario_id)
{
echo "<a href=\"genproc.php?menu=unjc&amp;token=$token&amp;clid=$clid\">Sair da Comunidade</a>";
}
}else{
echo "<a href=\"genproc.php?menu=reqjc&amp;token=$token&amp;clid=$clid\">Entrar na Comunidade!</a>";
}
if(Administrador(GeraID($token)))
{
echo "<br/><a href=\"painel_admin.php?menu=club&amp;token=$token&amp;clid=$clid\">Painel do Admin</a>";
}
if($clinfo[1]==$usuario_id)
{
//club owner
$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0'"));
echo "<br/><a href=\"lists.php?menu=clreq&amp;token=$token&amp;clid=$clid\">&#187;Solicita��es($mems[0])</a><br/>";
}
}else{
echo "Topicos: <b>$tps[0]</b>, Posts: <b>$pss[0]</b><br/>";
echo "<b>Descri��o:</b><br/>";
echo TextoGeral($clinfo[2]);
echo "<br/><br/>";
echo "<b>Regras:</b><br/>";
echo TextoGeral($clinfo[3]);
echo "<br/><br/>";
echo "Voc� Gostou? Est� esperando oque clique  <a href=\"genproc.php?menu=reqjc&amp;token=$token&amp;clid=$clid\">aqui</a> para entrar.";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=clubs&amp;token=$token\">";
echo "Lista de Comunidades</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="addcl")
{
AdicionarOnline(GeraID($token),"Criando comunidade","");
echo "<p align=\"center\">";
echo "<b>Criar Comunidade</b>";
echo "</p>";
echo "<p>";
if(GeraPontos($usuario_id)>=500)
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario_id."'"));
if($noi[0]<3)
{
echo "<img src=\"images/point.gif\" alt=\"*\"/>Todas as Informa��es s�o obrigat�rias<br/>";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Todos membros da equipe podem moderar suas comunidades quando assim acharem necess�rio.<br/>";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Quais quer dos campos que ficarem entre espa�os.<br/>";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Sua comunidade poder� ser removida sem previa aviso se por algum motivo estiver fora das regras do site<br/>";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Se por algum motivo sua comunidade n�o tiver movimenta��o ou atividade, nossos administradores poderam remov� los sem pr�via aviso.<br/><br/>";
echo "<form action=\"genproc.php?menu=addcl&amp;token=$token\" method=\"post\">";
echo "Nome da Comunidade:<input name=\"clnm\" maxlength=\"30\"/><br/>";
echo "Descri��o:<input name=\"clds\" maxlength=\"200\"/><br/>";
echo "Regras:<input name=\"clrl\" maxlength=\"500\"/><br/>";
echo "Foto da capa:<input name=\"cllg\" maxlength=\"200\"/><br/>";
echo "<input type=\"submit\" value=\"Criar\"/>";        
echo "</form>";
}else{
echo "Voc� j� tem 3 comunidades!";
}
}else{
echo "Voc� n�o pode criar comunidades!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Search
else if($menu=="search")
{
AdicionarOnline(GeraID($token),"Pesquisa no Site","");
echo "<p align=\"center\">";
echo "<img src=\"images/search.gif\" alt=\"*\"/><br/>";
echo "<b>Menu de Pesquisa</b>";
echo "</p>";
echo "<p>";
echo "<a href=\"search.php?menu=tpc&amp;token=$token\">&#0187;In Topicos</a><br/>";
echo "<a href=\"search.php?menu=blg&amp;token=$token\">&#0187;In Paginas Pessoais</a><br/>";
echo "<a href=\"search.php?menu=nbx&amp;token=$token\">&#0187;In Mensagens Privadas</a><br/>";
echo "<a href=\"search.php?menu=clb&amp;token=$token\">&#0187;In Comunidades</a><br/><br/>";
echo "Buscar Usu�rios:<br/>";
echo "<a href=\"search.php?menu=mbrn&amp;token=$token\">&#0187;Por Nick</a><br/>";
echo "<a href=\"search.php?menu=mbrl&amp;token=$token\">&#0187;Por Localiza��o</a><br/>";
echo "<a href=\"search.php?menu=mbrs&amp;token=$token\">&#0187;Por Interesse Sexuais</a><br/>";
echo "Mais op��es de busca para os membros est�o por vir<br/>";
echo "<br/>ou voc� pode simplesmente digitar o apelido do membro e ver o seu perfil<br/>";
echo "<form action=\"perfil.php?token=$token\" method=\"post\">";
echo "<br/>Nick <input name=\"mnick\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Ver Perfil\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Settings
else if($menu=="uset")
{
AdicionarOnline(GeraID($token),"Configura��o do Usu�rio","");
echo "<onevent type=\"onenterforward\">";
$usuario_id = GeraID($token);
$avat = GeraAvatar($usuario_id);
$email = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$usuario_id."'"));
$site = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='".$usuario_id."'"));
$bdy = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='".$usuario_id."'"));
$uloc = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='".$usuario_id."'"));
$usig = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='".$usuario_id."'"));
$sx = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$usuario_id."'"));
$uloc[0] = TextoGeral($uloc[0]);
echo "<p align=\"center\">";
echo "<b>Configura��es do Usu�rio</b>";
echo "</p>";
echo "<p>";
echo "<form action=\"genproc.php?menu=uprof&amp;token=$token\" method=\"post\">";
echo "Avatar: <input name=\"savat\" maxlength=\"100\" value=\"$avat\"/><br/>";
echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br/>";
echo "Site: <input name=\"usite\" maxlength=\"100\" value=\"$site[0]\"/><br/>";
echo "Data de Nascimento (YYYY-MM-DD): <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br/>";
echo "Localiza��o: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br/>";
echo "Assinatura: <input name=\"usig\" maxlength=\"100\" value=\"$usig[0]\"/><br/>";
echo "Sexo: <select name=\"usex\" value=\"$sx[0]\">";
echo "<option value=\"M\">Masculino</option>";
echo "<option value=\"F\">Feminino</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "<br/><br/>";
$sml = mysql_fetch_array(mysql_query("SELECT hvia FROM fun_users WHERE id='".GeraID($token)."'"));
if($sml[0]=="1")
{
echo "<a href=\"genproc.php?menu=shsml&amp;token=$token&amp;act=dis\">Desativar Smilies</a>";
}else{
echo "<a href=\"genproc.php?menu=shsml&amp;token=$token&amp;act=enb\">Ativar Smilies</a>";
}
echo "<br/><br/>";
echo "<form action=\"genproc.php?menu=upwd&amp;token=$token\" method=\"post\">";
echo "Senha: <input type=\"password\" name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "Repita Senha: <input type=\"password\" name=\"cpwd\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////////Poll Topic
else if($menu=="poll")
{
AdicionarOnline(GeraID($token),"Administrando Enquetes","");
echo "<p>";
$usuario_id = GeraID($token);
if(GeraPontos($usuario_id)<50)
{
echo "O N�mero minimo de pontos para administrar suas enquetes e de 50 pontos.";
}else{
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario_id."'"));
if($pid[0] == 0)
{
echo "<a href=\"sistema.php?menu=crpoll&amp;token=$token\">Criar Enquete</a>";
}else{
echo "<a href=\"sistema.php?menu=viewpl&amp;token=$token&amp;usuario=$usuario_id\">Ver Minha Enquete</a><br/>";
echo "<a href=\"genproc.php?menu=dlpoll&amp;token=$token\">Remover Enquete</a><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina Inicial</a>";
echo "</p>";
}else if($menu=="crpoll")
{
AdicionarOnline(GeraID($token),"Criando Nova Enquete","");
echo "<p>";
if(GeraPontos(GeraID($token))>=50)
{
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario_id."'"));
if($pid[0] == 0)
{
echo "<form action=\"genproc.php?menu=crpoll&amp;token=$token\" method=\"post\">";
echo "Quest�o:<input name=\"pques\" maxlength=\"250\"/><br/>";
echo "Resposta 1:<input name=\"opt1\" maxlength=\"100\"/><br/>";
echo "Resposta 2:<input name=\"opt2\" maxlength=\"100\"/><br/>";
echo "Resposta 3:<input name=\"opt3\" maxlength=\"100\"/><br/>";
echo "Resposta 4:<input name=\"opt4\" maxlength=\"100\"/><br/>";
echo "Resposta 5:<input name=\"opt5\" maxlength=\"100\"/><br/>";
echo "<input type=\"submit\" value=\"Criar Enquete\"/>";          
echo "</form>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Voc� j� tem uma enquete, apague a atual para criar a nova.";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Voc� precisa de mais de 50 pontos para criar uma enquete.";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="stats")
{
AdicionarOnline(GeraID($token),"Status do Site","");
echo "<p align=\"center\">";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users"));
echo "";
echo "Usu�rios Registrados: <b>$norm[0]</b> ";
$memid = mysql_fetch_array(mysql_query("SELECT id, name  FROM fun_users ORDER BY regdate DESC LIMIT 0,1"));
echo "�ltimo Usu�rio Registrado: <b><a href=\"perfil.php?usuario=$memid[0]&amp;token=$token\">$memid[1]</a></b><br/>";
$mols = mysql_fetch_array(mysql_query("SELECT name, value FROM fun_settings WHERE id='2'"));
echo "Maior Numero Online: <b>$mols[1]</b> Usu�rios online $mols[0]<br/>";
$mols = mysql_fetch_array(mysql_query("SELECT ppl, dtm FROM fun_mpot WHERE ddt='".date("d m y")."'"));
echo "Maior n�mero Online(<a href=\"lists.php?menu=moto&amp;token=$token\"> Online Hoje</a>): <b>$mols[0]</b> Usu�rios $mols[1]<br/>";
$tm24 = time() - (24*60*60);
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'".$tm24."'"));
echo mysql_error();
echo "Usu�rios Ativos no dia<b>$aut[0]</b><br/>";
$notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
echo "N�mero de T�picos: <b>$notc[0]</b> - Numero de Postagens: <b>$nops[0]</b><br/>";
$nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private"));
echo "Numero de Mensagens Privadas: <b>$nopm[0]</b><br/>";
$nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));
echo "Contador de Visitas: <b>$nopm[0]</b>";
echo "";
echo "</p>";
echo "<p>";
echo "";
/////
echo "<a href=\"sistema.php?menu=l24&amp;token=$token\">&#187;Veja oque ocorreu no site nas �ltimas 24 horas</a><br/>";
echo "<a href=\"lists.php?menu=members&amp;token=$token\">&#187;Usu�rios($norm[0])</a><br/>";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='M'"));
echo "<a href=\"lists.php?menu=males&amp;token=$token\">-&#187;Homens($norm[0])</a><br/>";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='F'"));
echo "<a href=\"lists.php?menu=fems&amp;token=$token\">-&#187;Mulheres($norm[0])</a><br/>";
$tbday=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
echo "<a href=\"lists.php?menu=bdy&amp;token=$token\">&#187;Aniversariante do dia($tbday[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs"));
echo "<a href=\"lists.php?menu=allbl&amp;token=$token\">&#187;Paginas Pessoais($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE pollid>'0'"));
echo "<a href=\"lists.php?menu=polls&amp;token=$token\">&#187;Enquetes($noi[0])</a><br/>";
echo "<a href=\"lists.php?menu=topp&amp;token=$token\">&#187;Top Postadores</a><br/>";
echo "<a href=\"lists.php?menu=tchat&amp;token=$token\">&#187;Top Chat</a><br/>";
echo "<a href=\"lists.php?menu=tgame&amp;token=$token\">&#187;Top Jogadore</a><br/>";
echo "<a href=\"lists.php?menu=topb&amp;token=$token\">&#187;Top Batalhadores</a><br/>";
echo "<a href=\"lists.php?menu=tshout&amp;token=$token\">&#187;Top Recados</a><br/>";
$nobr=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL "));
echo "<a href=\"lists.php?menu=brows&amp;token=$token\">&#187;Navegadores($nobr[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm>'0'"));
echo "<a href=\"lists.php?menu=staff&amp;token=$token\">&#187;Status de Usu�rios($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_judges"));
echo "<a href=\"lists.php?menu=judg&amp;token=$token\">&#187;Jogadores($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='1' OR penalty='2'"));
echo "<a href=\"lists.php?menu=banned&amp;token=$token\">&#187;Banidos($noi[0])</a><br/>";
if(Moderador(GeraID($token)))
{
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='0'"));
echo "<a href=\"lists.php?menu=trashed&amp;token=$token\">&#187;Bloquiados($noi[0])</a><br/>";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='2'"));
echo "<a href=\"lists.php?menu=ipban&amp;token=$token\">&#187;IPs Banidos($noi[0])</a><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="l24")
{
AdicionarOnline(GeraID($token),"Site stats","");
echo "<p>";
echo "";
/////
echo "Oque aconteceu no site nas �ltimas 24 horas<br/><br/>";
$tm24 = time() - (24*60*60);
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'".$tm24."'"));
echo "Usu�rios Ativos: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE regdate>'".$tm24."'"));
echo "Usu�rios Registrados: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bgdate>'".$tm24."'"));
echo "Pagimas Pessoais Criadas: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE joined>'".$tm24."' AND accepted='1'"));
echo "Usu�rios que entraram em comunidades: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE created>'".$tm24."'"));
echo "Comunidades Criadas: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE reqdt>'".$tm24."' AND agreed='1'"));
echo "Usu�rios que se tornaram amigos: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE dtime>'".$tm24."'"));
echo "Livro de Visita assinados: <b>$aut[0]</b><br/>";
if(Moderador(GeraID($token))){
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE actdt>'".$tm24."'"));
echo "Relat�rio de Atividades: <b>$aut[0]</b><br/>";
}
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pdt>'".$tm24."'"));
echo "Enquetes Criadas: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE dtpost>'".$tm24."'"));
echo "Pastagens no Forum: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE timesent>'".$tm24."'"));
echo "Mensagens Privadas Enviadas: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shtime>'".$tm24."'"));
echo "Recados Enviadas: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE crdate>'".$tm24."'"));
echo "Topicos criados no forum: <b>$aut[0]</b><br/>";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads WHERE pudt>'".$tm24."'"));
echo "Downloads Enviados: <b>$aut[0]</b><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=stats&amp;token=$token\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Status do Site</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="shout")
{
AdicionarOnline(GeraID($token),"Enviando Recado no Mural","");
echo "<p align=\"center\">";
if(GeraPontos(GeraID($token))<10)
{
echo "Voc� precisa de no m�nimo 10 pontos para postar em nosso mural";
}else{
echo "N�o use nosso mural para realizar flood, mensagens ofensivas, fazer spam ou qualquer outra a��o que infrinja nossas regras.<br/>";

echo "<form action=\"genproc.php?menu=shout&amp;token=$token\" method=\"post\">";
echo "Texto:<input name=\"shtxt\" maxlength=\"100\"/><br/>";
echo "<input type=\"submit\" valie=\"Enviar\"/>";    
echo "</form>";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////shout
else if($menu=="annc")
{
AdicionarOnline(GeraID($token),"Adding An Announcement","");
$clid = $_GET["clid"];
echo "<p align=\"center\">";
$cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
$usuario_id = GeraID($token);
if($cow[0]!=$usuario_id)
{
echo "Est� comunidade n�o e tua!";
}else{
echo "<form action=\"genproc.php?menu=annc&amp;token=$token&amp;clid=$clid\" method=\"post\">";
echo "Texto:<input name=\"antx\" maxlength=\"200\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";    
echo "</form>";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Guestbook
else if($menu=="addblg")
{
if(!GeraPontos(GeraID($token))>0)
{
echo "<p align=\"center\">";
echo "Voc� precisa ter no minimo 10 pontos para poder criar uma pagina pessoal<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Criando Pagina Pessoal","");
echo "<card id=\"inicio\" title=\"Adicionar Pagina Inicial\">";
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?menu=addblg&amp;token=$token\" method=\"post\">";
echo "Titulo:<input name=\"btitle\" maxlength=\"30\"/><br/>";
echo "Texto:<input name=\"msgtxt\" maxlength=\"500\"/><br/>";
echo "<input type=\"submit\" value=\"Criar Pagina Pessoal\"/>";    
echo "</form>";
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////add downloads
else if($menu=="addvlt")
{
if(!GeraPontos(GeraID($token))>5)
{
echo "<p align=\"center\">";
echo "Voc� Precisa de no minimo 5 pontos para enviar arquivos<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Adicionando arquivos em Downloads","");
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?menu=addvlt&amp;token=$token\" method=\"post\">";
echo "Adicione Links externos de jogos , mp3 , mp4 , AVI e etc.<br/><br/>";
echo "Nome do Arquivo:<input name=\"viname\" maxlength=\"50\"/><br/>";
echo "URL do Arquivo:<input name=\"vilink\" maxlength=\"255\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar Arquivo\"/>";    
echo "</form>";
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Guestbook
else if($menu=="signgb")
{
$usuario=$_GET["usuario"];
AdicionarOnline(GeraID($token),"Assino livro de visita de usu�rio","");
if(!AcessoLivroVisita(GeraID($token), $usuario))
{
echo "<p align=\"center\">";
echo "Voc� n�o pode assinar este livro de visitas!<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?menu=signgb&amp;token=$token\" method=\"post\">";
echo "Texto:<input name=\"msgtxt\" maxlength=\"500\"/><br/>";
echo "<input type=\"submit\" value=\"Assinar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="online")
{
AdicionarOnline(GeraID($token),"Vendo Usuarios Online","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = GeraNumeroOn(); //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
//changable sql
$sql = "SELECT
a.name, b.place, b.usesala FROM fun_users a
INNER JOIN fun_online b ON a.id = b.usesala
GROUP BY 1,2
LIMIT $limit_start, $items_per_pagina
";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[2]&amp;token=$token\">$item[0]</a>";
echo "$lnk - $item[1] <br/>";
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"online.php?pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"online.php?pagina=$npagina&amp;token=$token\">Pr�ximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
echo PularPagina($menu, $token,"index");
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="viewpl")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Vendo enquetes de usu�rio","");
echo "<p>";
$usuario_id = GeraID($token);
$pollid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario."'"));
if($pollid[0]>0)
{
$polli = mysql_fetch_array(mysql_query("SELECT id, pqst, opt1, opt2, opt3, opt4, opt5, pdt FROM fun_polls WHERE id='".$pollid[0]."'"));
if(trim($polli[1])!="")
{
$qst = TextoMensagens($polli[1], $token);
echo $qst."<br/><br/>";
$vdone = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$usuario_id."' AND pid='".$pollid[0]."'"));
$nov = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."'"));
$nov = $nov[0];
if($vdone[0]>0)
{
$voted= true;
}else{
$voted = false;
}
$opt1 = $polli[2];
if (trim($opt1)!="")
{
$opt1 = TextoGeral($opt1);
$nov1 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='1'"));
$nov1 = $nov1[0];
if($nov>0)
{
$per = floor(($nov1/$nov)*100);
$rests = "Votos: $nov1($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "1.$opt1 $rests<br/>";
}else{
$lnk = "1.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=1\">$opt1</a> $rests<br/>";
}
echo "$lnk";
}
$opt2 = $polli[3];
if (trim($opt2)!="")
{
$opt2 = TextoGeral($opt2);
$nov2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='2'"));
$nov2 = $nov2[0];
if($nov>0)
{
$per = floor(($nov2/$nov)*100);
$rests = "Votos: $nov2($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "2.$opt2 $rests<br/>";
}else{
$lnk = "2.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=2\">$opt2</a> $rests<br/>";
}
echo "$lnk";
}
$opt3 = $polli[4];
if (trim($opt3)!="")
{
$opt3 = TextoGeral($opt3);
$nov3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='3'"));
$nov3 = $nov3[0];
if($nov>0)
{
$per = floor(($nov3/$nov)*100);
$rests = "Votos: $nov3($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "3.$opt3 $rests<br/>";
}else{
$lnk = "3.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=3\">$opt3</a> $rests<br/>";
}
echo "$lnk";
}
$opt4 = $polli[5];
if (trim($opt4)!="")
{
$opt4 = TextoGeral($opt4);
$nov4 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='4'"));
$nov4 = $nov4[0];
if($nov>0)
{
$per = floor(($nov4/$nov)*100);
$rests = "Votos: $nov4($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "4.$opt4 $rests<br/>";
}else{
$lnk = "4.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=4\">$opt4</a> $rests<br/>";
}
echo "$lnk";
}
$opt5 = $polli[6];
if (trim($opt5)!="")
{
$opt5 = TextoGeral($opt5);
$nov5 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='5'"));
$nov5 = $nov5[0];
if($nov>0)
{
$per = floor(($nov5/$nov)*100);
$rests = "Votos: $nov5($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "5.$opt5 $rests<br/>";
}else{
$lnk = "5.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=5\">$opt5</a> $rests<br/>";
}
echo "$lnk";
}
echo "".date("d m y - H:i",$polli[7])."";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Essa enquete n�o existe!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Este usu�rio n�o tem enquetes.";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="stfol")
{
AdicionarOnline(GeraID($token),"Lista da equipe","");
//////ALL LISTS SCRIPT <<
if($pagina=="" || $pagina<=0)$pagina=1;
$timeout = 180;
$timeon = time()-$timeout;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'"));
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($limit_start<0)$limit_start=0;
//changable sql
$sql = "
SELECT name, perm, id FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'
LIMIT $limit_start, $items_per_pagina
";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"perfil.php?usuario=$item[2]&amp;token=$token\">$item[0]</a>";
if($item[1]==1)
{
$item[1] = "Administrador";
}else if($item[1]==2)
{
$item[1] = "Propriet�rio";
}
echo "$lnk - $item[1] <br/>";
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"sistema.php?menu=$menu&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"sistema.php?menu=$menu&amp;pagina=$npagina&amp;token=$token\">Pr�ximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
echo PularPagina($menu, $token,"index");
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="chbmsg")
{
AdicionarOnline(GeraID($token),"Menagem da lista de amigo","");
$cmsg = TextoGeral(MensagemAmigos(GeraID($token)));
echo "<form action=\"genproc.php?menu=upbmsg&amp;token=$token\" method=\"post\">";
echo "Texto:<input name=\"bmsg\" maxlength=\"100\" value=\"$cmsg\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form><br/>";
echo "<a href=\"lists.php?menu=amigos&amp;token=$token\">";
echo "Lista de Amigos</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////viewuser profile

else if($menu=="viewusrmore")
{
AdicionarOnline(GeraID($token),"Vendo perfil detalhada de usuario","sistema.php?menu=viewusrmore&amp;usuario=$usuario");
echo "<p align=\"center\">";
if($usuario==""||$usuario==0)
{
$mnick = $_POST["mnick"];
$usuario = GeraIdPorNick($mnick);
}
$usuarionick = GeraNickUsuario($usuario);
if($usuarionick!="")
{
echo "</p>";
echo "<p>";
$unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authosala='".$usuario."'"));
$tlink = "<a href=\"lists.php?menu=tbuid&amp;token=$token&amp;usuario=$usuario\">$unol[0]</a>";
echo "T�picos Criados: <b>$tlink</b><br/>";
$unop = mysql_fetch_array(mysql_query("SELECT posts FROM fun_users WHERE id='".$usuario."'"));
$unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$usuario."'"));
$plink = "<a href=\"lists.php?menu=uposts&amp;token=$token&amp;usuario=$usuario\">$unol[0]</a>";
echo "Postagens: <b>$plink/$unop[0]</b><br/>";
$noin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='".$usuario."'"));
$nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE byuid='".$usuario."'"));
echo "Mensagens recebidas: <b>$noin[0]</b> - Mensagens enviadas: <b>$nout[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_users WHERE id='".$usuario."'"));
echo "Pontos: <b>$nopl[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='".$usuario."'"));
echo "Mensagens no Chat: <b>$nopl[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT battlep FROM fun_users WHERE id='".$usuario."'"));
echo "Pontos de Jogo: <b>$nopl[0]</b><br/>";
$judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".$usuario."'"));
if($judg[0]>0)
{
echo "<b>Juiz de jogos</b><br/>";
}
$nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='".$usuario."'"));
$nopl = mysql_fetch_array(mysql_query("SELECT shouts FROM fun_users WHERE id='".$usuario."'"));
echo "Recados: <b><a href=\"lists.php?menu=shouts&amp;token=$token&amp;usuario=$usuario\">$nout[0]</a>/$nopl[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT regdate FROM fun_users WHERE id='".$usuario."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "Registrou se: <b>$jdt</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT lastact FROM fun_users WHERE id='".$usuario."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "�ltima vez ativo: <b>$jdt</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$usuario."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "�ltima Visit�: <b>$jdt</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT browserm FROM fun_users WHERE id='".$usuario."'"));
echo "Navegador: <b>$nopl[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$usuario."'"));
echo "E-mail: <b>$nopl[0]</b><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='".$usuario."'"));
$nopl[0] = strtolower($nopl[0]);
echo "Site Pessoal: <a href=\"$nopl[0]\">$nopl[0]</a><br/>";
$nopl = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='".$usuario."'"));
$sign = TextoMensagens($nopl[0], $token);
echo "Assinatura: $sign<br/>";
if(Moderador(GeraID($token)))
{
$uipadd = mysql_fetch_array(mysql_query("SELECT ipadd FROM fun_users WHERE id='".$usuario."'"));
echo "IP:<a href=\"lists.php?menu=byip&amp;token=$token&amp;usuario=$usuario\">$uipadd[0]</a><br/>";
$nob = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='".$usuario."' OR tid='".$usuario."') AND agreed='1'"));
echo "Amigos: $nob[0]";
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
$noi = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$usuario."'"));
if($noi[0]>0)
{
echo "<a href=\"sistema.php?menu=viewpl&amp;usuario=$usuario&amp;token=$token\">Enquetes</a><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$usuario."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?menu=ucl&amp;usuario=$usuario&amp;token=$token\">Comunidades($noi[0])</a><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$usuario."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?menu=clm&amp;usuario=$usuario&amp;token=$token\">Faz parte de  $noi[0] Comunidades</a><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='".$usuario."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?menu=blogs&amp;usuario=$usuario&amp;token=$token\">Pagina Pessoal($noi[0])</a><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads WHERE uid='".$usuario."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?menu=downloads&amp;usuario=$usuario&amp;token=$token\">Downloads($noi[0])</a><br/>";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$usuario."'"));
echo "<a href=\"lists.php?menu=gbook&amp;usuario=$usuario&amp;token=$token\">Livro de Visita($noi[0])</a><br/>";
$judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".GeraID($token)."'"));
if(Moderador(GeraID($token))||$judg[0]>0)
{
echo "<a href=\"sistema.php?menu=batp&amp;usuario=$usuario&amp;token=$token\">Pontos de Jogo</a><br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Usu�rio solicitado n�o existe ou foi removido.<br/>";
}
echo "<br/><a href=\"perfil.php?token=$token&amp;usuario=$usuario\">Voltar</a>";
echo "<br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="viewbud")
{
AdicionarOnline(GeraID($token),"Vendo lista de amigos","sistema.php?menu=viewbud&amp;usuario=$usuario");
echo "<p align=\"center\">";
if($usuario_id==""||$usuario_id==0)
{
$mnick = $_POST["mnick"];
$usuario = GeraIdPorNick($mnick);
}
$usuarionick = GeraNickUsuario($usuario);
if($usuarionick!="")
{
echo "</p>";
echo "<p>";
echo "$usuarionick<br/>";
echo "<br/>Alerta para os Amigos ";
echo "<form action=\"popup.php?menu=send&amp;token=$token\" method=\"post\">";
echo "<input type=\"text\" name=\"text\" maxlength=\"150\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
echo "</form>";
echo "<br/>";
echo "<a href=\"mensagens.php?menu=sendpm&amp;usuario=$usuario&amp;token=$token\">Enviar Mensagem</a><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Usu�rio n�o existe ou foi removido<br/>";
}
echo "<br/>0 <a a href=\"lists.php?menu=bud&amp;token=$token\">Lista de Amigos</a>";
echo "<br/><a accesskey=\"0\" href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
}
////////////////////////////////////////// uxset
else if($menu=="uxset")
{
AdicionarOnline(GeraID($token),"Vendo mais Configura��o","");
echo "<p>";
echo "<a href=\"sistema.php?menu=uadd&amp;token=$token\">&#187;Meu endere�o</a><br/>";
echo "<a href=\"sistema.php?menu=uper&amp;token=$token\">&#187;Personalidade</a><br/>";
echo "<a href=\"sistema.php?menu=umin&amp;token=$token\">&#187;Mais sobre mim</a><br/>";
echo "<a href=\"sistema.php?menu=upre&amp;token=$token\">&#187;Prefer�ncias</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////User Address
else if($menu=="uadd")
{
AdicionarOnline(GeraID($token),"Modificando Endere�o","");
$ainfo = mysql_fetch_array(mysql_query("SELECT country, city, street, phoneno, timezone FROM fun_xinfo WHERE uid='".GeraID($token)."'"));
echo "<p>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>V� at� prefer�ncias e defina que somente seus amigos podem ver seu endere�o.<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Se voc� n�o quer que essas informa��es seja vista por nenhum usu�rio deixe em branco.<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Fuso hor�rio � necess�ria para obter seus e-mails da conta G-Mail em sua hora local<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Exemplo sobre fuso hor�rio � de 2 para 2 horas em GMT, ou -2,5 para -2: 30 no GMT<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Essas informa��es iram ajudar a voc� a encontrar amigos e talvez o par perfeito<br/><br/>";
echo "<form action=\"genproc.php?menu=uadd&amp;token=$token\" method=\"post\">";
echo '
Pa�s: <input name="ucon" maxlength="50" value=\"$ainfo[0]\"/><br/>
Cidade: <input name="ucit" maxlength="50" value=\"$ainfo[1]\"/><br/>
Rua: <input name="ustr" maxlength="50" value=\"$ainfo[2]\"/><br/>
Hora Local(ex: +2 Ou -2.5): <input name="utzn" size="5" value="0" maxlength="5" value=\"$ainfo[4]\"/><br/>
Telefone: <input name="uphn" maxlength="20" value=\"$ainfo[3]\"/><br/>
';
echo "<input type=\"submit\" value=\"Salvar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configura��es</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////User Preferences
else if($menu=="upre")
{
AdicionarOnline(GeraID($token),"Vendo Prefer�ncias","");
$ainfo = mysql_fetch_array(mysql_query("SELECT sitedscr, amigosonly, sexpre FROM fun_xinfo WHERE uid='".GeraID($token)."'"));
echo "<p>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Seu site j� est� definida nas configura��es normais<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Definir apenas aos amigos sim, para que apenas seus amigos podem ver o seu n�mero de telefone, rua, e nome real<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>A preferencia sexuais ajudara a voc� localizar as pessoas certas<br/><br/>";
echo "<form action=\"genproc.php?menu=upre&amp;token=$token\" method=\"post\">";
echo '
Descri��o: <input name="usds" maxlength="200" value=\"$ainfo[0]\"/><br/>
Somente amigos:
<select name="ubon" value="$ainfo[1]">
<option value="1">sim</option>
<option value="0">n�o</option>
</select>
<br/>Prefer�ncia Sexuais:
<select name="usxp" value="$ainfo[2]">
<option value="F">Feminino</option>
<option value="M">Masculino</option>
<option value="B">Ambos</option>
</select>
';
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo " Mais Configura��o</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////User Personaliy
else if($menu=="uper")
{
AdicionarOnline(GeraID($token),"Personalidade","");
$ainfo = mysql_fetch_array(mysql_query("SELECT height, weight, realname, racerel, eyescolor, profession, hairtype FROM fun_xinfo WHERE uid='".GeraID($token)."'"));
echo "<p>";
echo "<form action=\"genproc.php?menu=uper&amp;token=$token\" method=\"post\">";
echo '
Altura: <input name="uhig" maxlength="10" value="$ainfo[0]"/><br/>
Peso: <input name="uwgt" maxlength="10" value="$ainfo[1]"/><br/>
Nome Real: <input name="urln" maxlength="100" value="$ainfo[2]"/><br/>
Nacionalidade: <input name="ueor" maxlength="100" value="$ainfo[3]"/><br/>
Cor dos Olhos: <input name="ueys" maxlength="10" value="$ainfo[4]"/><br/>
Cor do Cabelo: <input name="uher" maxlength="50" value="$ainfo[5]"/><br/>
Profiss�o: <input name="upro" maxlength="100" value="$ainfo[6]"/><br/>
';
echo "<input type=\"submit\" value=\"Salvar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configura��o</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////User Personaliy
else if($menu=="umin")
{
AdicionarOnline(GeraID($token),"Vendo Mais Informa��o do usu�rio","");
$ainfo = mysql_fetch_array(mysql_query("SELECT likes, deslikes, habitsb, habitsg, favsport, favmusic, moretext FROM fun_xinfo WHERE uid='".GeraID($token)."'"));
echo "<p>";
echo "<form action=\"genproc.php?menu=umin&amp;token=$token\" method=\"post\">";
echo '
Gosta: <input name="ulik" maxlength="250" value="$ainfo[0]"/><br/>
N�o Gosta: <input name="udlk" maxlength="250" value="$ainfo[1]"/><br/>
Mau h�bitos: <input name="ubht" maxlength="250" value="$ainfo[2]"/><br/>
Bons H�bitos: <input name="ught" maxlength="250" value="$ainfo[3]"/><br/>
Esporte preferido: <input name="ufsp" maxlength="100" value="$ainfo[4]"/><br/>
Estilo de M�sica: <input name="ufmc" maxlength="100" value="$ainfo[5]"/><br/>
Fale um pouco sobre voc�: <input name="umtx" maxlength="500" value="$ainfo[6]"/><br/>
';
echo "<input type=\"submit\" value=\"Salvar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"sistema.php?menu=uxset&amp;token=$token\">";
echo "Mais Configura��o</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Give Game Plusses
else if($menu=="givegp")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Dando pontos de jogo","");
echo "<p align=\"center\">";
echo "<b>Pontos de Jogo para  ".GeraNickUsuario($usuario)."</b><br/><br/>";
$gps = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".GeraID($token)."'"));
echo "Os pontos de jogo atual s�o $gps[0]<br/><br/>";
echo "Pontos<br/>";
echo "<form action=\"genproc.php?menu=givegp&amp;token=$token&amp;usuario=$usuario\" method=\"post\">";
echo "<input name=\"tfgp\" format=\"*N\" maxlength=\"2\"/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";  
echo "</form>";
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Give Battle points
else if($menu=="batp")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Adicionando Pontos de Batalha","");
echo "<p align=\"center\">";
$judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".GeraID($token)."'"));
if(Moderador(GeraID($token))||$judg[0]>0)
{
echo "<form action=\"genproc.php?menu=batp&amp;token=$token&amp;usuario=$usuario\" method=\"post\">";
echo "<b>Adicionar Pontos de batalha para  ".GeraNickUsuario($usuario)."</b><br/><br/>";
echo "<input name=\"ptbp\" format=\"*N\" maxlength=\"2\"/>";
echo "<input type=\"submit\" Value=\"Atualizar\"/>";
echo "<input type=\"hidden\" name=\"giv\" value=\"1\"/>";
echo "</form>";
echo "<form action=\"genproc.php?menu=batp&amp;token=$token&amp;usuario=$usuario\" method=\"post\">";
echo "<b>Converter Pontos de  ".GeraNickUsuario($usuario)."</b><br/><br/>";
echo "<input name=\"ptbp\" format=\"*N\" maxlength=\"2\"/>";
echo "<input type=\"submit\" Value=\"Converter\"/>";
echo "<input type=\"hidden\" name=\"giv\" value=\"0\"/>";
echo "</form>";
echo "<br/><br/>";
}else{
echo "A��o n�o permitida!";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if ($menu=="diversao")           {
AdicionarOnline(GeraID($token),"Menu Divers�o","");
echo "<p align=\"center\">";
echo "<img src=\"images/roll.gif\" alt=\"*\"/><br/>";
echo "Ol� se voc� quer se divertir esta no lugar";
echo "</p>";
echo "<p>";
echo "<a href=\"chatbot.php?token=$token\">&#187;Sistema de Conversa</a><br/>";
echo "<a href=\"games.php?menu=guessgm&amp;token=$token\">&#187;Numero Secreto</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>Pagina Inicial</a>";
echo "</p>";
}
///////////////////////////////view blog
else if($menu=="viewblog")
{
$bid = $_GET["bid"];
AdicionarOnline(GeraID($token),"Vendo Paginas Pessoais","");
echo "<p>";
$pminfo = mysql_fetch_array(mysql_query("SELECT btext, bname, bgdate,bowner, id FROM fun_blogs WHERE id='".$bid."'"));
$bttl = TextoGeral($pminfo[1]);
$btxt = TextoGeral($pminfo[0], $token);
$bnick = GeraNickUsuario($pminfo[3]);
$vbbl = "<a href=\"perfil.php?token=$token&amp;usuario=$pminfo[3]\">$bnick</a><br/>";
echo "Id Da Comunidade: <b>$bid</b><br/>";
echo "<b>$bttl</b> Criado por: $vbbl<br/>";
echo "$btxt<br/>";
$tmstamp = $pminfo[2];
$tmdt = date("d m y - h:i:s", $tmstamp);
echo "$tmdt<br/><br/>";
$vb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='".$usuario_id."' AND blogid='".$bid."'"));
if($vb[0]==0)
{
echo "<form action=\"genproc.php?menu=rateb&amp;token=$token&amp;bid=$pminfo[4]\" method=\"post\">";
echo "<select name=\"brate\">";
echo "<option value=\"1\">1</option>";
echo "<option value=\"2\">2</option>";
echo "<option value=\"3\">3</option>";
echo "<option value=\"4\">4</option>";
echo "<option value=\"5\">5</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Votar\"/>";
echo "</form>";
}else{
$rinfo = mysql_fetch_array(mysql_query("SELECT COUNT(*) as nofr, SUM(brate) as nofp FROM fun_brate WHERE blogid='".$bid."'"));
$ther = $rinfo[1]/$rinfo[0];
echo "Votos: $ther - Pontos: $rinfo[1]";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"lists.php?menu=allbl&amp;token=$token\">Voltar as Paginas Pessoais</a><br/>";
$bnick = GeraNickUsuario($pminfo[3]);
echo "<a href=\"lists.php?menu=blogs&amp;token=$token&amp;usuario=$pminfo[3]\">Voltar as paginas de $bnick</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////ADMIN CP
else if($menu=="painel_admin")
{
AdicionarOnline(GeraID($token),"Painel do Administrador","");
echo "<p align=\"center\">";
echo "<b>Painel do Administrador</b>";
echo "</p>";
echo "<p>";
if(Administrador(GeraID($token)))
{
echo "<a href=\"painel_admin.php?menu=general&amp;token=$token\">&#187;Configura��o Geral</a><br/>";
echo "<a href=\"painel_admin.php?menu=fcats&amp;token=$token\">&#187;Categoria de Forum</a><br/>";
echo "<a href=\"painel_admin.php?menu=forums&amp;token=$token\">&#187;Foruns</a><br/>";
echo "<a href=\"painel_admin.php?menu=ugroups&amp;token=$token\">&#187;Grupo de Usu�rios</a><br/>";
echo "<a href=\"painel_admin.php?menu=addperm&amp;token=$token\">&#187;Adicionar Permiss�o</a><br/>";
echo "<a href=\"painel_admin.php?menu=chuinfo&amp;token=$token\">&#187;Atualizar Informa��es do Usu�rio</a><br/>";
echo "<a href=\"painel_admin.php?menu=manrss&amp;token=$token\">&#187;Fontes de Noticias</a><br/>";
echo "<a href=\"sistema.php?menu=givegp&amp;token=$token\">&#187;Pontos de Jogo</a><br/>";
echo "<a href=\"painel_admin.php?menu=addsml&amp;token=$token\">&#187;Adicionar Smilies</a><br/>";
echo "<a href=\"painel_admin.php?menu=addavt&amp;token=$token\">&#187;Adicionar Avatar</a><br/>";
echo "<a href=\"painel_admin.php?menu=chrooms&amp;token=$token\">&#187;Gerenciar Salas de Chat</a><br/>";
echo "<a href=\"painel_admin.php?menu=clrdta&amp;token=$token\">&#187;Limpeza de dados</a><br/>";
}else{
echo "Voc� n�o tem permiss�o para acessar esta p�gina!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
/////////////////////////////////Terms of use
else if($menu=="terms")
{
$usuario_id =GeraID($token);
if($usuario_id>0)
{
AdicionarOnline(GeraID($token),"Termos de Uso","");
}
echo "<p>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>N�o sera tolerado envio de conte�dos racista, mensagens ofensivas, homofobia, e qualquer a��o que prejudique o bem estar de nossos usu�rios<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Postagens in�teis poderiam ser removidas sem pr�vio aviso!<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Voc� poder� ter apenas uma conta, se nossa equipe identificar uma ou mais contas , elas ser�o removidas sem aviso.<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>N�o passe seu usu�rio e senha para ningu�m , esses dados s�o �nicos e intransfer�veis<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Criar contas Fakes com informa��es pessoais (como idade, sexo, localiza��o etc ..) apenas para ter acesso aos f�runs e outros conte�dos do site, podera resultar em Banimento, ou advert�ncia por mensagens. <br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Ass�dio e racismo ir� resultar em um banimento por 7 dias no m�nimo, sem um aviso, e um banimento de IP permanente, se este comportamento continua.<br/>";
echo "<img src=\"images/point.gif\" alt=\"!\"/>A regra mais importante � se divertir aqui e aproveitar a sua estadia ;)<br/>";
echo "<br/>Lembre-se, estas regras foram feitas para proteg�-lo antes de nos proteger, se voc� acha que eles s�o um pouco restritivo, em seguida, ler nosso menu de <a href=\"lists.php?menu=faqs&amp;token=$token\">Ajuda</a>, se mesmo assim tiver duvidas procure um membro da equipe que esteja onlime .<br/>";
echo "</p>";
echo "<p align=\"center\">";
if($usuario_id>0)
{
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
}else{
echo "<a href=\"sistema.php\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Incial</a>";
}
echo "</p>";
}
else if($menu=="pltpc")
{
$tid = $_GET["tid"];
AdicionarOnline(GeraID($token),"Criando Enquetes","");
echo "<p>";
if((GeraPontos(GeraID($token))>=0)||Moderador($usuario_id))
{
$pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
if($pid[0] == 0)
{
echo "<form action=\"genproc.php?menu=pltpc&amp;token=$token&amp;tid=$tid\" method=\"post\">";
echo "Pergunta:<input name=\"pques\" maxlength=\"250\"/><br/>";
echo "Resposta 1:<input name=\"opt1\" maxlength=\"100\"/><br/>";
echo "Resposta 2:<input name=\"opt2\" maxlength=\"100\"/><br/>";
echo "Resposta 3:<input name=\"opt3\" maxlength=\"100\"/><br/>";
echo "Resposta 4:<input name=\"opt4\" maxlength=\"100\"/><br/>";
echo "Resposta 5:<input name=\"opt5\" maxlength=\"100\"/><br/>";
echo "<input type=\"submit\" value=\"Criar Enquete\"/>";
echo "</form>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>J� existe uma enquete com este tema";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Voc� precisa mais de 500 pontos para criar uma enquete";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="categoria_f")
{
$cid = $_GET["cid"];
AdicionarOnline(GeraID($token),"Vendo Categorias do Forum","");
$cinfo = mysql_fetch_array(mysql_query("SELECT name from fun_fcats WHERE id='".$cid."'"));
echo "<p align=\"center\">";
echo MuralDeRecados($token);
echo "</p>";
echo "<p>";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='".$cid."' AND clubid='0' ORDER BY position, id, name");
echo "";
while($forum = mysql_fetch_array($forums))
{
if(AcessoAoForum(GeraID($token), $forum[0]))
{
$notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$forum[0]."'"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$forum[0]."'"));
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";
echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$forum[0]\">$iml$forum[1]($notp[0]/$nops[0])</a><br/>";
$lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics WHERE fid='".$forum[0]."' ORDER BY lastpost DESC LIMIT 0,1"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$lpt[0]."'"));
if($nops[0]==0)
{
$pinfo = mysql_fetch_array(mysql_query("SELECT authosala FROM fun_topics WHERE id='".$lpt[0]."'"));
$tluid = $pinfo[0];
}else{
$pinfo = mysql_fetch_array(mysql_query("SELECT  uid  FROM fun_posts WHERE tid='".$lpt[0]."' ORDER BY dtpost DESC LIMIT 0, 1"));
$tluid = $pinfo[0];
}
$tlnm = TextoGeral($lpt[1]);
$tlnick = GeraNickUsuario($tluid);
$tpclnk = "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
$vulnk = "<a href=\"perfil.php?token=$token&amp;usuario=$tluid\">$tlnick</a>";
echo "�ltima postagem: $tpclnk, Por: $vulnk<br/><br/>";
}
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
$tmsg = TodasPms(GeraID($token));
$umsg = PmsNaoLidas(GeraID($token));
if($umsg>0)
{
echo "<a href=\"mensagens.php?menu=inicio&amp;token=$token\">Mensagens Privadas($umsg/$tmsg)</a><br/>";
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////View Topic
else if($menu=="viewtpc")
{
AdicionarOnline(GeraID($token),"Vendo T�pico No Forum","");
$tid = $_GET["tid"];
$go = $_GET["go"];
$tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
if(!AcessoAoForum(GeraID($token), $tfid[0]))
{
echo "<p align=\"center\">";
echo "Voc� n�o tem permiss�o para acessar este topico<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
$tinfo = mysql_fetch_array(mysql_query("SELECT name, text, authosala, crdate, views, fid, pollid from fun_topics WHERE id='".$tid."'"));
$tnm = TextoGeral($tinfo[0]);
echo "<p align=\"center\">";
$num_paginas = GeraNumeroPaginas($tid);
if($pagina==""||$pagina<1)$pagina=1;
if($go!="")$pagina=GeraProximaPagina($go,$tid);
$posts_per_pagina = 5;
if($pagina>$num_paginas)$pagina=$num_paginas;
$limit_start = $posts_per_pagina *($pagina-1);
echo "<a href=\"sistema.php?menu=post&amp;token=$token&amp;tid=$tid\">Responder T�pico</a>";
$lastlink = "<a href=\"sistema.php?menu=$menu&amp;tid=$tid&amp;token=$token&amp;go=last\">�ltima P�gina</a>";
$firstlink = "<a href=\"sistema.php?menu=$menu&amp;tid=$tid&amp;token=$token&amp;pagina=1\">Primeira P�gina</a> ";
$golink = "";
if($pagina>1)
{
$golink = $firstlink;
}
if($pagina<$num_paginas)
{
$golink .= $lastlink;
}
if($golink !="")
{
echo "<br/>$golink";
}
echo "</p>";
echo "<p>";
$vws = $tinfo[4]+1;
$rpls = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$tid."'"));
echo " Repostas: $rpls[0] - Visto: $vws<br/>";
///fm here
if($pagina==1)
{
$posts_per_pagina=4;
mysql_query("UPDATE fun_topics SET views='".$vws."' WHERE  id='".$tid."'");
$ttext = mysql_fetch_array(mysql_query("SELECT authosala, text, crdate, pollid FROM fun_topics WHERE id='".$tid."'"));
$unick = GeraNickUsuario($ttext[0]);
if(VerificaOnline($ttext[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$usl = "<br/><a href=\"perfil.php?token=$token&amp;usuario=$ttext[0]\">$iml$unick</a>";
$topt = "<a href=\"sistema.php?menu=tpcopt&amp;token=$token&amp;tid=$tid\">*</a>";
if($go==$tid)
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
$pst = TextoGeral($ttext[1],$token);
echo "$usl: $fli$pst $topt<br/>";
$dtot = date("d-m-y - H:i:s",$ttext[2]);
echo $dtot;
echo "<br/>";
if($ttext[3]>0)
{
echo "<a href=\"sistema.php?menu=viewtpl&amp;token=$token&amp;usuario=$tid\">Enquete</a><br/>";
}
}
if($pagina>1)
{
$limit_start--;
}
$sql = "SELECT id, text, uid, dtpost, quote FROM fun_posts WHERE tid='".$tid."' ORDER BY dtpost LIMIT $limit_start, $posts_per_pagina";
$posts = mysql_query($sql);
while($post = mysql_fetch_array($posts))
{
$unick = GeraNickUsuario($post[2]);
if(VerificaOnline($post[2]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$usl = "<br/><a href=\"perfil.php?token=$token&amp;usuario=$post[2]\">$iml$unick</a>";
$pst = TextoGeral($post[1], $token);
$topt = "<a href=\"sistema.php?menu=pstopt&amp;token=$token&amp;pid=$post[0]&amp;pagina=$pagina&amp;fid=$tinfo[5]\">*</a>";
if($post[4]>0)
{
$qtl = "<i><a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;pst=\">(quote:p=forum,d=2015-08-10)</a></i>";
}
if($go==$post[0])
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
echo "$usl: $fli$pst $topt<br/>";
$dtot = date("d-m-y - H:i:s",$post[3]);
echo $dtot;
echo "<br/>";
}
///to here
echo "</p>";
echo "<p align=\"center\">";
$tmsg = TodasPms(GeraID($token));
$umsg = PmsNaoLidas(GeraID($token));
if($umsg>0)
{
echo "<a href=\"mensagens.php?menu=inicio&amp;token=$token\">Mensagens Privada($umsg/$tmsg)</a><br/>";
}
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"sistema.php?menu=viewtpc&amp;pagina=$ppagina&amp;token=$token&amp;tid=$tid\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"sistema.php?menu=viewtpc&amp;pagina=$npagina&amp;token=$token&amp;tid=$tid\">Pr�xima&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"sistema.php\" method=\"get\">";
$rets .= "Jump to pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/>";
echo "<a href=\"sistema.php?menu=post&amp;token=$token&amp;tid=$tid\">Responder</a>";
echo "</p>";
echo "<p>";
$fid = $tinfo[5];
$fname = GeraNomeForum($fid);
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = $cinfo[0];
echo "<a href=\"inicio.php?token=$token\">";
echo "Pagina Inicial</a>&gt;";
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
if($cid[0]>0)
{
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = TextoGeral($cinfo[0]);
echo "<a href=\"sistema.php?menu=categoria_f&amp;token=$token&amp;cid=$cid[0]\">";
echo "$cname</a><br/>";
}else{
$cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$cid[0]."'"));
$cname = TextoGeral($cinfo[0]);
echo "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$cid[0]\">";
echo "$cname Comunidade</a><br/>";
}
$fname = TextoGeral($fname);
echo "&gt;<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">$fname</a>&gt;$tnm";
echo "</p>";
}
//////////////////////////////////View Forum
else if($menu=="ver_f")
{
$fid = $_GET["fid"];
$view = $_GET["view"];
if(!AcessoAoForum(GeraID($token), $fid))
{
AdicionarOnline(GeraID($token),"Administrando forum","");
echo "<p align=\"center\">";
echo "Voc� n�o pode acessar este forum<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Vendo Forum","");
$finfo = mysql_fetch_array(mysql_query("SELECT name from fun_forums WHERE id='".$fid."'"));
$fnm = TextoGeral($finfo[0]);
echo "<p align=\"center\">";
$norf = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss WHERE fid='".$fid."'"));
if($norf[0]>0)
{
echo "<a href=\"rwrss.php?menu=showfrss&amp;token=$token&amp;fid=$fid\"><img src=\"images/rss.gif\" alt=\"rss\"/>$finfo[0] Mais Op��es</a><br/>";
}
echo "<a href=\"sistema.php?menu=newtopic&amp;token=$token&amp;fid=$fid\">Novo T�pico</a><br/>";
echo "<form action=\"sistema.php\" method=\"get\">";
echo "Ver: <select name=\"view\">";
echo "<option value=\"all\">Todos</option>";
echo "<option value=\"new\">�ltimo Visitado</option>";
echo "<option value=\"myps\">Postagem Recente </option>";
echo "</select>";
echo "<input type=\"submit\" value=\"IR\"/>";
echo "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"hidden\" name=\"token\"  value=\"$token\"/>";
echo "</form>";
echo "<br/>";
if($view=="new")
{
echo "Vendo t�picos com postagens desda da �ltima visita";
}else if($view=="myps")
{
echo "Vendo T�picos que eu postei.";
}else {
echo "Todos t�picos";
}
echo "</p>";
echo "<p>";
echo "";
if($pagina=="" || $pagina<=0)$pagina=1;
if($pagina==1)
{
///////////pinned topics
$topics = mysql_query("SELECT id, name, closed, views, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='1' ORDER BY lastpost DESC, name, id LIMIT 0,5");
while($topic = mysql_fetch_array($topics))
{
$iml = "<img src=\"images/normal.gif\" alt=\"*\"/>";
$iml = "<img src=\"images/pin.gif\" alt=\"!\"/>";
$atxt ="";
if($topic[2]=='1')
{
//closed
$atxt = "<img src=\"images/closed.gif\" alt=\"!\"/>";
}
if($topic[4]>0)
{
$pltx = "(P)";
}else{
$pltx = "";
}
$tnm = TextoGeral($topic[1]);
$nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$topic[0]."'"));
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$topic[0]\">$iml$pltx$tnm($nop[0])$atxt</a><br/>";
}
echo "<br/>";
}
$usuario_id = GeraID($token);
if($view=="new")
{
$ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$usuario_id."'"));
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid."' AND pinned='0' AND lastpost >='".$ulv[0]."'"));
}
else if($view=="myps")
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT a.id) FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='".$fid."' AND a.pinned='0' AND b.uid='".$usuario_id."'"));
}
else{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid."' AND pinned='0'"));
}
$num_items = $noi[0]; //changable
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($limit_start<0)$limit_start=0;
if($view=="new")
{
$ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$usuario_id."'"));
$topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='0' AND lastpost >='".$ulv[0]."' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_pagina");
}
else if($view=="myps"){
$topics = mysql_query("SELECT a.id, a.name, a.closed, a.views, a.moved, a.pollid FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='".$fid."' AND a.pinned='0' AND b.uid='".$usuario_id."' GROUP BY a.id ORDER BY a.lastpost DESC, a.name, a.id  LIMIT $limit_start, $items_per_pagina");
}
else{
$topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='0' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_pagina");
}
while($topic = mysql_fetch_array($topics))
{
$nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$topic[0]."'"));
$iml = "<img src=\"images/normal.gif\" alt=\"*\"/>";
if($nop[0]>24)
{
$iml = "<img src=\"images/hot.gif\" alt=\"*\"/>";
}
if($topic[4]=='1')
{
$iml = "<img src=\"images/moved.gif\" alt=\"*\"/>";
}
if($topic[2]=='1')
{
$iml = "<img src=\"images/closed.gif\" alt=\"*\"/>";
}
if($topic[5]>0)
{
$iml = "<img src=\"images/poll.gif\" alt=\"*\"/>";
}
$atxt ="";
if($topic[2]=='1')
{
//closed
$atxt = "(X)";
}
$tnm = TextoGeral($topic[1]);
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$topic[0]\">$iml$tnm($nop[0])$atxt</a><br/>";
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
$tmsg = TodasPms(GeraID($token));
$umsg = PmsNaoLidas(GeraID($token));
if($umsg>0)
{
echo "<a href=\"mensagens.php?menu=inicio&amp;token=$token\">Mensagens Privadas($umsg/$tmsg)</a><br/>";
}
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"sistema.php?menu=ver_f&amp;pagina=$ppagina&amp;token=$token&amp;fid=$fid&amp;view=$view\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"sistema.php?menu=ver_f&amp;pagina=$npagina&amp;token=$token&amp;fid=$fid&amp;view=$view\">Pr�ximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"sistema.php\" method=\"get\">";
$rets .= "Ir para p�gina: <input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";
$rets .= "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/><a href=\"sistema.php?menu=newtopic&amp;token=$token&amp;fid=$fid\">Novo T�pico</a><br/>";
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
if($cid[0]>0)
{
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = TextoGeral($cinfo[0]);
echo "<a href=\"sistema.php?menu=categoria_f&amp;token=$token&amp;cid=$cid[0]\">";
echo "$cname</a><br/>";
}else{
$cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$cid[0]."'"));
$cname = TextoGeral($cinfo[0]);
echo "<a href=\"sistema.php?menu=gocl&amp;token=$token&amp;clid=$cid[0]\">";
echo "$cname Comunidade</a><br/>";
}
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina Inicial</a>";
echo "</p>";
}
else if($menu=="newtopic")
{
$fid = $_GET["fid"];
if(!AcessoAoForum(GeraID($token), $fid))
{
echo "<p align=\"center\">";
echo "Voc� n�o tem permiss�es para acessar o conte�do deste forum<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">P�gina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Criando novo topico","online.php");
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?menu=newtopic&amp;token=$token\" method=\"post\">";
echo "Titulo:<input name=\"ntitle\" maxlength=\"30\"/><br/>";
echo "Texto:<input name=\"tpctxt\" maxlength=\"500\"/><br/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"submit\" value=\"Criar\"/>";
echo "<form>";
echo "<br/><br/><a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
$fname = GeraNomeForum($fid);
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Post reply
else if($menu=="post")
{
$tid = $_GET["tid"];
$tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
$fid = $tfid[0];
if(!AcessoAoForum(GeraID($token), $fid))
{
echo "<p align=\"center\">";
echo "Voc� n�o tem permiss�o para ler o conte�do deste F�rum<br/><br/>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>";
echo "</p>";
exit();
}
AdicionarOnline(GeraID($token),"Respondendo T�pico","");
$qut = $_GET["qut"];
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?menu=post&amp;token=$token\" method=\"post\">";
echo "Texto:<input name=\"reptxt\" maxlength=\"500\"/><br/>";
echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
echo "<input type=\"hidden\" name=\"qut\" value=\"$qut\"/>";
echo "<input type=\"submit\" value=\"Responder\"/>";
echo "</form>";
$fid = GeraForumID($tid);
$fname = GeraNomeForum($fid);
echo "<br/><br/><a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid\">";
echo "Voltar Ao T�pico</a>";
echo "<br/><a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina Inicial</a>";
echo "</p>";
}
else if($menu=="viewtpl")
{
$usuario = $_GET["usuario"];
AdicionarOnline(GeraID($token),"Vendo Enquete do t�pico ","");
echo "<p>";
$usuario_id = GeraID($token);
$pollid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$usuario."'"));
if($pollid[0]>0)
{
$polli = mysql_fetch_array(mysql_query("SELECT id, pqst, opt1, opt2, opt3, opt4, opt5, pdt FROM fun_polls WHERE id='".$pollid[0]."'"));
if(trim($polli[1])!="")
{
$qst = TextoMensagens($polli[1], $token);
echo $qst."<br/><br/>";
$vdone = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$usuario_id."' AND pid='".$pollid[0]."'"));
$nov = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."'"));
$nov = $nov[0];
if($vdone[0]>0)
{
$voted= true;
}else{
$voted = false;
}
$opt1 = $polli[2];
if (trim($opt1)!="")
{
$opt1 = TextoGeral($opt1);
$nov1 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='1'"));
$nov1 = $nov1[0];
if($nov>0)
{
$per = floor(($nov1/$nov)*100);
$rests = "Votes: $nov1($per%)";
}else{
$rests = "Votes: 0(0%)";
}
if($voted)
{
$lnk = "1.$opt1 $rests<br/>";
}else{
$lnk = "1.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=1\">$opt1</a> $rests<br/>";
}
echo "$lnk";
}
$opt2 = $polli[3];
if (trim($opt2)!="")
{
$opt2 = TextoGeral($opt2);
$nov2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='2'"));
$nov2 = $nov2[0];
if($nov>0)
{
$per = floor(($nov2/$nov)*100);
$rests = "Votos: $nov2($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "2.$opt2 $rests<br/>";
}else{
$lnk = "2.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=2\">$opt2</a> $rests<br/>";
}
echo "$lnk";
}
$opt3 = $polli[4];
if (trim($opt3)!="")
{
$opt3 = TextoGeral($opt3);
$nov3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='3'"));
$nov3 = $nov3[0];
if($nov>0)
{
$per = floor(($nov3/$nov)*100);
$rests = "Votos: $nov3($per%)";
}else{
$rests = "Votes: 0(0%)";
}
if($voted)
{
$lnk = "3.$opt3 $rests<br/>";
}else{
$lnk = "3.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=3\">$opt3</a> $rests<br/>";
}
echo "$lnk";
}
$opt4 = $polli[5];
if (trim($opt4)!="")
{
$opt4 = TextoGeral($opt4);
$nov4 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='4'"));
$nov4 = $nov4[0];
if($nov>0)
{
$per = floor(($nov4/$nov)*100);
$rests = "Votos: $nov4($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "4.$opt4 $rests<br/>";
}else{
$lnk = "4.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=4\">$opt4</a> $rests<br/>";
}
echo "$lnk";
}
$opt5 = $polli[6];
if (trim($opt5)!="")
{
$opt5 = TextoGeral($opt5);
$nov5 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='".$pollid[0]."' AND ans='5'"));
$nov5 = $nov5[0];
if($nov>0)
{
$per = floor(($nov5/$nov)*100);
$rests = "Votos: $nov5($per%)";
}else{
$rests = "Votos: 0(0%)";
}
if($voted)
{
$lnk = "5.$opt5 $rests<br/>";
}else{
$lnk = "5.<a href=\"genproc.php?menu=votepl&amp;token=$token&amp;plid=$pollid[0]&amp;ans=5\">$opt5</a> $rests<br/>";
}
echo "$lnk";
}
echo "".date("d m y - H:i",$polli[7])."";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Essa enquete n�o existe!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Voc� n�o pode ver esta enquete";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
//////////////////////////////////////////Post Options
else if($menu=="pstopt")
{
$pid = $_GET["pid"];
$pagina = $_GET["pagina"];
$fid = $_GET["fid"];
AdicionarOnline(GeraID($token),"Op��o de postagem","");
$pinfo= mysql_fetch_array(mysql_query("SELECT uid,tid, text  FROM fun_posts WHERE id='".$pid."'"));
$tsala = $pinfo[0];
$tid = $pinfo[1];
$ptext = TextoGeral($pinfo[2]);
echo "<p align=\"center\">";
echo "<b>Op��o de Pastagem</b>";
echo "</p>";
echo "<p>";
$trnick = GeraNickUsuario($tsala);
echo "<a href=\"mensagens.php?menu=sendpm&amp;token=$token&amp;usuario=$tsala\">&#187;Enviar Mensagem para $trnick</a><br/>";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$tsala\">&#187;Ver Perfil de $trnick</a><br/>";

echo "<a href=\"genproc.php?menu=rpost&amp;token=$token&amp;pid=$pid\">&#187;Den�nciar</a><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;pagina=$pagina\">&#171;Voltar ao t�pico</a><br/>";
if(Moderador(GeraID($token)))
{
echo "<br/>Texto: ";
echo "<form action=\"modproc.php?menu=edtpst&amp;token=$token&amp;pid=$pid\" method=\"post\">";
echo "<input name=\"ptext\" value=\"$ptext\" maxlength=\"500\" value=\"$pmtext\"/> ";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/>";
echo "<br/><a href=\"modproc.php?menu=delp&amp;token=$token&amp;pid=$pid\">&#187;Remover</a><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="tpcopt")
{
$tid = $_GET["tid"];
AdicionarOnline(GeraID($token),"Op��o de t�pico","");
$tinfo= mysql_fetch_array(mysql_query("SELECT name,fid, authosala, text, pinned, closed  FROM fun_topics WHERE id='".$tid."'"));
$tsala = $tinfo[2];
$ttext = TextoGeral($tinfo[3]);
$tname = TextoGeral($tinfo[0]);
echo "<p align=\"center\">";
echo "<b>Op��o de T�pico</b>";
echo "</p>";
echo "<p>";
echo "ID do T�pico: <b>$tid</b><br/>";
$trnick = GeraNickUsuario($tsala);
echo "<a href=\"mensagens.php?menu=sendpm&amp;token=$token&amp;usuario=$tsala\">&#187;Enviar Mensagem para $trnick</a><br/>";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$tsala\">&#187;Ver Perfil de $trnick</a><br/>";
$plid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
if($plid[0]==0)
{
if(Moderador($usuario_id))
{
echo "<a href=\"sistema.php?menu=pltpc&amp;token=$token&amp;tid=$tid\">&#187;Adicionar Enquetes</a><br/>";
}
}else{
if(Moderador($usuario_id))
{
echo "<a href=\"genproc.php?menu=dltpl&amp;token=$token&amp;tid=$tid\">&#187;Remover Enquete</a><br/>";
}
}
echo "<a href=\"genproc.php?menu=rtpc&amp;token=$token&amp;tid=$tid\">&#187;Reportar</a><br/>";
echo "<a href=\"sistema.php?menu=viewtpc&amp;token=$token&amp;tid=$tid&amp;pagina=1\">&#171;Voltar ao T�pico</a><br/>";
if(Moderador(GeraID($token)))
{
echo "<br/>Title: ";
echo "<form action=\"modproc.php?menu=rentpc&amp;token=$token&amp;tid=$tid\" method=\"post\">";
echo "<input name=\"tname\" value=\"$tname\" maxlength=\"25\" value=\"$tname\"/> ";
echo "<input type=\"submit\" value=\"Renomear\"/>";
echo "</form>";
echo "<br/>Texto: ";
echo "<form action=\"modproc.php?menu=edttpc&amp;token=$token&amp;tid=$tid\" method=\"post\">";
echo "<input name=\"ttext\" value=\"$ttext\" maxlength=\"500\" value=\"$pmtext\"/> ";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/><a href=\"modproc.php?menu=delt&amp;token=$token&amp;tid=$tid\">&#187;Remover</a><br/>";
echo "<br/>";
if($tinfo[5]=='1')
{
$ctxt = "Abrir";
$cact = "0";
}else{
$ctxt = "Fechar";
$cact = "1";
}
echo "<a href=\"modproc.php?menu=clot&amp;token=$token&amp;tid=$tid&amp;tdo=$cact\">&#187;$ctxt</a><br/>";
if($tinfo[4]=='1')
{
$ptxt = "Desmarcar";
$pact = "0";
}else{
$ptxt = "Marcar";
$pact = "1";
}
echo "<a href=\"modproc.php?menu=pint&amp;token=$token&amp;tid=$tid&amp;tdo=$pact\">&#187;$ptxt</a><br/>";

echo "<br/>Mover para:<br/>";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE clubid='0'");
echo "<form action=\"modproc.php?menu=mvt&amp;token=$token&amp;tid=$tid\" method=\"post\">";
echo "<select name=\"mtf\">";
while ($forum = mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Mover\"/>";
echo "</form>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>