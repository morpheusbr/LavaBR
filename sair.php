<?php

include($_SERVER['DOCUMENT_ROOT']."/config/config.php");

if(GeraID(LimpaTexto($_GET["token"]))>0){

$a=$db->prepare("DELETE FROM fun_online WHERE usesala=:usesala");

$a->bindValue(':usesala',GeraID(LimpaTexto($_GET["token"])), PDO::PARAM_INT);

$a->execute();  

if($a->rowCount()>0){

$b=$db->prepare("UPDATE fun_users SET lastact=:lastact WHERE id=:id");

$b->bindValue(':lastact',time(), PDO::PARAM_INT);

$b->bindValue(':id',GeraID(LimpaTexto($_GET["token"])), PDO::PARAM_INT);

$b->execute();

$a=$db->prepare("DELETE FROM fun_ses WHERE uid=:uid");

$a->bindValue(':uid',GeraID(LimpaTexto($_GET["token"])), PDO::PARAM_INT);

$a->execute(); 

header('location: index.php');    

}else{

header('location: index.php');    

}  

}else{

header('location: index.php');    

}

?>