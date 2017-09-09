<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
LimpaDados();
echo "<center>";
echo "<img src=\"images/logo.png\" alt=\"{$config['TITULO_SITE']}\"/><br/>";
echo "</center>";
echo "<center>";
echo "Aqui você pode conhecer pessoas de todo o Brasil.<br/>Aqui você encontra: lista de amigos, bate-papo, foruns ou grupos para se comunicar. <br/>Você pode criar um
página detalhada, criar um livro de visitas e
publicar seus pensamentos facilmente a partir de seu telefone. <br/>
você pode ver seus amigos status online em sua lista de amigos, enviar mensagens privadas para eles e eles podem ve-las onde quer que estejam no mundo.<br/>Há muitos outros recursos disponível­veis gratuitamente, basta você se cadastrar no <strong>{$config['TITULO_SITE']}</strong>.";
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
echo "<center><strong>Usuários Online:</strong> ".GeraNumeroOn()."<br/>";
echo "<strong>Equipe Online:</strong> ".NumeroEquipeOn()."<br/><strong>Usuários Registrados:</strong> ".TotalRegistros()."</center>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>