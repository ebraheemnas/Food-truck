<?php
if(isset($_POST))
{
	if(isset($_POST["warenkorb_aktualisierung"]))
	{
		if(isset($_POST["anzahl"]) AND is_array($_POST["anzahl"]))
		{
			foreach($_POST["anzahl"] as $key => $anzahl_item)
			{
				if($anzahl_item > 0)
				{
					$_SESSION['warenkorb'][$key] = $anzahl_item;
				}
				else
				{
					unset($_SESSION['warenkorb'][$key]);
				}
			}
		}
	}

	if(isset($_POST["loeschen"]))
	{
		unset($_SESSION['warenkorb']);
	}
	
	
	if(isset($_POST["submit_bestellung"]))
	{
		$fehler = array();
		
		if(!isset($_POST["vorname"]) OR strlen($_POST["vorname"]) < 2)
		{
			$fehler[] = "Bitte geben Sie Ihren Vornamen an!";
		}
		else
		{
			$_SESSION["vorname"] = $_POST["vorname"];
		}
		
		if(!isset($_POST["nachname"]) OR strlen($_POST["nachname"]) < 2)
		{
			$fehler[] = "Bitte geben Sie Ihren Nachnamen an!";
		}
		else
		{
			$_SESSION["nachname"] = $_POST["nachname"];
		}		
		
		if(!isset($_POST["email"]) OR filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false)
		{
			$fehler[] = "Bitte geben Sie eine gültige E-Mail-Adresse ein!";
		}
		else
		{
			$_SESSION["email"] = $_POST["email"];
		}
		
		if(isset($_POST["firma"]))
		{
			$_SESSION["firma"] = $_POST["firma"];
		}
		
		if(isset($_POST["anmerkungen"]))
		{
			$_SESSION["anmerkungen"] = $_POST["anmerkungen"];
		}

		if(isset($_POST["einpacken"]))
		{
			$_SESSION["einpacken"] = $_POST["einpacken"];
		}
		else
		{
			$fehler[] = "Bitte geben Sie an, ob Ihre Bestellung eingepackt werden soll!";
		}
		
		if(array_sum($_POST["anzahl"]) == 0)
		{
			$fehler[] = "Sie haben kein Produkt ausgewählt!";
		}
		
		if(!isset($_POST["wochentag"]) OR array_sum($_POST["wochentag"]) == 0)
		{
			$fehler[] = "Sie haben keinen Wochentag ausgewählt!";
		}
		else
		{
			$_SESSION["wochentag"] = implode(",",$_POST["wochentag"]);
		}

		if(empty($fehler))
		{
			// Daten in Datenbank speichern
			$SQL = "INSERT INTO bestellungen(vorname, nachname, firma, email, anmerkungen, wochentag, einpacken) VALUES ('".$_SESSION["vorname"]."','".$_SESSION["nachname"]."','".$_SESSION["firma"]."', '".$_SESSION["email"]."', '".$_SESSION["anmerkungen"]."','".$_SESSION["wochentag"]."', '".$_SESSION["einpacken"]."')";
			mysqli_query($connect, $SQL);
			
			// Bestellte Elemente in Datenbank schreiben
			foreach($_POST["anzahl"] as $key => $anzahl_item)
			{
				$SQL = "INSERT INTO bestellungen_produkte(id,produkt_id,anzahl) VALUES (LAST_INSERT_ID(),'".$key."','".$anzahl_item."')";
				mysqli_query($connect, $SQL);
			}
			
			// Bestätigungsmail an Kunden schicken
			$empfaenger = $_SESSION["email"];
			$absendername = "vhb Foodtruck";
			$absendermail = "vhb@profthome.de";
			$betreff = "Deine Bestellung";
			$mailtext = "Hallo ".$_SESSION["vorname"]." ".$_SESSION["nachname"].", vielen Dank für Deine Bestellung";
			
			mail($empfaenger, $betreff, $mailtext, "From: ".$absendername." <".$absendermail.">");
			$bestellung_abgeschlossen = true;
		}
	}
}
?>