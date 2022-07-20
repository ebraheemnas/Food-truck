<?php
if(isset($_SESSION['warenkorb']) AND array_sum($_SESSION['warenkorb']) > 0)
{
?>

<h1>Warenkorb</h1>

<?php
if(isset($bestellung_abgeschlossen) AND $bestellung_abgeschlossen == true)
{
	echo '<div class="hinweis">';
	echo 'Deine Bestellung wurde erfolgreich an uns übermittelt. Du erhältst in Kürze eine Bestellbestätigung per E-Mail';
	unset($_SESSION['warenkorb']);
	echo '</div>';
}
else
{
?>

<?php
if(isset($fehler) AND is_array($fehler))
{
	echo '<div class="hinweis">';
	foreach($fehler as $fehler_item)
	{
		echo $fehler_item;
		echo "<br/>";
	}
	echo '</div>';
}

?>


<form method="post" action="" novalidate="novalidate">
<table id="bestellformular">
<tr>
	<th>Vorname</th>
	<td><input type="text" name="vorname" value="<?php if(isset($_SESSION["vorname"])) { echo $_SESSION["vorname"]; } ?>" /></td>
</tr>
<tr>
	<th>Nachname</th>
	<td><input type="text" name="nachname" value="<?php if(isset($_SESSION["nachname"])) { echo $_SESSION["nachname"]; } ?>" /></td>
</tr>
<tr>
	<th>E-Mail-Adresse</th>
	<td><input type="email" name="email" value="<?php if(isset($_SESSION["email"])) { echo $_SESSION["email"]; } ?>" /></td>
</tr>
<tr>
	<th>Firma</th>
	<td><input type="text" name="firma" value="<?php if(isset($_SESSION["firma"])) { echo $_SESSION["firma"]; } ?>" /></td>
</tr>

<?php
$keys = array_keys($_SESSION['warenkorb']); // Key auslesen
$keys_string = implode(",",$keys);

$abfrage = "SELECT id, beschreibung, preis FROM food WHERE id IN (".$keys_string.")";
$ergebnis = mysqli_query($connect, $abfrage);
while($row = mysqli_fetch_assoc($ergebnis))
{
	echo '<tr>';
	echo '<th>'.$row["beschreibung"].'</th>';
	echo '<td>';
	echo '<select name="anzahl['.$row["id"].']">';
	for($i=0;$i<=10;$i++)
	{
		echo '<option value="'.$i.'"';
		if($_SESSION["warenkorb"][$row["id"]] == $i)
		{
			echo ' selected="selected" ';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select>';
	echo '</td>';
	echo '</tr>';
}

?>


<tr>
	<th>Anmerkungen zur Bestellung</th>
	<td><textarea name="anmerkungen"><?php if(isset($_SESSION["anmerkungen"])) { echo $_SESSION["anmerkungen"]; } ?></textarea></td>
</tr>
<tr>
	<th>Für welche/n Tag/e möchten Sie Ihre Bestellung aufgeben?</th>
	<td>
		<input type="checkbox" name="wochentag[]" value="1"/> Montag<br/>
		<input type="checkbox" name="wochentag[]" value="2"/> Dienstag<br/>
		<input type="checkbox" name="wochentag[]" value="3"/> Mittwoch<br/>
		<input type="checkbox" name="wochentag[]" value="4"/> Donnerstag<br/>
		<input type="checkbox" name="wochentag[]" value="5"/> Freitag<br/>
	</td>
</tr>
<tr>
	<th>Sollen wir Ihre Bestellung einpacken?</th>
	<td>
		<input type="radio" name="einpacken" value="1" /> ja <br/>
		<input type="radio" name="einpacken" value="0" /> nein <br/>
	</td>
</tr>
<tr>
	<td colspan="2"><input name="submit_bestellung" id="submit" type="submit" value="Kostenpflichtig bestellen"/></td>
</tr>
</table>
<input type="hidden" name="warenkorb_aktualisierung" value="true" />
</form>

<?php
}
}
else
{
	header('Location: index.php');
}
?>