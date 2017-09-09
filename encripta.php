<?php

include($_SERVER['DOCUMENT_ROOT']."/config/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" AND $_POST['protecao']==$_SESSION['protecao']) {

$usuario=base64_encode(LimpaTexto($_POST['usuario']));

$senha=base64_encode(LimpaTexto($_POST['senha']));

header('location: logar.php?usuario='.$usuario.'&senha='.$senha);  

exit();

}else{

header('location: index.php');

exit();    

}

unset($_SESSION['protecao']);

?>