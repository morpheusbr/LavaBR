<?php
/**
 * @author FABIO VIEIRA
 * @copyright 2015
 */
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
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
AdicionarOnline(GeraID($token),"Pagina Inicial","sistema.php?menu=$menu");
//SalvaInfoLog($token);
echo "<p align=\"center\">";
echo "<small>".date("d/m/Y H:i:s")."</small><br/>";
echo "<img src=\"images/logo.png\" alt=\"{$config['TITULO_SITE']}\"/><br/>";
echo "Olá ".GeraNickUsuario(GeraID($token))."<br/>";
echo TextoGeral(GeraMsgFixa())."<br/>";
echo '<br/><a href="'.$_SERVER["PHP_SELF"].'?token='.$token.'&amp;tempo='.time().'">Atualizar</a></p>';
echo "<div class=\"linha1\"><img src=\"images/npm.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"mensagens.php?menu=inicio&amp;token=$token\">Mensagem Privada(".PmsNaoLidas(GeraID($token))."/".TodasPms(GeraID($token)).")</a></div>";
echo "<div class=\"linha2\"><img src=\"images/chat.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"batepapo.php?token=$token\">Bate Papo(".ContaDadosTabela('fun_chonline').")</a></div>";
echo "<div class=\"linha1\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"comunidades.php?token=$token\">Comunidades(".ContaDadosTabela('fun_clubs').")</a></div>";
echo "<div class=\"linha2\"><img src=\"images/1.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"forum.php?menu=inicio&amp;token=$token\">Forum(".ContaDadosTabela('fun_fcats')."/".ContaDadosTabela('fun_forums')."/".ContaDadosTabela('fun_posts').")</a></div>";
$usuario_id = GeraID($token);
echo "<div class=\"linha1\"><img src=\"images/bdy.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"lists.php?menu=amigos&amp;token=$token\">Amigos(".AmigosOnline($usuario_id)."/".TotalDeAmigos($usuario_id).")</a>";
if(NSolicitacaoAmizade($usuario_id)>0)
{
echo ": <a href=\"lists.php?menu=solicitacao&amp;token=$token\">".NSolicitacaoAmizade($usuario_id)."</a>";
}
echo "</div>";
echo "<div class=\"linha2\"><img src=\"images/cpanel.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"sistema.php?menu=cpanel&amp;token=$token\">Painel do Usuario</a></div>";
echo "<div class=\"linha1\"><img src=\"images/pack.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"lists.php?menu=downloads&amp;token=$token\">Downloads(".ContaDadosTabela('fun_downloads').")</a></div>";
if (Administrador(GeraID($token)))
{
echo "<div class=\"linha2\"><img src=\"images/admn.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"sistema.php?menu=painel_admin&amp;token=$token\">Painel do Admin</a></div>";
}
if(Moderador(GeraID($token)))
{
$tnor = ContaDadosTabela('fun_private','reported','1');
$tot = $tnor[0];
$tnor = ContaDadosTabela('fun_posts','reported','1');
$tot += $tnor[0];
$tnor = ContaDadosTabela('fun_topics','reported','1');
$tot += $tnor[0];
$tnol = ContaDadosTabela('fun_mlog');
$tol = $tnol[0];
if($tol+$tot>0)
{
echo "<div class=\"linha2\"><a href=\"modcp.php?menu=inicio&amp;token=$token\">Relatório Equipe ($tot/$tol)</a></div>";
}
}
echo "</p><p align=\"center\">";
echo MuralDeRecados($token);
echo "</p>";
echo "<p align=\"center\">";
echo "<img src=\"images/onl.gif\" width=\"16\" height=\"16\" alt=\"*\"/><b>Úsuarios Online:</b> <a href=\"online.php?token=$token\">".GeraNumeroOn()."</a><br/>";
echo "<img src=\"images/onl.gif\" width=\"16\" height=\"16\" alt=\"*\"/><strong>Equipe Online:</strong> <a href=\"sistema.php?menu=stfol&amp;token=$token\">". NumeroEquipeOn()."</a><br/>";
echo "<br/><img src=\"images/stat.gif\" width=\"16\" height=\"16\" alt=\"*\"/><a href=\"sistema.php?menu=stats&amp;token=$token\">Status</a> ";
echo "<a href=\"smilies.php?token=$token\"><img src=\"images/reuters.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Smilie</a> <a href=\"sair.php?token=$token\"><img src=\"images/exit.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Sair</a></p>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>