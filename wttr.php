<!DOCTYPE HTML>
<?php
	$request_method = $_SERVER['REQUEST_METHOD'];

	if ($request_method == 'POST') {
		$POSTdata = json_decode(file_get_contents('php://input'), true);

//debug		var_dump($POSTdata);

		$site = (string) $POSTdata["site"];
		$loc = (string) $POSTdata["loc"];
		$temp = (float) $POSTdata["temp"];
		$pres = (float) $POSTdata["pres"];
		$humi = (float) $POSTdata["humi"];
		$io = (int) $POSTdata["io"];

//debug		echo "<br>Loc: " . $loc . "<br>Temp: " . $temp . "<br>Pressure: " . $pres . "<br>Humidity: " . $humi . "<br>IO: " . $io . "<br>";

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
			$temp = 0.0;

			if (empty($pres)) {
				$pres = 0000;

				if (empty($humi)) {
					$humi = 00;

					if (empty($io)) {
						$io = 0;
						}
					}
				}
			}
//debug     echo "<br>Site: " . $site . "<br>Loc: " . $loc . "<br>Temp: " . $temp . "<br>Pressure: " . $pres . "<br>Humidity: " . $humi . "<br>IO: " . $io . "<br>";

	$mysqli = new mysqli("<ENDPOINT>", "<USER>", "<PASS>", "<DB>");

	if ($mysqli->connect_errno) {
//debug		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	else {
//debug		echo "Database connection ESTABLISHED :-)!";
//debug		echo $loc;
//debug		echo $site;

		$stmt = $mysqli->prepare("INSERT INTO wttr_station (site, loc, temp, pres, humi, io) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssdddi', $site, $loc, $temp, $pres, $humi, $io);
		$stmt->execute();
		$stmt->close();
//debug		echo $mysqli->info;
//debug		echo "Fertich!";
	}
	}
	else
	{
		// Nur POST erlaubt!
        http_response_code(405);
		echo "Nope! Try \"POST\" instead.";
	}
//debug     echo "<p>Ende!</p>";
?>
