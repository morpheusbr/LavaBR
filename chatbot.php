<?php
include($_SERVER['DOCUMENT_ROOT']."/inc/_inicio.php");
VerificaConexao();
VerificaBanIP();
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
$id=$_GET["id"];
$botid = "b7df63a24e363170";
$input = $_POST["input"];
$custid=$_POST["custid"];
$hostname = "www.pandorabots.com";
$hostpath = "/pandora/talk-xml";
echo "<p align=\"center\">";
echo "<br/>";
AdicionarOnline(GeraID($token),"Falando com Sistema","");
if ($input!="")
{
$sendData = "botid=".$botid."&input=".urlencode($input)."&custid=".$custid;
// Send the request to Pandorabot
$result = EnviarServidor($hostname, $hostpath, $sendData);
//TODO: Process the returned XML as an XML document instead of a big string.
// Use string manipulations to pull out the 'custid' and 'that' values.
$pos = strpos($result, "custid=\"");
// Extract the custid
if ($pos === false) {
$custid = "";
} else {
$pos += 8;
$endpos = strpos($result, "\"", $pos);
$custid = substr($result, $pos, $endpos - $pos);
}
// Extrat <that> - this is the reply from the Pandorabot
$pos = strpos($result, "<that>");
if ($pos === false) {
$reply = "";
} else {
$pos += 6;
$endpos = strpos($result, "</that>", $pos);
$reply = unhtmlspecialchars(substr($result, $pos, $endpos - $pos));
}
$hers = $reply;
$hers = TextoGeral($hers);
$input=TextoGeral($input);
$nick = GeraNickUsuario($usuario_id);
echo "<br/><b>$nick: </b>$input<br/>";
echo "<b>Sistema: </b>$hers<br/>";
echo "<form action=\"chatbot.php?token=$token\" method=\"post\">";
echo "<br/><input type=\"text\" name=\"input\" maxlength=\"120\" value=\"$input\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<br/>";
}else{
echo "Olá, agora você pode conversar com o nosso Sistema<br/>";
echo "<form action=\"chatbot.php?token=$token\" method=\"post\">";
echo "<input type=\"text\" name=\"input\" maxlength=\"120\" value=\"$input\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<br/>";
}
echo "<br/><br/><a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>Página Inicial</a><br/>";
echo "</p>";
function unhtmlspecialchars( $string )
{
$string = str_replace ( '&amp;', '&', $string );
$string = str_replace ( '&#039;', '\'', $string );
$string = str_replace ( '&quot;', '"', $string );
$string = str_replace ( '&lt;', '<', $string );
$string = str_replace ( '&gt;', '>', $string );
$string = str_replace ( '&uuml;', '?', $string );
$string = str_replace ( '&Uuml;', '?', $string );
$string = str_replace ( '&auml;', '?', $string );
$string = str_replace ( '&Auml;', '?', $string );
$string = str_replace ( '&ouml;', '?', $string );
$string = str_replace ( '&Ouml;', '?', $string );
return $string;
}
include($_SERVER['DOCUMENT_ROOT']."/inc/_fim.php");
?>