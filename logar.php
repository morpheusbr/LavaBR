<?php
/**
 * @author FABIO VIEIRA
 * @copyright 2015
 */
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
VerificaBanIP();
LimpaDados();
VerificaBanNick();
$u=$db->prepare('SELECT * FROM fun_users WHERE name=:name'); 
$u->bindValue(':name',base64_decode($_GET["usuario"]), PDO::PARAM_STR);
$u->execute();
if(!isset($_GET["usuario"]) AND !isset($_GET["senha"])){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> O Campo nick e senha são obrigatório!</div>";    
}else if($u->rowCount()<1){
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> Este usuário não existe!</div>";     
}else{
$s=$db->prepare('SELECT * FROM fun_users WHERE name=:name AND pass=:pass'); 
$s->bindValue(':name',base64_decode($_GET["usuario"]), PDO::PARAM_STR);
$s->bindValue(':pass',md5(base64_decode($_GET["senha"])), PDO::PARAM_STR);
$s->execute();
$dados=DadosDoUsuario(base64_decode($_GET["usuario"]));
if (($dados->login_errados >= 3) && ($dados->login_errado > (time() - 30))) {
echo '<div class="erro"><img src="images/notok.gif" alt="!"/>Voce inseriu uma senha incorreta 3 vezes ou mais. Favor aguardar 30 segundos e tente novamente.</div>';
}else if($s->rowCount()<1){
$sth = $db->prepare('UPDATE fun_users '
. 'SET login_errados = login_errados+1, login_errado = :login_errado '
. 'WHERE name = :name');
$sth->execute(array(':name' => base64_decode($_GET["usuario"]), ':login_errado' => time()));
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"!\"/> A senha que você está digitando não confere!</div>";     
}else{
$xtm = time() + (TempoSessao()*60);
$token=md5(uniqid(rand(), true));
$ses= $db->prepare('INSERT INTO fun_ses (id, uid, expiretm) VALUES (:id, :uid, :expiretm)');
$ses->bindValue(':id',$token, PDO::PARAM_STR);
$ses->bindValue(':uid',GeraIdPorNick(base64_decode($_GET["usuario"])), PDO::PARAM_INT);
$ses->bindValue(':expiretm',$xtm, PDO::PARAM_STR);
$ses->execute();
if($ses->rowCount()>0){
$tolog=true;
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"!\"/>Login realizado com sucesso ".GeraNickUsuario(GeraIdPorNick(base64_decode($_GET["usuario"])))."!<br/>";
$v=$db->prepare('SELECT * FROM fun_users WHERE name=:name AND pass=:pass'); 
$v->bindValue(':name',base64_decode($_GET["usuario"]), PDO::PARAM_STR);
$v->bindValue(':pass',md5(base64_decode($_GET["senha"])), PDO::PARAM_STR);
$v->execute();
$item=$v->fetchObject();
$u= $db->prepare("UPDATE fun_users SET lastvst=:lastvst WHERE name=:name AND pass=:pass");
$u->bindValue(':lastvst',$item->lastvst, PDO::PARAM_STR);
$u->bindValue(':name',base64_decode($_GET["usuario"]), PDO::PARAM_STR);
$u->bindValue(':pass',md5(base64_decode($_GET["senha"])), PDO::PARAM_STR);
$u->execute();   
}else{
$d=$db->prepare('SELECT * FROM fun_ses WHERE uid=:uid'); 
$d->bindValue(':uid',GeraIdPorNick(base64_decode($_GET["usuario"])), PDO::PARAM_INT);
$d->execute();
if($d->rowCount()>0){
$xtm = time() + (TempoSessao()*60);
$c= $db->prepare("UPDATE fun_ses SET expiretm=:expiretm WHERE uid=:uid");
$c->bindValue(':expiretm',$xtm, PDO::PARAM_STR);
$c->bindValue(':uid',GeraIdPorNick(base64_decode($_GET["usuario"])), PDO::PARAM_INT);
$c->execute();
if($c->rowCount()>0){
$tolog=true;  
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"!\"/>Login realizado com sucesso ".GeraNickUsuario(GeraIdPorNick(base64_decode($_GET["usuario"])))."!<br/>";  
}else{
echo "<div class=\"atencao\"><img src=\"images/point.gif\" alt=\"!\"/>Não é possível iniciar a sessão no momento, tente mais tarde!</div>";
}    
}else{
    
}
}
}
}
if($tolog)
{
echo "<a href=\"inicio.php?token=$token\">Entrar no Site</a></div>";
echo "<div class=\"atencao\"><img src=\"images/point.gif\" alt=\"!\"/><strong>Dica:</strong> Adicione está página nos favoritos para evitar repetir o login novamente!</div>";   
}
echo "<p align=\"center\"><a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\"/>Página Inicial</a></p>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>