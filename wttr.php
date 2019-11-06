<!DOCTYPE HTML>
<?php
	$authkeys = array("key1", "key2", "key3", "key4", "key5", "key6", "key7");		//Hier weitere Keys hinzufügen, falls neue Module eingesetzt werden!
	$request_method = $_SERVER['REQUEST_METHOD'];
	if ($request_method == 'POST') {
		$POSTdata = json_decode(file_get_contents('php://input'), true);
		$authkey = (string) $POSTdata["authkey"];
		$site = (string) $POSTdata["site"];
		$loc = (string) $POSTdata["loc"];
		$temp = (float) $POSTdata["temp"];
		$pres = (float) $POSTdata["pres"];
		$humi = (float) $POSTdata["humi"];
		$io = (int) $POSTdata["io"];
		if (!in_array($authkey, $authkeys) || empty($authkey)) {
	    	die("Invalid authentication key!");
		}
		if (!empty($site) && strlen($site) <= 0 || strlen($site) > 32) {
			die("Allowed string length for <b>SITE</b>: 1-32 chars.");
		}
		if (empty($site)) {
			$site = "unknown";
		}
		if (!empty($loc) && strlen($loc) <= 0 || strlen($loc) > 32) {
			die("Allowed string length for <b>LOC</b>: 1-32 chars.");
		}
		if (empty($loc)) {
			$loc = "unknown";
		}
		if (empty($temp)) {
			$temp = 0;
		}
		if (empty($pres)) {
			$pres = 0;
		}
		if (empty($humi)) {
			$humi = 0;
		}
		if (empty($io)) {
			$io = 0;
		}
		$mysqli = new mysqli("<ENDPOINT>", "<USER>", "<PASSWORT>", "<DB>");
		if ($mysqli->connect_errno) {
			die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		}
		else {
			$stmt = $mysqli->prepare("INSERT INTO wttr_station (site, loc, temp, pres, humi, io) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('ssdddi', $site, $loc, $temp, $pres, $humi, $io);
			$stmt->execute();
			$stmt->close();
		}
	}
	else
	{
		// Nur POST erlaubt!
        http_response_code(405);
		echo "Nope! Try \"POST\" instead.";
	}
?>
