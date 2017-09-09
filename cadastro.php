<?php
/**
 * @author FABIO VIEIRA
 * @copyright 2015
 */
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaBanIP();
LimpaDados();
VerificaBanNick();
echo '<center><strong>Registrar</strong></center>';
$data=$_POST["ano"].'-'.$_POST["mes"].'-'.$_POST["dia"];
if(Registro()){
if ($_SERVER["REQUEST_METHOD"] == "POST" AND LimpaTexto($_POST['protecao'])==$_SESSION['protecao']) {
$e=$db->prepare('SELECT * FROM fun_users WHERE name=:name'); 
$e->bindValue(':name',LimpaTexto($_POST["nick"]), PDO::PARAM_STR);
$e->execute();
$dados= $e->fetchObject();
if (strtolower($_POST['captcha']) != strtolower($_SESSION['captcha'])) {
echo '<div class="erro"><img src="images/notok.gif" alt="!"/> Codigo de Verificação nao confere!</div>';
}else if(!isset($_POST["nick"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> O Campo nick e obrigatório!</div>";    
}else if(!isset($_POST["senha"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> O Campo senha e obrigatório!</div>";    
}else if(!isset($_POST["email"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> O Campo e-mail e obrigatório!</div>";    
}else if(RemoveEspaco($_POST["nick"])||SemCaracter($_POST["nick"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> Nick Invalido!</div>";    
}else if(RemoveEspaco($_POST["senha"])||SemCaracter($_POST["senha"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> Senha Invalido!</div>";       
}else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> E-mail Invalido!</div>"; 
}else if($_POST["senha"]!=$_POST["rsenha"]){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> As Senhas digitadas não são iguais!</div>"; 
}else if(strlen($_POST["nick"])<4){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> O nick deve ter mais de 4 caracteres!</div>"; 
}else if(strlen($_POST["senha"])<4 AND strlen($_POST["rsenha"])<4){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> A senha deve ter mais de 4 caracteres!</div>"; 
}else if($e->rowCount()>0){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> Este usuario já está cadastrado!</div>";     
}else{
unset($_SESSION['captcha']);
unset($_SESSION['protecao']);
$ins = $db->prepare('INSERT INTO fun_users (name, pass, birthday, sex, location, regdate, ipadd, browserm) VALUES (:name, :pass, :birthday, :sex, :location, :regdate, :ipadd, :browserm)');
$nav = explode(" ",$_SERVER['HTTP_USER_AGENT']);
$navegador = $nav[0];
$ins->bindValue(':name',LimpaTexto($_POST["nick"]), PDO::PARAM_STR);
$ins->bindValue(':pass',md5(LimpaTexto($_POST["senha"])), PDO::PARAM_STR);
$ins->bindValue(':birthday',LimpaTexto($data), PDO::PARAM_STR);
$ins->bindValue(':sex',LimpaTexto($_POST["sexo"]), PDO::PARAM_STR);
$ins->bindValue(':location',LimpaTexto($_POST["local"]), PDO::PARAM_STR);
$ins->bindValue(':regdate',time(), PDO::PARAM_STR);
$ins->bindValue(':ipadd',GeraIP(), PDO::PARAM_STR);
$ins->bindValue(':browserm',$navegador, PDO::PARAM_STR);
$ins->execute();
$uiltimoID=$db->lastInsertId();
if($ins->rowCount()>0){
$msg = "Bem Vindo Amigo(a) =)!Você esta no {$config['TITULO_SITE']} , a melhor comunidade da Wap.";
$msg = $msg;
MsgAutomatica($msg,$uiltimoID);
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"!\"/>Registro realizado com sucesso!<br/>Segue seus dados:<br/><strong>Login:</strong> ".LimpaTexto($_POST["nick"])."<br/><strong>Senha:</strong> ".LimpaTexto($_POST["senha"])."<br/><strong>E-mail:</strong> ".LimpaTexto($_POST["email"])."<br/>"; 
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<form action=\"encripta.php\" enctype=\"multipart/form-data\" method=\"POST\">";
echo '<input type="hidden" name="usuario" value="'.LimpaTexto($_POST["nick"]).'" />';
echo '<input type="hidden" name="senha" value="'.LimpaTexto($_POST["senha"]).'" />';
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"Entrar no Site\"/>";
echo "</form></div>"; 
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> Erro ao Cadastrar Entre em contato com o Administrador do site!</div>";    
}
}     
}else{
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST" enctype="multipart/form-data">';    
echo '<strong>Nick:</strong><br/><input type="text" name="nick" size="20" /><br/>';
echo '<strong>E mail:</strong><br/><input type="text" name="email" size="20" /><br/>';  
echo '<strong>Senha:</strong><br/><input type="password" name="senha" size="20" /><br/>';
echo '<strong>Confirme:</strong><br/><input type="password" name="rsenha" size="20" /><br/>';      
echo '<strong>Sexo:</strong><br/><select name="sexo"/>';
echo '<option value="M">Masculino</option>';
echo '<option value="F">Feminino</option>';
echo '<option value="G">Indefinido</option>';
echo '</select><br/>';
echo '<strong>Data de Nascimento:</strong><br/>'.SelecaoData().'<br/>'; 
echo '<strong>Localidade:</strong><br/><input type="text" name="local" size="20" /><br/>';
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo '<img src="captcha/captcha.php" alt="captcha" /><br/>';
echo '<strong>Verificação:</strong><br/><input type="text" name="captcha" size="20" /><br/>';
echo '<input type="submit"  value="Enviar"/>';
echo '</form>';   
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/>";
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Os registros para esta faixa de IP está desativado no momento, por favor, verifique mais tarde!";
echo "</div>";    
}
echo "<p align=\"center\"><a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\"/>Página Inicial</a></p>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>