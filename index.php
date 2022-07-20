<?php 
session_start ();
$host = "localhost";
$user = "wp_website_user";
$passwort = "WEBproGr@MmInG";
$datenbank_name = "vhb_wp_website";

$connect = mysqli_connect($host,$user,$passwort,"$datenbank_name");
if(!$connect){
	die('Could not Connect MySql Server:' );
}
mysqli_query($connect, "SET NAMES 'utf8'");
include ('formsubmit.inc.php');
?>


<!DOCTYPE html>
<html>
<head>
<title>Meine erste Website</title>
<meta charset="utf-8" />
<link rel="stylesheet" href="styles/style.css"/>
</head>
<body>
<nav>
<ul>
	<li><a href="index.php" <?php if((isset($_GET["page"]) AND $_GET["page"] == "startseite") OR !isset($_GET["page"])) { echo ' class="active"'; } ?> >Startseite</a></li>
	<li><a href="index.php?page=food" <?php if((isset($_GET["page"]) AND $_GET["page"] == "food")) { echo ' class="active"'; } ?>>Food</a>
	<ul class="submenu">
		<li><a href="index.php?page=food&food=deftiges">Deftiges</a></li>
		<li><a href="index.php?page=food&food=snacks">Snacks</a></li>
		<li><a href="index.php?page=food&food=nachtisch">Nachtisch</a></li>
		<li><a href="index.php?page=food&food=getraenke">Getränke</a></li>
	</ul>
	</li>
	<li><a href="index.php?page=standorte" <?php if((isset($_GET["page"]) AND $_GET["page"] == "standorte")) { echo ' class="active"'; } ?>>Standort</a></li>
	<li><a href="index.php?page=kontakt" <?php if((isset($_GET["page"]) AND $_GET["page"] == "kontakt")) { echo ' class="active"'; } ?>>Kontakt</a></li>
	
	<?php
	if(isset($_SESSION['warenkorb']) AND array_sum($_SESSION['warenkorb']) > 0)
	{
		echo '<li><a href="index.php?page=warenkorb" '; if((isset($_GET["page"]) AND $_GET["page"] == "warenkorb")) { echo ' class="active"'; } echo '>Warenkorb</a></li>';
	}
	?>
	
</ul>
</nav>

<?php
if(isset($_GET["page"]))
{
	$pages = array("food","standorte","kontakt","warenkorb","impressum","agb");
	
	if(in_array($_GET["page"], $pages))
	{
		include($_GET["page"].".inc.php");
	}
}
else
{
	include("startseite.inc.php");
}
?>

<footer>
<p>&copy; 2016 - <a href="mailto:vhb@profthome.de">vhb - Web-Programming</a></p>
<ul>
<li><a href="index.php?page=impressum">Impressum</a></li>
<li><a href="index.php?page=agb"> AGB</a></li>
</ul>
</footer>

</body>
</html>