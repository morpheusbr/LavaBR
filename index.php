<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
LimpaDados();
echo "<center>";
echo "<img src=\"images/logo.png\" alt=\"{$config['TITULO_SITE']}\"/><br/>";
echo "</center>";
echo "<center>";
echo "Aqui voc� pode conhecer pessoas de todo o Brasil.<br/>Aqui voc� encontra: lista de amigos, bate-papo, foruns ou grupos para se comunicar. <br/>Voc� pode criar um
p�gina detalhada, criar um livro de visitas e
publicar seus pensamentos facilmente a partir de seu telefone. <br/>
voc� pode ver seus amigos status online em sua lista de amigos, enviar mensagens privadas para eles e eles podem ve-las onde quer que estejam no mundo.<br/>H� muitos outros recursos dispon�vel�veis gratuitamente, basta voc� se cadastrar no <strong>{$config['TITULO_SITE']}</strong>.";
echo "</center>";
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<form action=\"encripta.php\" enctype=\"multipart/form-data\" method=\"POST\">";
echo "<strong>Login:</strong><br/> <input name=\"usuario\" size=\"8\" maxlength=\"30\"/><br/>";
echo "<strong>Senha:</strong><br/> <input type=\"password\" name=\"senha\" size=\"8\" maxlength=\"30\"/><br/>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"Entrar no Site\"/>";
echo "</form>";
echo "<center><a href=\"cadastro.php\">Registre-se</a></center><p>";
echo "<center><strong>Usu�rios Online:</strong> ".GeraNumeroOn()."<br/>";
echo "<strong>Equipe Online:</strong> ".NumeroEquipeOn()."<br/><strong>Usu�rios Registrados:</strong> ".TotalRegistros()."</center>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>