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
echo '<center><strong>Usuários Online</strong></center>';
AdicionarOnline(GeraID($token),"Vendo Usuarios Online","");
if($pagina=="" || $pagina<=0)$pagina=1;
$num_items = GeraNumeroOn();
$items_per_pagina= 10;
$num_paginas = ceil($num_items/$items_per_pagina);
if($pagina>$num_paginas)$pagina= $num_paginas;
$limit_start = ($pagina-1)*$items_per_pagina;
echo "<p>";
$items =$db->query("SELECT * FROM fun_online LIMIT $limit_start, $items_per_pagina");
while ($item = $items->fetch(PDO::FETCH_ASSOC)) {
if ($cor == "linha1"){$cor = "linha2";}else{$cor = "linha1";}
$lnk = "<div class=\"{$cor}\"><img align=\"left\" src=\"".GeraAvatar($item['usesala'])."\" width=\"40\" height=\"40\" /><a href=\"perfil.php?usuario={$item['usesala']}&amp;token={$token}\">".GeraNickUsuario($item['usesala'])."</a>";
echo "$lnk<br/><strong>Local:</strong> {$item['place']} </div>";
}
echo "</p>";
echo "<p align=\"center\">";
if($pagina>1)
{
$ppagina = $pagina-1;
echo "<a href=\"online.php?pagina=$ppagina&amp;token=$token\">&#171;Anterior</a> ";
}
if($pagina<$num_paginas)
{
$npagina = $pagina+1;
echo "<a href=\"online.php?pagina=$npagina&amp;token=$token\">Próximo&#187;</a>";
}
echo "<br/>$pagina/$num_paginas<br/>";
if($num_paginas>2)
{
echo PularPagina($menu, $token,"index");
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina Inicial</a>";
echo "</p>";
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>