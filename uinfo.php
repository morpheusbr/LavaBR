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
if($menu=="")
{
AdicionarOnline($usuario_id,"Vendo Perfil detalhado","");
echo "<p align=\"center\">";
$usuarionick = GeraNickUsuario($usuario);
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">Voltar ao perfil de $usuarionick</a>";
echo "</p>";
echo "<p><small>";
$regd = mysql_fetch_array(mysql_query("SELECT regdate FROM fun_users WHERE id='".$usuario."'"));
$sage = time()-$regd[0];
$rwage = ceil($sage/(24*60*60));
echo "&#187;Usuario há<b>$rwage Dias</b><br/>";
echo "&#187;Nivel no site: <b>".GeraNivel($usuario)."</b><br/>";
$pstn = mysql_fetch_array(mysql_query("SELECT posts FROM fun_users WHERE id='".$usuario."'"));
$ppd = $pstn[0]/$rwage;
echo "&#187;Historico de Postagens: <b>$pstn[0]</b> postagens com media de <b>$ppd</b> por dia<br/>";
$chpn = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='".$usuario."'"));
$cpd = $chpn[0]/$rwage;
echo "&#187;Historico de Chat: <b>$chpn[0]</b> mensagens, com media de <b>$cpd</b> por dia<br/>";
$gbsg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbsigner='".$usuario."'"));
echo "&#187;Assinou: <b>$gbsg[0] Livro de Visitas</b><br/>";
$brts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='".$usuario."'"));
echo "&#187;Avaliou: <b>$brts[0] Paginas Pessoais</b><br/>";
$pvts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$usuario."'"));
echo "&#187;Votou em <b>$pvts[0] Enquetes</b><br/>";
$strd = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='".$usuario."' AND starred='1'"));
echo "&#187;Mensagens Salvas: <b>$strd[0]</b><br/><br/>";
echo "<a href=\"uinfo.php?menu=fsts&amp;token=$token&amp;usuario=$usuario\">&#187;Postagens no Forum</a><br/>";
echo "<a href=\"uinfo.php?menu=cinf&amp;token=$token&amp;usuario=$usuario\">&#187;Informações de Contato</a><br/>";
echo "<a href=\"uinfo.php?menu=look&amp;token=$token&amp;usuario=$usuario\">&#187;Interesses</a><br/>";
echo "<a href=\"uinfo.php?menu=pers&amp;token=$token&amp;usuario=$usuario\">&#187;Personalidade</a><br/>";
echo "<a href=\"uinfo.php?menu=rwidc&amp;token=$token&amp;usuario=$usuario\">&#187;Cartão ID</a><br/>";
echo "</small></p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="rwidc")
{
AdicionarOnline(GeraID($token),"Vendo Cartão ID","");
echo "<p align=\"center\">";
echo "<b>Cartão ID</b><br/>";
echo "<img src=\"rwidc.php?id=$usuario\" alt=\"ll id\"/><br/><br/>";
echo "Segue o Link do seu Cartão ID <br/><textarea cols=\"50\" rows=\"2\" wrap=\"virtual\" maxlength=\"100\" readonly=\"readonly\">http://".$_SERVER['HTTP_HOST']."/rwidc.php?id=$usuario</textarea><br/><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
else if($menu=="fsts")
{
AdicionarOnline($usuario_id,"Vendo Perfil do Usuario","");
$usuarionick = GeraNickUsuario($usuario);
echo "<p><small>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Postagens em Forum<br/><br/>";
$pst = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$usuario."'"));
$frms = mysql_query("SELECT id, name FROM fun_forums WHERE clubid='0' ORDER BY name");
while ($frm=mysql_fetch_array($frms))
{
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) as nops, a.uid FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE a.uid='".$usuario."' AND b.fid='".$frm[0]."' GROUP BY a.uid "));
$prc = ceil(($nops[0]/$pst[0])*100);
echo TextoGeral($frm[1]).": <b>$nops[0] ($prc%)</b><br/>";
}
echo "<br/><a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Postagens em Forum";
echo "</small></p>";
}
else if($menu=="cinf")
{
AdicionarOnline($usuario_id,"Vendo Perfil do Usuario","");
$usuarionick = GeraNickUsuario($usuario);
echo "<p><small>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Informações de Contato<br/><br/>";
//duh
$inf1 = mysql_fetch_array(mysql_query("SELECT country, city, street, phoneno, realname, amigosonly, sitedscr FROM fun_xinfo WHERE uid='".$usuario."'"));
$inf2 = mysql_fetch_array(mysql_query("SELECT site, email FROM fun_users WHERE id='".$usuario."'"));
if($inf1[5]=='1')
{
if(($usuario_id==$usuario)||(VerificaAmizade($usuario_id, $usuario)))
{
$rln = $inf1[4];
$str = $inf1[2];
$phn = $inf1[3];
}else{
$rln = "Não é possível visualizar";
$str = "Não é possível visualizar";
$phn = "Não é possível visualizar";
}
}else{
$rln = $inf1[4];
$str = $inf1[2];
$phn = $inf1[3];
}
echo "Nome Real: $rln<br/>";
echo "País: $inf1[0]<br/>";
echo "Cidade: $inf1[1]<br/>";
echo "Rua: $str<br/>";
echo "Site: <a href=\"$inf2[0]\">$inf2[0]</a><br/>";
echo "Descrição do Seu Site: $inf1[6]<br/>";
echo "Celular: $phn<br/>";
echo "E-Mail: $inf2[1]<br/>";
//tuh
echo "<br/><a href=\"inicio.php?token=$token\">Pagina Incial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidasr</a>&gt;Informações de Contato";
echo "</small></p>";
}
else if($menu=="look")
{
AdicionarOnline($usuario_id,"Vendo Perfil do Usuario","");
$usuarionick = GeraNickUsuario($usuario);
echo "<p><small>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Interesses<br/><br/>";
//duh
$inf1 = mysql_fetch_array(mysql_query("SELECT sexpre, height, weight, racerel, hairtype, eyescolor FROM fun_xinfo WHERE uid='".$usuario."'"));
$inf2 = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$usuario."'"));
if($inf1[0]=="M" && $inf2[0]=="F")
{
$sxp = "Passivo";
}else if($inf1[0]=="F" && $inf2[0]=="M")
{
$sxp = "";
}else if($inf1[0]=="M" && $inf2[0]=="M"){
$sxp = "Gay";
}else if($inf1[0]=="F" && $inf2[0]=="F"){
$sxp = "Lesbica";
}else if($inf1[0]=="B"){
$sxp = "Bisexual";
}else{
$sxp = "Não Informado";
}
if($inf2[0]=="M")
{
$usx = "Masculino";
}else if($inf2[0]=="F")
{
$usx = "Feminino";
}else{
$usx = "Travesti";
}
echo "Sexo: $usx<br/>";
echo "Sexualidade: $sxp<br/>";
echo "Altura: $inf1[1]<br/>";
echo "Peso: $inf1[2]<br/>";
echo "Nascionalidade: $inf1[3]<br/>";
echo "Cor do Cabelo: $inf1[4]<br/>";
echo "Cor dos Olhos: $inf1[5]<br/>";
//tuh
echo "<br/><a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Interesses";
echo "</small></p>";
}
else if($menu=="pers")
{
AdicionarOnline($usuario_id,"Vendo Perfil do Usuario","");
$usuarionick = GeraNickUsuario($usuario);
echo "<p><small>";
echo "<a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Personalidade<br/><br/>";
//duh
$inf1 = mysql_fetch_array(mysql_query("SELECT likes, deslikes, habitsb, habitsg, favsport, favmusic, moretext FROM fun_xinfo WHERE uid='".$usuario."'"));
echo "<b>Gosta:</b> ".TextoGeral($inf1[0])."<br/>";
echo "<b>Não Gosta:</b> ".TextoGeral($inf1[1])."<br/>";
echo "<b>Bons Habitos:</b> ".TextoGeral($inf1[2])."<br/>";
echo "<b>Maus Habitos:</b> ".TextoGeral($inf1[3])."<br/>";
echo "<b>Esporte Favorito:</b> ".TextoGeral($inf1[4])."<br/>";
echo "<b>Estilo de Musica:</b> ".TextoGeral($inf1[5])."<br/>";
echo "<b>Mais Sobre Mim:</b> ".TextoGeral($inf1[6])."<br/>";
//tuh
echo "<br/><a href=\"inicio.php?token=$token\">Pagina Inicial</a>&gt;";
echo "<a href=\"perfil.php?token=$token&amp;usuario=$usuario\">$usuarionick</a><br/>";
echo "&gt;<a href=\"uinfo.php?token=$token&amp;usuario=$usuario\">Informações Extendidas</a>&gt;Personalidade";
echo "</small></p>";
}
else{
echo "<p align=\"center\">";
echo "A Pagina Solicitada Não Existe<br/><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>