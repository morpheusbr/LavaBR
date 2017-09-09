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
BancoDeDados();
$menu = LimpaTexto($_GET["menu"]);
$token = LimpaTexto($_GET["token"]);
$pagina = LimpaTexto($_GET["pagina"]);
$usuario = LimpaTexto($_GET["usuario"]);
$pmid = LimpaTexto($_GET["pmid"]);
$usuario_id = GeraID($token);
VerificaLogin();
switch (LimpaTexto($_GET["menu"])):
case 'inicio':
AdicionarOnline(GeraID($_GET["token"]),"Vendo Mensagens Privadas","");
echo "<p align=\"center\">";
echo "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";
echo "Ver: <select name=\"ver\">";
echo "<option value=\"todas\">Todas</option>";
echo "<option value=\"enviadas\">Enviadas</option>";
echo "<option value=\"salvas\">Salvas</option>";
echo "<option value=\"novas\">Não Lidas</option>";
echo "</select>";
echo "<input type=\"hidden\" name=\"menu\" value=\"{$_GET["menu"]}\"/>";
echo "<input type=\"hidden\" name=\"token\" value=\"{$_GET["token"]}\"/>";
echo "<input type=\"submit\" value=\"IR\"/>";
echo "</form>";
echo "</p>";
if($_GET["ver"]=="")$_GET["ver"]="todas";
if($pagina=="" || $pagina<=0)$pagina=1;
$doit=false;
$num_items = TodasPms(GeraID($_GET["token"]),$_GET["ver"]); //changable
$items_per_pagina= 7;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($num_items>0)
{
if($_GET["ver"]=="todas"){
$sql = "SELECT * FROM fun_private WHERE touid='".GeraID($_GET["token"])."' ORDER BY timesent DESC LIMIT $limit_start,$items_per_pagina";
}else if($_GET["ver"]=="enviadas"){
$sql = "SELECT * FROM fun_private WHERE byuid='".GeraID($_GET["token"])."' ORDER BY timesent DESC LIMIT $limit_start,$items_per_pagina";
}else if($_GET["ver"]=="salvas"){
$sql = "SELECT * FROM fun_private WHERE touid='".GeraID($_GET["token"])."' AND starred='1' ORDER BY timesent DESC LIMIT $limit_start,$items_per_pagina";
}else if($_GET["ver"]=="novas"){
$sql = "SELECT * FROM fun_private WHERE touid='".GeraID($_GET["token"])."' AND unread='1' ORDER BY timesent DESC LIMIT $limit_start,$items_per_pagina";
}
echo "<p><small>";
$items = $db->query($sql);
while ($item = $items->fetch(PDO::FETCH_ASSOC)) {
if($item['unread']=="1")
{
$iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";
}else{
if($item['starred']=="1")
{
$iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";
}else{
$iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";
}
}
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
echo "<div class=\"{$cor}\"><img align=\"left\" src=\"".GeraAvatar($item['byuid'])."\" width=\"60\" height=\"60\" /><a href=\"mensagens.php?menu=ler&amp;id={$item['id']}&amp;token={$_GET["token"]}\">{$iml}".Resumo($item['text'],30,'...')."</a><br/><strong>Por:</strong> ".GeraNickUsuario($item['byuid'])."<br/><strong>Data:</strong> ".date("d/m/Y - H:i",$item['timesent'])."</div>";
}
echo "</small></p>";
echo "<p align=\"center\">";
$npagina = $pagina+1;
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;pagina=$ppagina&amp;token={$_GET["token"]}&amp;ver={$_GET["view"]}$exp\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;pagina=$npagina&amp;token={$_GET["token"]}&amp;ver={$_GET["view"]}$exp\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";
$rets .= "<strong>Pular pagina:</strong> <input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"{$_GET["menu"]}\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"{$_GET["token"]}\"/>";
$rets .= "<input type=\"hidden\" name=\"ver\" value=\"{$_GET["ver"]}\"/>";
$rets .= "</form>";
echo $rets;
echo "<br/>";
}
echo "<br/>";
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=remover&amp;token={$_GET["token"]}\" method=\"post\">";
echo "Remover: <select name=\"tipo\">";
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<option value=\"nlidas\">Não Salvas</option>";
echo "<option value=\"lidas\">Lidas</option>";
echo "<option value=\"todas\">Todas</option>";
echo "</select>";
echo "<input type=\"submit\" value=\"OK\"/>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "</form>";
echo "</p>";
}else{
echo "<p align=\"center\">";
echo "<div class=\"ok\">Você não tem mensagens privadas</div>";
echo "</p>";
}
echo "<center>";
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=enviar&amp;token={$_GET["token"]}\">Enviar Mensagem</a><br/>";
echo "<a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</center>";
break;
case 'remover';
echo "<p align=\"center\">";
AdicionarOnline(GeraID($_GET["token"]),"Removendo Mensagem Privada","");
if(LimpaTexto($_POST["tipo"])=="nlidas" AND $_POST['protecao']==$_SESSION['protecao'])
{
$a=$db->prepare("DELETE FROM fun_private WHERE touid=:touid AND reported!=:reported AND starred=:starred AND unread=:unread");
$a->bindValue(':touid',GeraID($_GET["token"]), PDO::PARAM_INT);
$a->bindValue(':reported',1, PDO::PARAM_INT);
$a->bindValue(':starred',0, PDO::PARAM_INT);
$a->bindValue(':unread',0, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Todas as mensagens exceto as não lidas e salvas foram removidas com sucesso!<br/>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida</div>";
}
}else if(LimpaTexto($_POST["tipo"])=="lidas" AND $_POST['protecao']==$_SESSION['protecao'])
{
$a=$db->prepare("DELETE FROM fun_private WHERE touid=:touid AND reported!=:reported AND unread=:unread");
$a->bindValue(':touid',GeraID($_GET["token"]), PDO::PARAM_INT);
$a->bindValue(':reported',1, PDO::PARAM_INT);
$a->bindValue(':unread',0, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Todas as mensagens ,exceto as não lidas foram removidas com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida</div>";
}
}else if(LimpaTexto($_POST["tipo"])=="todas" AND $_POST['protecao']==$_SESSION['protecao'])
{
$a=$db->prepare("DELETE FROM fun_private WHERE touid=:touid AND reported!=:reported");
$a->bindValue(':touid',GeraID($_GET["token"]), PDO::PARAM_INT);
$a->bindValue(':reported',1, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Todas as mensagens, exceto as reportadas foram removidas com sucesso.</a></div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida</div>";
}
}
unset($_SESSION['protecao']);
echo "<br/><br/><a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;token=$token\">Mensagens Privadas</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
case 'enviar':
AdicionarOnline(GeraID($_GET["token"]),"Enviando Mensagem Privada","");
echo "<p align=\"center\">";
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=enviar_ok&amp;token={$_GET["token"]}\" method=\"post\">";
if(empty($_GET["usuario"])){
echo "Enviar Mensagem Privada<br/>";
echo "Usuario<input name=\"usuario\" maxlength=\"40\"/><br/>";    
}else{
echo "Enviar Mensagem para ".GeraNickUsuario(LimpaTexto($_GET["usuario"]))."<br/>";
echo "<input type=\"hidden\" name=\"usuario\" value=\"".LimpaTexto($_GET["usuario"])."\" />";    
}
echo "<input name=\"texto\" maxlength=\"500\"/><br/>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<br/><br/>";
echo "<a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
case 'enviar_ok':
AdicionarOnline(GeraID($_GET["token"]),"Enviando Mensagem Privada","");
echo "<p align=\"center\">";
if ($_SERVER["REQUEST_METHOD"] == "POST" AND $_POST['protecao']==$_SESSION['protecao']) {
unset($_SESSION['protecao']);
$lastpm = $db->query("SELECT MAX(timesent) FROM fun_private WHERE byuid='".GeraID($_GET["token"])."'")->fetch();
$pmfl = $lastpm[0]+VerificaL();
if(GeraID($_GET["token"])==1)$pmfl=0;
if($pmfl<time())
{
if(!AntiSpam(LimpaTexto($_POST["texto"]),GeraID($_GET["token"])))
{
if((!VerificaIgnorado(GeraID($_GET["token"]),LimpaTexto($_POST["usuario"])))&&(!VerificaBloqueio(GeraID($_GET["token"]))))
{
$a=$db->prepare("INSERT INTO fun_private SET text=:text, byuid=:byuid, touid=:touid, timesent=:timesent");
$a->bindValue(':text',LimpaTexto($_POST["texto"]), PDO::PARAM_STR);
$a->bindValue(':byuid',GeraID($_GET["token"]), PDO::PARAM_INT);
$a->bindValue(':touid',LimpaTexto($_POST["usuario"]), PDO::PARAM_INT);
$a->bindValue(':timesent',time(), PDO::PARAM_INT);
$a->execute(); 
$res=true;
}else{
$res = true;
}
if($res)
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Mensagem enviada com sucesso para ".GeraNickUsuario(LimpaTexto($_POST["usuario"]))."<br/>";
echo TextoMensagens(LimpaTexto($_POST["texto"]),$_GET["token"]).'</div>';
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Você não pode enviar mensagem para ".GeraNickUsuario(LimpaTexto($_POST["usuario"]))."</div>";
}
}else{
$bantime = time() + (7*24*60*60);
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Você não pode enviar mensagem para ".GeraNickUsuario(LimpaTexto($_POST["usuario"]))."<br/><br/>";
echo "Você acaba de enviar um link bloquiado para um de nossos usuários, considera este aviso como uma notificação , se tentar mais uma vez você perderá pontos e se ainda assim você insistir sera banido de nosso site</div>";
$a=$db->prepare("INSERT INTO fun_penalties SET uid=:uid, penalty=:penalty, exid=:exid, timeto=:timeto, pnreas=:pnreas");
$a->bindValue(':uid',GeraID($_GET["token"]), PDO::PARAM_INT);
$a->bindValue(':penalty',1, PDO::PARAM_INT);
$a->bindValue(':exid',1, PDO::PARAM_INT);
$a->bindValue(':timeto',$bantime, PDO::PARAM_INT);
$a->bindValue(':pnreas','Banido Automática mente por spam', PDO::PARAM_STR);
$a->execute();
///////////////////////////
$b=$db->prepare("UPDATE fun_users SET plusses=:plusses, shield=:shield WHERE id=:id"); 
$b->bindValue(':id',GeraID($_GET["token"]), PDO::PARAM_INT);
$b->bindValue(':plusses',0, PDO::PARAM_INT);
$b->bindValue(':shield',0, PDO::PARAM_INT);
$b->execute(); 
/////////////////////////////////////////
$c=$db->prepare("INSERT INTO fun_private SET text=:text, byuid=:byuid, touid=:touid, timesent=:timesent"); 
$c->bindValue(':text',LimpaTexto($_POST["texto"]), PDO::PARAM_STR);
$c->bindValue(':byuid',GeraID($_GET["token"]), PDO::PARAM_INT);
$c->bindValue(':touid',1, PDO::PARAM_INT);
$c->bindValue(':timesent',time(), PDO::PARAM_INT);
$c->execute(); 
}
}else{
$rema = $pmfl - time();
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Proteção Antiflood Ativa: $rema Segundos</div>";
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"O\"/>Ação não permitida pelo sistema</div>";    
}
echo "<br/><a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;token={$_GET["token"]}\">Mensagens Privadas</a><br/>";
echo "<a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
case 'ler':
AdicionarOnline(GeraID($_GET["token"]),"Lendo Mensagem Privada","");
echo "<p>";
$a=$db->prepare("SELECT * FROM fun_private WHERE id=:id"); 
$a->bindValue(':id',LimpaTexto($_GET["id"]),PDO::PARAM_INT);
$a->execute();
$dados=$a->fetchObject(); 
if(GeraID($_GET["token"])==$dados->touid)
{
$b=$db->prepare("UPDATE fun_private SET unread=:unread WHERE id=:id"); 
$b->bindValue(':unread',0,PDO::PARAM_INT);
$b->bindValue(':id',LimpaTexto($_GET["id"]),PDO::PARAM_INT);
$b->execute();
}
if(($dados->touid==GeraID($_GET["token"]))||($dados->byuid==GeraID($_GET["token"])))
{
if(GeraID($_GET["token"])==$dados->touid)
{
if(VerificaOnline($dados->byuid))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$ptxt = "Enviado Por: ";
$bylnk = "<a href=\"perfil.php?usuario={$dados->byuid}&amp;token={$_GET["token"]}\">{$iml}".GeraNickUsuario($dados->byuid)."</a>";
}else{
if(VerificaOnline($dados->touid))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$ptxt = "Enviado Para: ";
$bylnk = "<a href=\"perfil.php?usuario={$dados->touid}&amp;token={$_GET["token"]}\">{$iml}".GeraNickUsuario($dados->touid)."</a>";
}
echo "$ptxt $bylnk<br/>";
$tmstamp = $dados->timesent;
$tmdt = date("d m Y - H:i:s", $tmstamp);
echo "$tmdt<br/><br/>";
$pmtext = TextoMensagens($dados->text,$_GET["token"]);
$pmtext = str_replace("/ajuda","<a href=\"lists.php?menu=faqs&amp;token={$_GET["token"]}\">Ajuda</a>", $pmtext);
$pmtext = str_replace("/texto",GeraNickUsuario($dados->touid), $pmtext);
if(FiltroDeSpam($pmtext))
{
if(($dados->reported=="0") && ($dados->byuid!=1))
{
$b=$db->prepare("UPDATE fun_private SET reported=:reported WHERE id=:id"); 
$b->bindValue(':reported',1,PDO::PARAM_INT);
$b->bindValue(':id',LimpaTexto($_GET["id"]),PDO::PARAM_INT);
$b->execute();
}
}
echo $pmtext;
echo "</p>";
$token = md5(uniqid(rand(), true));
$_SESSION['protecao'] = $token;
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=enviar_ok&amp;token={$_GET["token"]}\" method=\"post\">";
echo "<strong>Responder:</strong> <input type=\"hidden\" name=\"usuario\" value=\"".$dados->byuid."\" />";    
echo "<input name=\"texto\" maxlength=\"500\"/><br/>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"Responder\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=opcao&amp;token={$_GET["token"]}\" method=\"post\">";
echo "Opções: <select name=\"tipo\">";
echo "<option value=\"remover-".LimpaTexto($_GET["id"])."\">Remover</option>";
if(MensagemSalva(LimpaTexto($_GET["id"])))
{
echo "<option value=\"remover_s-".LimpaTexto($_GET["id"])."\">Remover das Salvas</option>";
}else{
echo "<option value=\"salvar-".LimpaTexto($_GET["id"])."\">Salvar</option>";
}
echo "<option value=\"reportar-".LimpaTexto($_GET["id"])."\">Reportar</option>";
echo "</select>";
echo '<input type="hidden" name="protecao" value="'.$token.'" />';
echo "<input type=\"submit\" value=\"OK\"/>";
echo "</form>";
echo "<br/><br/><a href=\"{$_SERVER["PHP_SELF"]}?menu=dialogo&amp;token={$_GET["token"]}&amp;usuario={$dados->byuid}\">Ver Conversa</a>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Está Mensagem Privada não e sua</div>";
}
echo "<br/><br/><a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;token={$_GET["token"]}\">Mensagens Privadas</a><br/>";
echo "<a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
case 'opcao';
$item = explode("-",$_POST["tipo"]);
$id = $item[1];
$tipo = $item[0];
echo "<p align=\"center\">";
$a=$db->prepare("SELECT * FROM fun_private WHERE id=:id");
$a->bindValue(':id',$id, PDO::PARAM_STR);
$a->execute();
$dados=$a->fetchObject();
if($tipo=="remover" AND $_POST['protecao']==$_SESSION['protecao'])
{
AdicionarOnline(GeraID($_GET["token"]),"Removendo Mensagem Privada","");
if(GeraID($_GET["token"])==$dados->touid)
{
if($dados->reported>0)
{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta mensagem foi reportada e não pode ser removida</div>";
}else{
$a=$db->prepare("DELETE FROM fun_private WHERE id=:id");
$a->bindValue(':id',$id, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Mensagem removida com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Você não tem permissão para remover esta mensagem</div>";
}
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Está mensagem não e sua</div>";
}
}else if($tipo=="remover_s" AND $_POST['protecao']==$_SESSION['protecao'])
{
AdicionarOnline(GeraID($_GET["token"]),"Salvando Mensagem Privada","");
if(GeraID($_GET["token"])==$dados->touid)
{
$a=$db->prepare("UPDATE fun_private SET starred=:starred WHERE id=:id");
$a->bindValue(':id',$id, PDO::PARAM_INT);
$a->bindValue(':starred',1, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Mensagem salva</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Não foi possível salvar a mensagem</div>";
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta mensagem não e sua</div>";
}
}else if($tipo=="salvar" AND $_POST['protecao']==$_SESSION['protecao'])
{
AdicionarOnline(GeraID($_GET["token"]),"removendo mensagem salva","");
if(GeraID($_GET["token"])==$dados->touid)
{
$a=$db->prepare("UPDATE fun_private SET starred=:starred WHERE id=:id");
$a->bindValue(':id',$id, PDO::PARAM_INT);
$a->bindValue(':starred',0, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Mensagem removida das mensagens salvas com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida</div>";
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta mensagem não e sua</div>";
}
}else if($tipo=="reportar" AND $_POST['protecao']==$_SESSION['protecao'])
{
AdicionarOnline(GeraID($_GET["token"]),"Reportando Mensagem ","");
if(GeraID($_GET["token"])==$dados->touid)
{
if($dados->reported<1)
{
$a=$db->prepare("UPDATE fun_private SET reported=:reported WHERE id=:id");
$a->bindValue(':id',$id, PDO::PARAM_INT);
$a->bindValue(':reported',1, PDO::PARAM_INT);
$a->execute();
if($a->rowCount())
{
echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Mensagem Reportada Com sucesso</div>";
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida</div>";
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta mensagem não e sua</div>";
}
}else{
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Esta mensagem não e sua</div>";
}
}
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;token={$_GET["token"]}\">Mensagens Privadas</a><br/>";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
case 'dialogo':
AdicionarOnline(GeraID($_GET["token"]),"Vendo Conversa nas Mensagens Privadas","");
if($pagina=="" || $pagina<=0)$pagina=1;
$pms = $db->query("SELECT COUNT(*) FROM fun_private WHERE (byuid='".GeraID($_GET["token"])."' AND touid='".LimpaTexto($_GET["usuario"])."') OR (byuid='".LimpaTexto($_GET["usuario"])."' AND touid='".GeraID($_GET["token"])."') ORDER BY timesent")->fetch();
$num_items = $pms[0]; //changable
$items_per_pagina= 7;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
if($num_items>0)
{
echo "<p>";
$pms = $db->query("SELECT * FROM fun_private WHERE (byuid='".GeraID($_GET["token"])."' AND touid='".LimpaTexto($_GET["usuario"])."') OR (byuid='".LimpaTexto($_GET["usuario"])."' AND touid='".GeraID($_GET["token"])."') ORDER BY timesent LIMIT $limit_start, $items_per_pagina");
while ($pm = $pms->fetch(PDO::FETCH_ASSOC)){
if(VerificaOnline($pm['byuid']))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
if(VerificaOnline($pm['touid']))
{
$iml2 = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml2 = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
$bylnk = "<div class=\"{$cor}\"><strong>De:</strong> {$iml}".GeraNickUsuario($pm['byuid'])." <strong>Para:</strong> {$iml2}".GeraNickUsuario($pm['touid'])."<br/>";
echo $bylnk;
echo "<strong>Assunto:</strong> ".TextoMensagens($pm['text'], $token)."<br/>";
$tmopm = date("d m y - h:i:s",$pm['timesent']);
echo " <small>{$tmopm}<br/>";
echo "</small></div>";
}
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=dialogo&amp;pagina=$ppagina&amp;token={$_GET["token"]}&amp;usuario={$_GET["usuario"]}\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=dialogo&amp;pagina=$npagina&amp;token={$_GET["token"]}&amp;usuario={$_GET["usuario"]}\">Proximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
$rets = "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"pagina\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"menu\" value=\"{$_GET["menu"]}\"/>";
$rets .= "<input type=\"hidden\" name=\"token\" value=\"{$_GET["token"]}\"/>";
$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"{$_GET["usuario"]}\"/>";
$rets .= "</form>";
echo $rets;
}
}else{
echo "<p align=\"center\">";
echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Não dados a serem vistos</div>";
}
echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=inicio&amp;token=$token\">Mensagem Privada</a><br/>";
echo "<a href=\"inicio.php?token={$_GET["token"]}\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
break;
endswitch;
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>