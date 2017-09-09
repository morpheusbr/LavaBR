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

AdicionarOnline(GeraID($token),"vendo Lista de Smilies","");

echo '<center><strong>Lista de Smilies</strong></center>';

if($pagina=="" || $pagina<=0)$pagina=1;

$a=$db->prepare("SELECT * FROM fun_smilies");

$a->execute();

$num_items = $a->rowCount();

$items_per_pagina= 5;

$num_paginas = ceil($num_items/$items_per_pagina);

if(($pagina>$num_paginas)&&$pagina!=1)$pagina= $num_paginas;

$limit_start = ($pagina-1)*$items_per_pagina;

$sql = "SELECT * FROM fun_smilies ORDER BY id DESC LIMIT $limit_start, $items_per_pagina";

$items =$db->query($sql);

if($a->rowCount()>0)

{

while($item = $items->fetch(PDO::FETCH_OBJ)) {

if(Administrador(GeraID($token)))

{

$del= "<a href=\"{$_SERVER["PHP_SELF"]}?menu=remover&amp;token=$token&amp;id=$item->id\">[x]</a>";

}else{

$del= "";

}

if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}

echo "<div class=\"{$cor}\">$item->scode &#187; ";

echo "<img src=\"$item->imgsrc\" alt=\"$item->scode\"/> $del</div>";

}

}

echo "<p align=\"center\">";

if($pagina>1)

{

$ppagina = $pagina-1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";

}

if($pagina<$num_paginas)

{

$npagina = $pagina+1;

echo "<a href=\"{$_SERVER["PHP_SELF"]}?pagina=$npagina&amp;token=$token\">Proximo&#187;</a>";

}

echo "<br/>$pagina/$num_paginas<br/>";

if($num_paginas>2)

{

$rets = "<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\">";

$rets .= "Pular pagina<input name=\"pagina\" format=\"*N\" size=\"3\"/>";

$rets .= "<input type=\"submit\" value=\"IR\"/>";

$rets .= "<input type=\"hidden\" name=\"menu\" value=\"$menu\"/>";

$rets .= "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";

$rets .= "</form>";

echo $rets.'</p>';

}

break;

case 'remover':

AdicionarOnline(GeraID($token),"Removendo Smilies","");

if(Administrador(GeraID($token)))

{

$smid = $_GET["id"];

$a=$db->prepare('DELETE FROM fun_smilies WHERE id=:id');

$a->bindValue(':id', $smid, PDO::PARAM_INT);

$a->execute();

if($a->rowCount()>0)

{

echo "<div class=\"ok\"><img src=\"images/ok.gif\" alt=\"O\"/>Smilie removido com sucesso</div>";

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Erro ao remover Smilie</div>";

}

}else{

echo "<div class=\"erro\"><img src=\"images/notok.gif\" alt=\"X\"/>Ação não permitida!</div>";    

}

break;

endswitch;

echo "<center><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>Pagina Inicial</a></center>";

include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");

?>