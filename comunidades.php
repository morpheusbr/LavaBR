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

switch (LimpaTexto($_GET["menu"])):

default:

AdicionarOnline(GeraID($token),"Menu Comunidades","");

echo "<center><b><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Menu Comunidades</b></center>";

echo "<div class=\"linha1\"><a href=\"{$_SERVER["PHP_SELF"]}?menu=todas&amp;token=$token\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Todas Comunidades</a></div>";

echo "<div class=\"linha2\"><a href=\"sistema.php?menu=myclub&amp;token=$token\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Minhas Comunidades</a></div>";

echo "<div class=\"linha1\"><a href=\"lists.php?menu=clm&amp;usuario=$myid&amp;token=$token&amp;usuario=$usuario_id\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Comunidades que participo</a></div>";

echo "<div class=\"linha2\"><a href=\"lists.php?menu=pclb&amp;token=$token&amp;usuario=$usuario_id\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Comunidades mais populares</a></div>";

echo "<div class=\"linha1\"><a href=\"lists.php?menu=aclb&amp;token=$token&amp;usuario=$usuario_id\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Comunidades com mais atividade</a></div>";

echo "<div class=\"linha2\"><a href=\"lists.php?menu=rclb&amp;token=$token&amp;usuario=$usuario_id\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Comunidades Aleatória</a><br/></div>";

$a=$db->prepare('SELECT * FROM fun_clubs ORDER BY created DESC LIMIT 1'); 

$a->execute();

$dados=$a->fetchObject();

echo "<div class=\"linha1\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Comunidade Mais Nova: <a href=\"{$_SERVER["PHP_SELF"]}?menu=ver&amp;id=$dados->id&amp;token=$token\">".TextoGeral($dados->name)."</a></div>";

break;

case 'todas';

AdicionarOnline(GeraID($token),"Vendo Comunidades","");

echo "<center><b>Todas Comunidades</b></center>";

if($pagina=="" || $pagina<=0)$pagina=1;

$a=$db->prepare("SELECT * FROM fun_clubs");

$a->execute();

$num_items = $a->rowCount();

$items_per_pagina= 5;

$num_paginas = ceil($num_items/$items_per_pagina);

if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;

$limit_start = ($pagina-1)*$items_per_pagina;

//changable sql

$sql = "SELECT * FROM fun_clubs ORDER BY created DESC LIMIT $limit_start, $items_per_pagina";

echo "<p>";

$items = $db->query($sql);

if($a->rowCount()>0)

{

while($item = $items->fetch(PDO::FETCH_OBJ)) {

$b=$db->prepare("SELECT * FROM fun_clubmembers WHERE clid=:clid AND accepted=:accepted"); 

$b->bindValue(':clid',$item->id,PDO::PARAM_INT);

$b->bindValue(':accepted',1,PDO::PARAM_INT);

$b->execute();

if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}

echo "<div class=\"{$cor}\"><a href=\"{$_SERVER["PHP_SELF"]}?menu=ver&amp;id=$item->id&amp;token=$token\">$item->name(".$b->rowCount().")</a> Proprietário: ".GeraNickUsuario($item->owner)."<br/>".TextoGeral($item->description)."<br/>Data de Criação: (".date("d/m/y", $item->created).")</div>";

}

}

echo "<p align=\"center\">";

if($pagina>1)

{

$ppagina = $pagina-1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=$menu&amp;pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";

}

if($pagina<$num_paginas)

{

$npagina = $pagina+1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?menu=$menu&amp;pagina=$npagina&amp;token=$token\">Próxima&#187;</a>";

}

echo "<br/>$pagina/$num_paginas<br/>";

if($num_paginas>2)

{

$rets = "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";

$rets .= "Pular Pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";

$rets .= "<input type=\"submit\" value=\"IR\"/>";

$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";

$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";

$rets .= "</form>";

echo $rets;

}

echo '</p>';

break;

case 'ver';

$id = $_GET["id"];

$a=$db->prepare('SELECT * FROM fun_clubs WHERE id=:id'); 

$a->bindValue(':id',$id,PDO::PARAM_INT);

$a->execute();

$dados=$a->fetchObject();

AdicionarOnline(GeraID($token),"Vendo Comunidade","");

$clnm = TextoGeral($dados->name);

echo "<center><b>$clnm</b><br/>";

if(trim($dados->logo)=="")

{

echo "<img src=\"images/logo.png\" alt=\"logo\"/>";

}else{

echo "<img src=\"$dados->logo\" alt=\"logo\"/>";

}

echo '</center>';

echo "<div class=\"linha1\">ID da Comunidade: <b>{$id}</b></div>";

$b=$db->prepare('SELECT * FROM fun_clubmembers WHERE clid=:clid AND uid=:uid AND accepted=:accepted'); 

$b->bindValue(':clid',$id,PDO::PARAM_INT);

$b->bindValue(':uid',$usuario_id,PDO::PARAM_INT);

$b->bindValue(':accepted',1,PDO::PARAM_INT);

$b->execute();

echo "<div class=\"linha2\">Proprietário: ".GeraNickUsuario($dados->owner)."</div>";

$c=$db->prepare('SELECT * FROM fun_clubmembers WHERE clid=:clid AND accepted=:accepted'); 

$c->bindValue(':clid',$id,PDO::PARAM_INT);

$c->bindValue(':accepted',1,PDO::PARAM_INT);

$c->execute();

echo "<div class=\"linha1\">Membros: <a href=\"lists.php?menu=clmem&amp;token=$token&amp;clid=$id\">(".$c->rowCount().")</a></div>";

echo "<div class=\"linha2\">Data de Criação: ".date("d/m/y", $dados->created)."</div>";

echo "<div class=\"linha1\">Pontos: $dados->plusses</div>";

$d=$db->prepare('SELECT * FROM fun_forums WHERE clubid=:clubid'); 

$d->bindValue(':clubid',$id,PDO::PARAM_INT);

$d->execute();

$forum=$d->fetchObject();

$e=$db->prepare('SELECT * FROM fun_rooms WHERE clubid=:clubid'); 

$e->bindValue(':clubid',$id,PDO::PARAM_INT);

$e->execute();

$chat=$e->fetchObject();

$f=$db->prepare('SELECT * FROM fun_topics WHERE fid=:fid'); 

$f->bindValue(':fid',$forum->id,PDO::PARAM_INT);

$f->execute();

$pss = $db->query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$forum->id."'")->fetch();

if(($b->rowCount()>0)||Moderador($usuario_id))

{

$g=$db->prepare('SELECT * FROM fun_announcements WHERE clid=:clid'); 

$g->bindValue(':clid',$id,PDO::PARAM_INT);

$g->execute();

echo "<br/><a href=\"lists.php?menu=annc&amp;token=$token&amp;clid=$id\"><img src=\"images/annc.gif\" alt=\"!\"/>Anúncios(".$g->rowCount().")</a><br/>";

$h=$db->prepare('SELECT * FROM fun_chat WHERE sala=:sala'); 

$h->bindValue(':sala',$chat->id,PDO::PARAM_INT);

$h->execute();

echo "<a href=\"chat.php?token=$token&amp;sala=$sala[0]\"><img src=\"images/chat.gif\" alt=\"*\"/>$clnm Chat(".$h->rowCount().")</a><br/>";

echo "<a href=\"sistema.php?menu=ver_f&amp;token=$token&amp;fid=$fid[0]\"><img src=\"images/1.gif\" alt=\"*\"/>$clnm Forum(".$f->rowCount()."/$pss[0])</a><br/><br/>";

$j=$db->prepare('SELECT * FROM fun_clubmembers WHERE clid=:clid AND uid=:uid'); 

$j->bindValue(':clid',$id,PDO::PARAM_INT);

$j->bindValue(':uid',GeraID($token),PDO::PARAM_INT);

$j->execute();

if($j->rowCount()>0)

{

if($dados->owner!=$usuario_id)

{

echo "<a href=\"genproc.php?menu=unjc&amp;token=$token&amp;clid=$id\">Sair da Comunidade</a>";

}

}else{

echo "<a href=\"genproc.php?menu=reqjc&amp;token=$token&amp;clid=$id\">Entrar na Comunidade!</a>";

}

if(Administrador(GeraID($token)))

{

echo "<br/><a href=\"painel_admin.php?menu=club&amp;token=$token&amp;clid=$id\">Painel do Admin</a>";

}

if($dados->owner==$usuario_id)

{

$m=$db->prepare('SELECT * FROM fun_clubmembers WHERE clid=:clid AND accepted=:accepted'); 

$m->bindValue(':clid',$id,PDO::PARAM_INT);

$m->bindValue(':accepted',0,PDO::PARAM_INT);

$m->execute();

if($m->rowCount()>0){

echo "<br/><a href=\"lists.php?menu=clreq&amp;token=$token&amp;clid=$id\">&#187;Solicitações(".$m->rowCount().")</a><br/>";    

}

}

}else{

echo "Topicos: <b>".$f->rowCount()."</b>, Posts: <b>$pss[0]</b><br/>";

echo "<b>Descrição:</b><br/>";

echo TextoGeral($dados->description);

echo "<br/><br/>";

echo "<b>Regras:</b><br/>";

echo TextoGeral($dados->rules);

echo "<br/><br/>";

echo "Você Gostou? Está esperando oque clique  <a href=\"genproc.php?menu=reqjc&amp;token=$token&amp;clid=$id\">aqui</a> para entrar.";

}

echo "<center><a href=\"{$_SERVER["PHP_SELF"]}?token=$token\"><img src=\"images/group.gif\" width=\"16\" height=\"16\" alt=\"*\"/>Lista de Comunidades</a></center>";

break;

endswitch;

echo "<center><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>Pagina Inicial</a></center>";

include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");

?>