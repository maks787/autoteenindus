<?php
ob_start();

$xmlFile = 'bookings.xml';
$jsonFile = 'bookings.json';

function loadXml($xmlFile)
{
    return simplexml_load_file($xmlFile);
}

function convertXmlToJson($xmlFile, $jsonFile)
{
    $xml = loadXml($xmlFile);
    if ($xml === false) {
        die("Error loading XML file.");
    }

    $jsonArray = json_decode(json_encode($xml), true);
    $jsonData = json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($jsonFile, $jsonData);
}

function addBooking($xmlFile, $jsonFile, $name, $phone, $datetime, $service, $carNumber)
{
    if (!ctype_digit($phone)) {
        die("Error: Phone number must contain only digits.");
    }

    // Add to XML
    $xml = loadXml($xmlFile);
    $newBooking = $xml->addChild('booking');
    $newContact = $newBooking->addChild('contact');
    $newContact->addChild('name', htmlspecialchars($name));
    $newContact->addAttribute('phone', htmlspecialchars($phone));
    $newBooking->addChild('datetime', htmlspecialchars($datetime));
    $newDetails = $newBooking->addChild('details');
    $newDetails->addChild('service', htmlspecialchars($service));
    $newDetails->addChild('carNumber', htmlspecialchars($carNumber));

    if ($xml->asXML($xmlFile)) {
        echo "Uus broneering on edukalt lisatud.<br>";
    } else {
        echo "Broneeringu lisamine ebaõnnestus.<br>";
    }

    convertXmlToJson($xmlFile, $jsonFile);
}

function getAvailableServices($xmlFile)
{
    $xml = loadXml($xmlFile);
    $services = $xml->xpath("//service");

    $serviceList = [];
    foreach ($services as $service) {
        $serviceList[] = (string)$service;
    }

    return $serviceList;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['newName'], $_GET['newPhone'], $_GET['newDatetime'], $_GET['newService'], $_GET['newCarNumber'])) {
    $newName = htmlspecialchars($_GET['newName']);
    $newPhone = htmlspecialchars($_GET['newPhone']);
    $newDatetime = htmlspecialchars($_GET['newDatetime']);
    $newService = htmlspecialchars($_GET['newService']);
    $newCarNumber = htmlspecialchars($_GET['newCarNumber']);

    addBooking($xmlFile, $jsonFile, $newName, $newPhone, $newDatetime, $newService, $newCarNumber);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$services = getAvailableServices($xmlFile);

echo '<link rel="stylesheet" href="styles.css">';

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broneeringud</title>
</head>
<body>

<nav class="nav">
    <ul>
        <li><a href="bookings.php">XML Broneeringud</a></li>
        <li><a href="display_json.php">JSON Broneeringud</a></li>
        <li><a href="<?php echo $xmlFile; ?>" target="_blank">Vaata XML faili</a></li>
        <li><a href="<?php echo $jsonFile; ?>" target="_blank">Vaata JSON faili</a></li>
    </ul>
</nav>

<h2>Broneeringud:</h2>
<table>
    <tr><th>Nimi</th><th>Telefon</th><th>Teenuse nimi</th><th>Auto number</th><th>Aeg</th></tr>
    <?php
    $xml = loadXml($xmlFile);
    foreach ($xml->booking as $booking) {
        echo "<tr>";
        echo "<td>" . $booking->contact->name . "</td>";
        echo "<td>" . $booking->contact['phone'] . "</td>";
        echo "<td>" . $booking->details->service . "</td>";
        echo "<td>" . $booking->details->carNumber . "</td>";
        echo "<td>" . $booking->datetime . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<form method="get" action="">
    <h3>Lisa uus broneering</h3>
    <label for="newName">Nimi:</label>
    <input type="text" id="newName" name="newName" required>

    <label for="newPhone">Telefon:</label>
    <input type="text" id="newPhone" name="newPhone" pattern="\d+" title="Only digits are allowed" required>

    <label for="newDatetime">Aeg:</label>
    <input type="datetime-local" id="newDatetime" name="newDatetime" required>

    <label for="newService">Teenuse nimi:</label>
    <select id="newService" name="newService" required>
        <?php foreach ($services as $service): ?>
            <option value="<?php echo $service; ?>"><?php echo $service; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="newCarNumber">Auto number:</label>
    <input type="text" id="newCarNumber" name="newCarNumber" required>

    <input type="submit" value="Lisa broneering">
</form>

</body>
</html>

<?php
ob_end_flush();
?>
