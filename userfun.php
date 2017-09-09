<?php


include("core.php");
include("config.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

	echo "<head>";

	echo "<title>{$config['TITULO_SITE']}</title>";
	echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"estilo/padrao.css\" />";
	echo "</head>";

	echo "<body>";
$conecta = BancoDeDados();
if (!$conecta)
{
    
    echo "<p align=\"center\">";
    echo "<img src=\"images/exit.gif\" alt=\"*\"/><br/>";
    echo "ERROR! cannot connect to database<br/><br/>";
    echo "This error happens usually when backing up the database, please be patient, The site will be up any minute<br/><br/>";
   
    echo "<b>THANK YOU VERY MUCH</b>";
    echo "</p>";
    exit();
}
$nav = explode(" ",$_SERVER['HTTP_USER_AGENT']);
$navegador = $nav[0];
$uip = GeraIP();
$menu = $_GET["menu"];
$token = $_GET["token"];
$pagina = $_GET["pagina"];
$usuario = $_GET["usuario"];

$usuario_id = GeraID($token);

LimpaDados();
if(VerificaIpBan($uip,$navegador))
    {
      if(!Protegido(GeraID($token)))
      {
        
      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "This IP address is blocked<br/>";
      echo "<br/>";
      echo "How ever we grant a shield against IP-Ban for our great users, you can try to see if you are shielded by trying to log-in, if you kept coming to this pagina that means you are not shielded, so come back when the ip-ban period is over<br/><br/>";
      $banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty='2' AND ipadd='".$uip."' AND browserm='".$navegador."' LIMIT 1 "));
      //echo mysql_error();
      $reinicio =  $banto[0] - time();
      $rmsg = GeraTextoTempo($reinicio);
      echo " IP: $rmsg<br/><br/>";
      
      echo "</p>";
      echo "<p>";
  echo "Usesala: <input name=\"loguid\" format=\"*x\" maxlength=\"30\"/><br/>";
  echo "Password: <input type=\"password\" name=\"logpwd\"  maxlength=\"30\"/><br/>";
  echo "<anchor>LOGIN<go href=\"login.php\" method=\"get\">";
  echo "<postfield name=\"loguid\" value=\"$(loguid)\"/>";
  echo "<postfield name=\"logpwd\" value=\"$(logpwd)\"/>";
  echo "</go></anchor>";
  echo "</p>";
      exit();
      }
    }
if(($menu != "") && ($menu!="terms"))
{
    $usuario_id = GeraID($token);
    if((Logado($token)==false)||($usuario_id==0))
    {
        
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "<a href=\"sistema.php\">Login</a>";
      echo "</p>";
      exit();
    }
    
    
    
}
//echo VerificaBan($usuario_id);
if(VerificaBan($usuario_id))
    {
        
      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "You are <b>Banned</b><br/>";
      $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='".$usuario_id."' AND penalty='1'"));
	  $banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='".$usuario_id."'"));
	  
      $reinicio = $banto[0]- time();
      $rmsg = GeraTextoTempo($reinicio);
      echo "Time to finish your penalty: $rmsg<br/><br/>";
	  echo "Ban Reason: $banres[0]";
      //echo "<a href=\"sistema.php\">Login</a>";
      echo "</p>";
      exit();
    }
	$res = mysql_query("UPDATE fun_users SET browserm='".$navegador."', ipadd='".$uip."' WHERE id='".GeraID($token)."'");
	$wnick = GeraNickUsuario($usuario);
	$sex = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$usuario."'"));
	if($sex[0]=="M")
	{
		$pron = "he";
		$pron2 = "him";
		$pron3 = "his";
	}else{
		$pron = "she";
		$pron2 = "her";
		$pron3 = "her";
	}
	AdicionarOnline($usuario_id,"having fun with another member :P","");
if($menu=="profile")
{
	
    
    
    echo "<p><small>";
    $nopl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$usuario."'"));
  echo "Game Plusses: <b>$nopl[0]</b><br/><br/>";
  
  ///////////////////////////////////////////////////////
	echo "<img src=\"smilies/smooch.gif\" alt=\"smooch\"/><b>Smooch's:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='smooch'"));
  echo "Have smooched: <b><a href=\"lists.php?menu=smc&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='smooch'"));
  echo "Have been Smooched: <b><a href=\"lists.php?menu=smd&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  echo "Poor $wnick, a fat old lady have smooched $pron2 untill $pron almost choked! yes you can smooch $wnick but don't kill $pron2<br/>";
	echo "<a href=\"userfun.php?menu=smooch&amp;usuario=$usuario&amp;token=$token\">Smooch!</a><br/><br/>";
  
  //////////////////////////////////////////////////////
  echo "<img src=\"smilies/kick.gif\" alt=\"kick\"/><b>Kicks:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='kick'"));
  echo "Have Kicked: <b><a href=\"lists.php?menu=kck&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='kick'"));
  echo "Have been Kicked: <b><a href=\"lists.php?menu=kcd&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  echo "And yes $wnick have been kicked on the shin untill it's smashed, I think it'll be funny to kick $wnick on the chin hehe<br/>";
	echo "<a href=\"userfun.php?menu=kick&amp;usuario=$usuario&amp;token=$token\">Kick!</a><br/><br/>";
	
	///////////////////////////////////////////////////////
	echo "<img src=\"smilies/poke.gif\" alt=\"poke\"/><b>Pokes:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='poke'"));
  echo "Have Poked: <b><a href=\"lists.php?menu=pok&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='poke'"));
  echo "Have been Poked: <b><a href=\"lists.php?menu=pkd&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  echo "the last thing that $wnick needs now is another poke, $pron have a hole in $pron3 tummy because of the last poke, and no the other tokene of the hole is not in $pron3 back, some of us are obsessed with butts you know<br/>";
	echo "<a href=\"userfun.php?menu=poke&amp;usuario=$usuario&amp;token=$token\">Poke!</a><br/><br/>";
	
	///////////////////////////////////////////////////////
	echo "<img src=\"smilies/cuddle.gif\" alt=\"hug\"/><b>Hugs:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$usuario."' AND menu='hug'"));
  echo "Have Hugged: <b><a href=\"lists.php?menu=hgs&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$usuario."' AND menu='hug'"));
  echo "Have been Hugged: <b><a href=\"lists.php?menu=hgd&amp;usuario=$usuario&amp;token=$token\">$nopl[0]</a></b> Times<br/>";
  echo "Poor $wnick, remember that fat lady usuario choked $pron2? well.. she hugged $pron2 untill she broke $pron3 ribs, $pron surely needs a hug from you now<br/>";
	echo "<a href=\"userfun.php?menu=hug&amp;usuario=$usuario&amp;token=$token\">Hug!</a>";
	
    echo "</small></p>";

    echo "<p align=\"center\">";
	echo "<a href=\"sistema.php?menu=givegp&amp;usuario=$usuario&amp;token=$token\">Donate Game Plusses</a><br/>";
	echo "<a href=\"perfil.php?usuario=$usuario&amp;token=$token\">";
echo "$wnick's profile</a><br/>";
    echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if ($menu=="hug" || $menu=="smooch" || $menu=="kick" || $menu=="poke")
{
	
    echo "<p align=\"center\">";
	$nopl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$usuario_id."'"));
	if($nopl[0]<5)
	{
		echo "<img src=\"images/notok.gif\" alt=\"X\"/>You should have at least 5 game plusses to perform an menu on other members<br/><br/>";
	}else{
		$actime = mysql_fetch_array(mysql_query("SELECT actime FROM fun_usfun WHERE uid='".$usuario_id."' AND target='".$usuario."' ORDER BY actime DESC LIMIT 1"));
		$timeout = $actime[0] + (10*24*60*60);
		if(time()<$timeout)
		{
			echo "<img src=\"images/notok.gif\" alt=\"X\"/>You can only perform one menu on the same user every 10 days<br/><br/>";
		}else{
			if($usuario_id==$usuario)
			{
				echo "<img src=\"images/notok.gif\" alt=\"X\"/>Why on earth you wanna $menu your self?<br/><br/>";
			}else{
				$res = mysql_query("INSERT INTO fun_usfun SET uid='".$usuario_id."', menu='".$menu."', target='".$usuario."', actime='".time()."'");
				if(!$res)
				{
					echo mysql_error()."<br/>";
					echo "<img src=\"images/notok.gif\" alt=\"X\"/>DATABASE ERROR!<br/><br/>";
				}else{
					mysql_query("UPDATE fun_users SET gplus=gplus-5 WHERE id='".$usuario_id."'");
					echo "<img src=\"images/ok.gif\" alt=\"+\"/>You just have ".$menu."ed $wnick, where did you do that, I'm not gonna tell <img src=\"smilies/spiteful.gif\" alt=\"haba\"/><br/><br/>";
					echo "5 game plusses were subtracted from you, and you can't perform any other menu on $wnick for the next 10 days<br/><br/>";
				}
			}
		}
		
	}
	echo "<a href=\"sistema.php?menu=givegp&amp;usuario=$usuario&amp;token=$token\">Donate Game Plusses</a><br/>";
	echo "<a href=\"perfil.php?usuario=$usuario&amp;token=$token\">";
echo "$wnick's profile</a><br/>";
    echo "<a href=\"inicio.php?token=$token\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
	echo "</body>";
	echo "</html>";
?>