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
$id=LimpaTexto($_GET["id"]);
$sala=LimpaTexto($_GET["sala"]);
$senha=LimpaTexto($_GET["senha"]);
LimpaDados();
VerificaLogin();
VerificaBanNick();
VerificaBanIP();
switch (LimpaTexto($_GET["menu"])):
default:
AdicionarOnline(GeraID($_GET["token"]),"Salas de Chat","");
echo "<center><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat<br/><br/>";
echo "<a href=\"mensagens.php?menu=inicio&amp;token={$_GET["token"]}\">Mensagem Privada(".PmsNaoLidas(GeraID($_GET["token"]))."/".TodasPms(GeraID($_GET["token"])).")</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=sala_usuarios&amp;token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Sala de Usuarios</a></center>";
$a = $db->query("SELECT * FROM fun_rooms WHERE static='1' AND clubid='0'");
if($a->rowCount()>0){
while ($room = $a->fetch(PDO::FETCH_OBJ)) {   
if(ProSala($room->id,$_GET["token"]))
{
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
echo "<div class=\"{$cor}\"><a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token={$_GET["token"]}&amp;sala={$room->id}\"><img src=\"images/chat.gif\" alt=\"*\"/>{$room->name}(".ChatOnline($room->id).")</a></div>";
}
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"*\"/>Nao foi criado nenhuma sala de Chat!</div>";  }
break;
case 'sala_usuarios':
AdicionarOnline(GeraID($_GET["token"]),"Vendo sala de usuarios","");
echo "<center><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Usuarios<br/><br/>";
echo "<a href=\"mensagens.php?menu=inicio&amp;token={$_GET["token"]}\">Mensagem Privada(".PmsNaoLidas(GeraID($_GET["token"]))."/".TodasPms(GeraID($_GET["token"])).")</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\">Salas Publicas</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=criar&amp;token={$_GET["token"]}\">Criar Nova Sala</a></center>";
$a = $db->query("SELECT * FROM fun_rooms WHERE static='0'");
while ($room=$a->fetch(PDO::FETCH_OBJ)) {  
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
if(ProSala($room->id,$_GET["token"]))
{
if($room->pass=="")
{
echo "<div class=\"{$cor}\"><a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token={$_GET["token"]}&amp;sala={$room->id}\"><img src=\"images/chat.gif\" alt=\"*\"/>".$room->name."(".ChatOnline($room->id).")</a></div>";
}else{
echo "<div class=\"{$cor}\"><img src=\"images/chat.gif\" alt=\"*\"/>".$room->name."(".ChatOnline($room->id).")";
echo "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"GET\">";
echo "<input type=\"password\" placeholder=\"Senha\" name=\"senha\"/>";
echo "<input type=\"hidden\" name=\"sala\" value=\"$room->id\"/>";
echo "<input type=\"hidden\" name=\"token\" value=\"{$_GET["token"]}\"/>";
echo "<input type=\"hidden\" name=\"menu\" value=\"conversa\"/>";
echo "<input type=\"submit\" value=\"Continuar\"/>";
echo "</form></div>";
}
}
}
break;
case'criar':
AdicionarOnline(GeraID($_GET["token"]),"Criando sala de chat","");
echo "<div class=\"atencao\"><img src=\"images/point.gif\" alt=\"!\"/>Deixe a senha em branco para n?o bloquiar a sala.</div>";
echo "<div class=\"atencao\"><img src=\"images/point.gif\" alt=\"!\"/>Salve sua senha em um local seguro.</div>";
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=criar_ok&amp;token={$_GET["token"]}\" method=\"post\">";
echo "<strong>Nome da Sala:</strong><br/><input name=\"nome\" maxlength=\"30\"/><br/>";
echo "<strong>Senha:</strong><br/><input name=\"senha\" format=\"*x\" maxlength=\"10\"/><br/>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"Criar Sala\"/>";
echo "</form>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat</a></center>";
break;
case 'criar_ok';
$rname = LimpaTexto($_POST["nome"]);
$rpass = LimpaTexto($_POST["senha"]);
AdicionarOnline(GeraID($_GET["token"]),"Criando Sala de Chat","");
if ($rpass=="")
{
$cns = 1;
}else{
$cns = 0;
}
$a=$db->prepare("SELECT * FROM fun_rooms WHERE static=:static");
$a->bindValue(':static',0, PDO::PARAM_INT);
$a->execute();
if($a->rowCount()<10)
{
$a=$db->prepare("INSERT INTO fun_rooms SET name=:name, pass=:pass, censord=:censord, static=:static, lastmsg=:lastmsg");
$a->bindValue(':name',$rname, PDO::PARAM_STR);
$a->bindValue(':pass',$rpass, PDO::PARAM_STR);
$a->bindValue(':censord',$cns, PDO::PARAM_INT);
$a->bindValue(':static',0, PDO::PARAM_INT);
$a->bindValue(':lastmsg',time(), PDO::PARAM_INT);
$a->execute();
if($a->rowCount()>0)
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Sala Criada com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Erro!</div>";
}
}else{
echo "<div class=\"atencao\"><img src=\"images/notok.gif\" alt=\"X\"/>Você já tem 10 Salas</div>";
}
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?menu=sala_usuarios&amp;token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Usuarios</a></center>";
break;
case 'conversa':
$usuario_id = GeraID($token);
$id=$_GET["id"];
$sala=$_GET["sala"];
$senha=$_GET["senha"];
$a=$db->prepare("SELECT * FROM fun_rooms WHERE id=:id");
$a->bindValue(':id',$sala, PDO::PARAM_INT);
$a->execute();
$dados=$a->fetchObject();
if($a->rowCount()<1)
{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta sala não existe tente outra</div>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat</a></center>";
}else if($dados->pass!="")
{
if($senha!=$dados->pass)
{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>A senha digitada e inválida</div>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat</a></center>";
}
}else if(!ProSala($sala,$_GET["token"]))
{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Você não tem permissão para acessar está sala!</div>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat</a></center>";
}else{
AdicionarNoChat($usuario_id, $sala);
AdicionarOnline($usuario_id,"Sala de chat","");
RemoveChat();
echo "<center>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;time=";
echo date('dmHis');
echo "&amp;token=$token&amp;sala=$sala&amp;senha=$senha";
echo "\">Atualizar</a>";
if (PmsNaoLidas(GeraID($_GET["token"]))>0)
{
echo " | <a href=\"mensagens.php?menu=inicio&amp;token={$_GET["token"]}\">".PmsNaoLidas(GeraID($_GET["token"]))." Nova(s) Pm(s)</a>";
}
echo "</center>";
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token={$_GET["token"]}&amp;sala=$sala&amp;senha=$senha\" method=\"post\">";
echo "<p>Texto:<input name=\"message\" type=\"text\" value=\"\" maxlength=\"255\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
$message=$_POST["message"];
$usuario = $_POST["usuario"];
$a=$db->prepare('SELECT * FROM fun_rooms WHERE id=:id'); 
$a->bindValue(':id',$sala,PDO::PARAM_INT);
$a->execute(); 
$dados=$a->fetchObject(); 
if (trim($message) != "")
{
$a=$db->prepare('SELECT * FROM fun_chat WHERE msgtext=:msgtext'); 
$a->bindValue(':msgtext',$message,PDO::PARAM_STR);
$a->execute();
if($a->rowCount()<1){
if($usuario>0){
$a=$db->prepare("INSERT INTO fun_chat SET  chatter=:chatter, usuario=:usuario, timesent=:timesent, msgtext=:msgtext, sala=:sala");
$a->bindValue(':chatter',$usuario_id,PDO::PARAM_INT);
$a->bindValue(':usuario',$usuario,PDO::PARAM_INT);
$a->bindValue(':timesent',time(),PDO::PARAM_INT);
$a->bindValue(':msgtext',$message,PDO::PARAM_STR);
$a->bindValue(':sala',$sala,PDO::PARAM_INT);
$a->execute();     
}else{
$a=$db->prepare("INSERT INTO fun_chat SET  chatter=:chatter, timesent=:timesent, msgtext=:msgtext, sala=:sala");
$a->bindValue(':chatter',$usuario_id,PDO::PARAM_INT);
$a->bindValue(':timesent',time(),PDO::PARAM_INT);
$a->bindValue(':msgtext',$message,PDO::PARAM_STR);
$a->bindValue(':sala',$sala,PDO::PARAM_INT);
$a->execute();     
}

//////////////////////////////////////////////
$b=$db->prepare("UPDATE fun_rooms SET lastmsg=:lastmsg WHERE id=:id"); 
$b->bindValue(':lastmsg',time(),PDO::PARAM_INT);
$b->bindValue(':id',$sala,PDO::PARAM_INT);
$b->execute();
/////////////////////////////////////////////
$c=$db->prepare("SELECT chmsgs FROM fun_users WHERE id=:id"); 
$c->bindValue(':id',$sala,PDO::PARAM_INT);
$c->execute();
$ponto=$c->fetchObject();
$total=$ponto->chmsgs + 1;
///////////////////////////////////////////////
$d=$db->prepare("UPDATE fun_users SET chmsgs=:chmsgs WHERE id=:id"); 
$d->bindValue(':chmsgs',$total,PDO::PARAM_INT);
$d->bindValue(':id',$sala,PDO::PARAM_INT);
$d->execute();
///////////////////////////////////////////////
if($dados->freaky==2)
{
$botid = "aa14290f85f522206c1f9b8eb3abc1df";
$hostname = "www.pandorabots.com";
$hostpath = "/pandora/talk-xml";
$sendData = "botid=".$botid."&input=".urlencode($message)."&custid=".$custid;
$result = EnviarServidor($hostname, $hostpath, $sendData);
$pos = strpos($result, "custid=\"");
$pos = strpos($result, "<that>");
if ($pos === false) {
$reply = "";
} else {
$pos += 6;
$endpos = strpos($result, "</that>", $pos);
$reply = SuperCaracteres(substr($result, $pos, $endpos - $pos));
}
$e=$db->prepare("INSERT INTO fun_chat SET  chatter=:chatter, usuario=:usuario, timesent=:timesent, msgtext=:msgtext, sala=:sala"); 
$e->bindValue(':chatter','900000',PDO::PARAM_INT);
$e->bindValue(':usuario',0,PDO::PARAM_INT);
$e->bindValue(':timesent',time(),PDO::PARAM_INT);
$e->bindValue(':msgtext',$reply."@".GeraNickUsuario($usuario_id),PDO::PARAM_STR);
$e->bindValue(':sala',$sala,PDO::PARAM_INT);
$e->execute();
}
}
$message = "";
}
echo "<p>";
echo "<small>";
$consulta = $db->query("SELECT * FROM fun_chat WHERE sala='".$sala."' ORDER BY timesent DESC, id DESC");
$counter=0;
while ($chat = $consulta->fetch(PDO::FETCH_OBJ)) {
$canc = true;
if($counter<10)
{
if(VerificaBloqueio($chat->chatter)){
if($usuario_id!=$chat->chatter)
{
$canc = false;
}
}
//////good
if(VerificaIgnorado($chat->chatter,$usuario_id)){
$canc = false;
}
//////////good
if($chat->chatter!=$usuario_id)
{
if($chat->usuario!=0)
{
if($chat->usuario!=$usuario_id)
{
$canc = false;
}
}
}
if($chat->exposed=='1' && Moderador($usuario_id))
{
$canc = true;
}
if($canc)
{
$f=$db->prepare("SELECT * FROM fun_users WHERE id=:id"); 
$f->bindValue(':id',$chat->chatter,PDO::PARAM_INT);
$f->execute();
$mod=$f->fetchObject();
$iml = "";
if(($mod->chmood!=0))
{
$g=$db->prepare("SELECT * FROM fun_moods WHERE id=:id"); 
$g->bindValue(':id',$mod->chmood,PDO::PARAM_INT);
$g->execute();
$img=$g->fetchObject();
$iml = "<img src=\"$img->img\" alt=\"$img->text\"/>";
}
$chnick = GeraNickUsuario($chat->chatter);
$optlink = $iml.$chnick;
if(($chat->usuario!=0)&&($chat->chatter==$usuario_id))
{
///out
$iml = "<img src=\"moods/out.gif\" alt=\"!\"/>";
$chnick = GeraNickUsuario($chat->usuario);
$optlink = $iml." Mensagem para ".$chnick;
}
if($chat->usuario==$usuario_id)
{
///out
$iml = "<img src=\"moods/in.gif\" alt=\"!\"/>";
$chnick = GeraNickUsuario($chat->chatter);
$optlink = $iml." Mensagem de ".$chnick;
}
if($chat->exposed=='1')
{
///out
$iml = "<img src=\"moods/point.gif\" alt=\"!\"/>";
$chnick = GeraNickUsuario($chat->chatter);
$tonick = GeraNickUsuario($chat->usuario);
$optlink = "$iml de ".$chnick." para ".$tonick;
}
$ds= date("H.i.s", $chat->timesent);
$text = TextoMensagens($chat->msgtext, $token);
$nos = substr_count($text,"<img src=");
if(FiltroDeSpam($text))
{
$chnick = GeraNickUsuario($chat->chatter);
echo "<div class=\"atencao\"><b>Sistema:&#187;<i>Opa! $chnick, não é permitido fazer spam em nosso site*</i></b></div>";
}
else if($nos>4){
$chnick = GeraNickUsuario($chat->chatter);
echo "<div class=\"atencao\"><b>Sistema:&#187;<i>*Opa! $chnick, somente 4 smilie por mensagem*</i></b></div>";
}else{
$sres = substr($chat->msgtext,0,3);
if($sres == "/me")
{
$chco = strlen($chat->msgtext);
$goto = $chco - 3;
$rest = substr($chat->msgtext,3,$goto);
$tosay = TextoMensagens($rest, $token);
echo "<div class=\"atencao\"><i>*$chnick $tosay*</i></div>";
}else{
$tosay = TextoMensagens($chat->msgtext, $token);
if($dados->censord==1)
{
$tosay = str_replace("fuck","*this word rhymes with duck*",$tosay);
$tosay = str_replace("shit","*dont swear*",$tosay);
$tosay = str_replace("dick","*ooo! you dirty person*",$tosay);
$tosay = str_replace("pussy","*angel flaps*",$tosay);
$tosay = str_replace("cock","*daddy stick*",$tosay);
$tosay = str_replace("can i be a mod","*im sniffing staffs ass*",$tosay);
$tosay = str_replace("can i be admin","*im a big ass kisser*",$tosay);
$tosay = str_replace("ginger","*the cute arsonist*",$tosay);
$tosay = str_replace("neon","*the cute but evil princess*",$tosay);
$tosay = str_replace("kaas","*the cheese boy*",$tosay);
$tosay = str_replace("slut","*s+m freak*",$tosay);
$tosay = str_replace("kahla","*lyrical lizard*",$tosay);
}
if($dados->freaky==1)
{
$tosay = TextoGeral($chat->msgtext);
$tosay = strrev($tosay);
}
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
echo "<div class=\"{$cor}\">$optlink <a href=\"{$_SERVER["PHP_SELF"]}?menu=pvt&amp;token=$token&amp;usuario=$chat->chatter&amp;sala=$sala&amp;senha=$senha\">[PVT]</a>&#187;$ds<br/>";
echo $tosay."</div>";
}
}
$counter++;
}
}
}
echo "</small>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?menu=online&amp;token=$token&amp;sala=$sala&amp;senha=$senha\">Usuários na Sala(".ChatOnline($sala).")</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?token=$token\">Salas de Chat</a></center>";
}
break;
case 'pvt':
$unick = GeraNickUsuario($usuario);
echo "<center><b>Mensagem privada para $unick</b></center>";
AdicionarOnline($usuario_id,"Enviando mensagem no chat","");
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token=$token&amp;sala=$sala&amp;senha=$senha\" method=\"post\">";
echo "<p>Mensagem:<input name=\"message\" type=\"text\" value=\" \" maxlength=\"255\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";
echo "</form><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=expor&amp;token=$token&amp;usuario=$usuario&amp;sala=$sala&amp;senha=$senha\">&#187;Expor $unick</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=online&amp;token=$token&amp;sala=$sala&amp;senha=$senha\">&#187;Usuários na sala </a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token=$token&amp;sala=$sala&amp;senha=$senha\">&#171;Voltar a Sala</a>";
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token=$token\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de Chat</a></center>";
break;
case 'expor':
AdicionarOnline($usuario_id,"Lista de usu?rios da sala","");
$a=$db->prepare("UPDATE fun_chat SET exposed=:exposed WHERE chatter=:chatter AND usuario=:usuario");
$a->bindValue(':exposed',1, PDO::PARAM_INT);
$a->bindValue(':chatter',$usuario, PDO::PARAM_INT);
$a->bindValue(':usuario',$usuario_id, PDO::PARAM_INT);
$a->execute();
if($a->rowCount()>0){
echo "As mensagens de ".GeraNickUsuario($usuario)." foram expostas para equipe!!";    
}else{
echo "N?o foi possivel expor as mensagens de ".GeraNickUsuario($usuario)."!!";   
}
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token=$token&amp;sala=$sala&amp;senha=$senha\">&#171;Voltar a Sala</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?token=$token\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de chat</a></center>";
break;
case 'online':
AdicionarOnline($usuario_id,"Lista de usu?rios na sala","");
echo '<center>Usuarios online na Sala</center>';
$consulta = $db->query("SELECT DISTINCT * FROM fun_chonline WHERE sala='".$sala."' and uid IS NOT NULL");
while ($ins = $consulta->fetch(PDO::FETCH_OBJ)){
echo "".GeraNickUsuario($ins->uid)." <a href=\"{$_SERVER["PHP_SELF"]}?menu=pvt&amp;token=$token&amp;usuario=$ins->uid&amp;sala=$sala&amp;senha=$senha\">[PVT]</a>";
}
echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?menu=conversa&amp;token=$token&amp;sala=$sala&amp;senha=$senha\">&#171;Voltar a Sala</a><br/>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?token=$token\"><img src=\"images/chat.gif\" alt=\"*\"/>Salas de chat</a></center>";
break;
endswitch;
echo "<center><a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>Pagina Inicial</a></center>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>