<?php

/**

 * @author FABIO VIEIRA

 * @copyright 2015

 */

include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");

VerificaBanIP();

LimpaDados();

VerificaBanNick();

$menu = LimpaTexto($_GET["menu"]);

$token = LimpaTexto($_GET["token"]);

$pagina = LimpaTexto($_GET["pagina"]);

$usuario = LimpaTexto($_GET["usuario"]);

switch (LimpaTexto($_GET["menu"])):

default:

AdicionarOnline(GeraID($token),"Vendo Recados","");

echo '<center><strong>Lista de Recados</strong></center>';

if(GeraPontos(GeraID($token))<10)

{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Você precisa de no mínimo 10 pontos para postar em nosso mural</div>";

}else{

echo "<div class=\"atencao\"><img src=\"images/point.gif\" alt=\"X\"/>Não use nosso mural para realizar flood, mensagens ofensivas, fazer spam ou qualquer outra ação que infrinja nossas regras.</div>";

$pro = md5(uniqid(rand(), true));

$_SESSION['protecao'] = $pro;

echo "<form action=\"{$_SERVER["PHP_SELF"]}?menu=enviar&amp;token=$token\" method=\"post\">";

echo "Texto:<input name=\"texto\" maxlength=\"100\"/>";

echo '<input type="hidden" name="protecao" value="'.$pro.'" />';

echo "<input type=\"submit\" valie=\"Enviar\"/>";    

echo "</form>";

}

if($pagina=="" || $pagina<=0)$pagina=1;

if($usuario=="")

{

$a=$db->prepare("SELECT * FROM fun_shouts");

$a->execute();

}else{

$a=$db->prepare("SELECT * FROM fun_shouts WHERE shouter=:shouter");

$a->bindValue(':shouter',$usuario, PDO::PARAM_INT);

$a->execute();

}

$num_items = $a->rowCount();

$items_per_pagina= 10;

$num_paginas = ceil($num_items/$items_per_pagina);

if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;

$limit_start = ($pagina-1)*$items_per_pagina;

if($usuario<1)

{

$sql = "SELECT * FROM fun_shouts ORDER BY shtime DESC LIMIT $limit_start, $items_per_pagina";

}else{

$sql = "SELECT * FROM fun_shouts  WHERE shouter='".$usuario."'ORDER BY shtime DESC LIMIT $limit_start, $items_per_pagina";

}

$itens = $db->query($sql);

if($a->rowCount()>0)

{

while($item = $itens->fetch(PDO::FETCH_OBJ)) {

$shnick ="<img align=\"left\" src=\"".GeraAvatar($item->shouter)."\" width=\"60\" height=\"60\" />".GeraNickUsuario($item->shouter);

if(Moderador(GeraID($token)))

{

$del = "<a href=\"{$_SERVER["PHP_SELF"]}?menu=deletar&amp;token=$token&amp;id=$item->id\">[Remover]</a>";

}else{

$del = "";

}

if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}

echo "<div class=\"{$cor}\"><img align=\"left\" src=\"".GeraAvatar($item->shouter)."\" width=\"60\" height=\"60\" />".GeraNickUsuario($item->shouter).":<br/>".TextoGeral($item->shout,$token)."<br/><small><strong>Data:</strong> ".date("d/m/Y - H:i:s", $item->shtime)." {$del}</small></div>";

}

}

echo "<center>";

if($pagina>1)

{

$ppagina = $pagina-1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?pagina=$ppagina&amp;token=$token&amp;usuario=$usuario\">&#171;Anterior</a> ";

}

if($pagina<$num_paginas)

{

$npagina = $pagina+1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?pagina=$npagina&amp;token=$token&amp;usuario=$usuario\">Proximo&#187;</a>";

}

echo "<br/>$pagina/$num_paginas<br/>";

if($num_paginas>2)

{

$rets = "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";

$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";

$rets .= "<input type=\"submit\" value=\"IR\"/>";

$rets .= "<input type=\"hidden\" name=\"usuario\" value=\"$usuario\"/>";

$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";

$rets .= "</form>";

echo $rets;

}

echo '</center>';

break;

case 'deletar':

if(Moderador(GeraID($token)))

{

AdicionarOnline(GeraID($token),"Removendo Recado","");

$shid = $_GET["id"];

$a=$db->prepare('SELECT * FROM fun_shouts WHERE id=:id');

$a->bindValue(':id', $shid, PDO::PARAM_INT);

$a->execute();

$dados=$a->fetchObject();

$msg = GeraNickUsuario($dados->shouter);

$msg .= ":".TextoGeral((strlen($dados->shout)<20?$dados->shout:substr($dados->shout, 0, 20)));

$b=$db->prepare('DELETE FROM fun_shouts WHERE id=:id');

$b->bindValue(':id', $shid, PDO::PARAM_INT);

$b->execute();

if($b->rowCount()>0)

{

$c=$db->prepare("INSERT INTO fun_mlog SET menu=:menu, details=:details, actdt=:actdt");

$c->bindValue(':menu','Recados', PDO::PARAM_STR);

$c->bindValue(':details',GeraNickUsuario(GeraID($token)).'removeu a mensagem de <b>'.$shid.'</b> - '.$msg.'', PDO::PARAM_STR);

$c->bindValue(':actdt',time(), PDO::PARAM_INT);

$c->execute();

echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Recado Removido</div>";

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Erro</div>";

}

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida!!</div>";    

}

break;

case'enviar':

AdicionarOnline(GeraID($token),"Postando Recado","");

if(LimpaTexto($_POST['protecao'])==$_SESSION['protecao']){

unset($_SESSION['protecao']);

if(GeraPontos(GeraID($token))<10)

{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Voce Precisa ter no minimo 10 pontos para postar no mural de recados!</div>";

}else{

$a=$db->prepare("INSERT INTO fun_shouts SET shout=:shout, shouter=:shouter, shtime=:shtime");

$a->bindValue(':shout',LimpaTexto($_POST["texto"]), PDO::PARAM_STR);

$a->bindValue(':shouter',GeraID($token), PDO::PARAM_INT);

$a->bindValue(':shtime',time(), PDO::PARAM_INT);

$a->execute();

if($a->rowCount()>0)

{

$b=$db->prepare("SELECT * from fun_users WHERE id='".$usuario_id."'");

$b->bindValue(':id',GeraID($token), PDO::PARAM_INT);

$b->execute();

$dados=$b->fetchObject();

$shts = $dados->shouts+1;

$c=$db->prepare("UPDATE fun_users SET shouts=:shouts WHERE id=:id");

$c->bindValue(':shouts',$shts, PDO::PARAM_INT);

$c->bindValue(':id',GeraID($token), PDO::PARAM_INT);

$c->execute();

echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Recado Enviado com Sucesso!<br/><a href=\"{$_SERVER["PHP_SELF"]}?token={$_GET["token"]}\">Continuar...</a></div>";

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Erro</div>";

}

}

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida!!</div>";    

}

break;

endswitch;

echo "<center><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>Pagina Inicial</a></center>";

include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");

?>