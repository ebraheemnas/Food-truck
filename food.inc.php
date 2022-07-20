<h1>Food</h1>

<?php
if(isset($_SESSION['warenkorb']) AND array_sum($_SESSION['warenkorb']) > 0)
{
	echo '
	<form method="post">
	<input type="submit" value="Warenkorb leeren" name="loeschen" />
	</form>
	';
}
?>

<form method="post">
<table>
<tr>
	<th></th>
	<th>Produktname</th>
	<th>Beschreibung</th>
	<th>Vegan</th>
	<th>Vegetarisch</th>
	<th>Preis</th>
	<th>Bestellen</th>
</tr>

<?php
$kategorie_id = 0;
if(isset($_GET["food"]))
{
	$abfrage_alias_id = "SELECT id FROM kategorien WHERE alias = '".$_GET["food"]."'";
	$ergebnis_alias_id = mysqli_query($connect, $abfrage_alias_id);
	while($row_alias_id = mysqli_fetch_assoc($ergebnis_alias_id))
	{
		$kategorie_id = $row_alias_id["id"];
	}
}
	
if($kategorie_id == 0)
{
	$abfrage_kategorie = "SELECT id, bezeichnung, alias FROM kategorien";
}
else
{
	$abfrage_kategorie = "SELECT id, bezeichnung, alias FROM kategorien WHERE id = ".$kategorie_id."";
}
$ergebnis_kategorie = mysqli_query($connect, $abfrage_kategorie);
while($row_kategorie = mysqli_fetch_assoc($ergebnis_kategorie))
{
	echo '
	<tr>
		<th class="titel" id="'.$row_kategorie["alias"].'">'.$row_kategorie["bezeichnung"].'</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
	</tr>	
	';
	
	$abfrage_food = "SELECT id, kategorie, bild, beschreibung, inhaltsstoffe, vegan, vegetarisch, preis FROM food WHERE kategorie = ".$row_kategorie["id"]."";
	$ergebnis_food = mysqli_query($connect, $abfrage_food);
	while($row_food = mysqli_fetch_assoc($ergebnis_food))
	{
		echo '
		<tr>
			<td><img src="bilder/'.$row_kategorie["alias"].'/'.$row_food["bild"].'.jpg" height="200px" width="200px"></td>
			<td class="bezeichnung">'.$row_food["beschreibung"].'</td>
			<td>'.$row_food["inhaltsstoffe"].'</td>
			';
			
			if($row_food["vegan"] == 1)
			{
				echo '
				<td>ja</td>
				';
			}
			else
			{
				echo '
				<td>-</td>
				';			
			}
			
			if($row_food["vegetarisch"] == 1)
			{
				echo '
				<td>ja</td>
				';
			}
			else
			{
				echo '
				<td>-</td>
				';			
			}
			
			echo '
			<td class="preis">'.number_format($row_food["preis"], 2, ",", ".").' €</td>
			<td>
			<select name="anzahl['.$row_food["id"].']" onchange="this.form.submit()">
			';
			for($i=0;$i<=10;$i++)
			{
				echo '<option value="'.$i.'"'; if(isset($_SESSION['warenkorb'][$row_food["id"]]) AND $_SESSION['warenkorb'][$row_food["id"]] == $i) { echo ' selected="selected"';}  echo '>'.$i.'</option>';
			}
			echo '
			</select>
			</td>
		</tr>
		';
	}
}

?>
</table>
<input type="hidden" name="warenkorb_aktualisierung" value="true" />
</form>