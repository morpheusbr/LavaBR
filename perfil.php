<?php
/**
 * @author FABIO VIEIRA
 * @copyright 2015
 */
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
$menu = LimpaTexto($_GET["menu"]);
$token = LimpaTexto($_GET["token"]);
$pagina = LimpaTexto($_GET["pagina"]);
$usuario = LimpaTexto($_GET["usuario"]);
$usuario_id = GeraID($token);
VerificaConexao();
LimpaDados();
VerificaLogin();
VerificaBanNick();
VerificaBanIP();
AdicionarOnline(GeraID(LimpaTexto($_GET["token"]))," Vando Perfil de usuario","perfil.php?usuario=". $_GET["usuario"]);
echo "<p align=\"center\">";
if( $_GET["usuario"] ==""|| $_GET["usuario"] ==0)
{
$mnick = $_POST["mnick"];
$usuario = GeraIdPorNick($mnick);
}
$usuarionick = GeraNickUsuario( $_GET["usuario"] ) ;
if( GeraNickUsuario( $_GET["usuario"] ) !="")
{
$avlink = GeraAvatar( $_GET["usuario"] );
echo "<br/><img src=\"$avlink\" width=\"100\" height=\"100\" alt=\"avatar\"/>";
echo "</p>";
echo "<div class=\"linha1\">ID: <b> {$_GET["usuario"] }</b></div>";
echo "<div class=\"linha2\">Nick:". GeraNickUsuario( $_GET["usuario"] )." </div>";
$dados=DadosDoUsuario( $_GET["usuario"] );
$uage = GeraIdade($dados->birthday);
if($dados->sex=='M')
{
$usex = "Masculino";
}else if($dados->sex=='F'){
$usex = "Feminino";
}else{
$usex = "Indefinido";
}
echo "<div class=\"linha1\">Idade: <b>{$uage}</b></div>";
echo "<div class=\"linha2\">Interesse: <b>{$usex}</b></div>";
echo "<div class=\"linha1\">Localidade: <b>{$dados->location}</b></div>";
echo "<div class=\"linha2\"><a href=\"sistema.php?menu=viewusrmore&amp;token= {$_GET["token"]} &amp;usuario={$_GET["usuario"]} \">[Mais detalhes]</a></div>";
$usuario_id = GeraID($_GET["token"]);
if(SolicitacaoAmizade( GeraID($_GET["token"]) , $_GET["usuario"] )==0)
{
echo "<div class=\"linha2\"><a href=\"genproc.php?menu=bud&amp;usuario={$_GET["usuario"]}&amp;token={$_GET["token"]} &amp;todo=add\">Solicitar Amizade</a></div>";
}else if(SolicitacaoAmizade( GeraID($_GET["token"]) , $_GET["usuario"] )==1)
{
echo "<div class=\"linha2\">Aguardando aprovacao de amizade</div>";
}
$ires = Ignorado( GeraID($_GET["token"]) , $_GET["usuario"] );
if(es==2)
{
echo "<div class=\"linha1\"><a href=\"genproc.php?menu=ign&amp;usuario={$_GET["usuario"] }&amp;token={$_GET["token"]} &amp;todo=del\">Remover da Lista de Bloqueio</a></div>";
}else if($ires==1)
{
echo "<div class=\"linha1\"><a href=\"genproc.php?menu=ign&amp;usuario={$_GET["usuario"]}&amp;token={$_GET["token"]} &amp;todo=add\">Bloquiar Usuario</a></div>";
}
if(Moderador(GeraID( $_GET["token"] )))
{
echo "<div class=\"linha2\"><a href=\"modcp.php?menu=user&amp;usuario={$_GET["usuario"]}&amp;token= {$_GET["token"]} \">Moderar Usuario</a></div>";
}
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<center><form action=\"mensagens?menu=enviar_ok&amp;token={$_GET["token"]}\" method=\"post\">";
echo "<strong>Mensagem Privada:</strong><br/><input type=\"hidden\" name=\"usuario\" value=\" {$_GET["usuario"]} \" />";    
echo "<input name=\"texto\" maxlength=\"500\"/><br/>";
echo '<input type="hidden" name="protecao" value="'. $token .'" />';
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form></center>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Este usuario nao existe mais ou foi removido<br/>";
}
echo "<center><a href=\"inicio.php?token= {$_GET["token"]} \">Pagina Inicial</a></center>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>