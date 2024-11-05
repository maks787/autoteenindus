<?php

function convertXmlToJson($xmlFile, $jsonFile)
{
    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        die("Error loading XML file.");
    }

    $jsonArray = json_decode(json_encode($xml), true);

    $jsonData = json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($jsonFile, $jsonData);

    echo "JSON data has been successfully saved to $jsonFile.";
}

$xmlFile = 'bookings.xml';
$jsonFile = 'bookings.json';

convertXmlToJson($xmlFile, $jsonFile);

function loadXml($xmlFile)
{
    return simplexml_load_file($xmlFile);
}
echo '<nav>';
echo '<ul>';
echo '<li><a href="bookings.php">XML Broneeringud</a></li>';
echo '<li><a href="display_json.php">JSON Broneeringud</a></li>';
echo '</ul>';
echo '</nav>';
echo '</nav>';
function searchByCarNumber($xmlFile, $carNumber)
{
    $xml = loadXml($xmlFile);
    $results = $xml->xpath("//booking[details/carNumber='$carNumber']");

    if ($results) {
        echo "<h2>Tulemused auto numbri $carNumber järgi:</h2>";
        foreach ($results as $booking) {
            echo "Nimi: " . $booking->contact->name . "<br>";
            echo "Telefon: " . $booking->contact['phone'] . "<br>";
            echo "Teenuse nimi: " . $booking->details->service . "<br>";
            echo "Auto number: " . $booking->details->carNumber . "<br>";
            echo "Aeg: " . $booking->datetime . "<br>";
        }
    } else {
        echo "Broneeringu auto numbriga $carNumber ei leitud.<br>";
    }
}

function updateBooking($xmlFile, $carNumber, $newDatetime, $newService)
{
    $xml = loadXml($xmlFile);
    $booking = $xml->xpath("//booking[details/carNumber='$carNumber']");

    if ($booking) {
        $booking[0]->datetime = $newDatetime;
        $booking[0]->details->service = $newService;
        $xml->asXML($xmlFile);
        echo "Broneering on edukalt uuendatud auto numbrile $carNumber.<br>";
    } else {
        echo "Broneeringu auto numbriga $carNumber ei leitud.<br>";
    }
}

function addBooking($xmlFile, $name, $phone, $datetime, $service, $carNumber)
{
    $xml = loadXml($xmlFile);
    $newBooking = $xml->addChild('booking');
    $newContact = $newBooking->addChild('contact');
    $newContact->addChild('name', htmlspecialchars($name));
    $newContact->addAttribute('phone', htmlspecialchars($phone));
    $newBooking->addChild('datetime', htmlspecialchars($datetime));
    $newDetails = $newBooking->addChild('details');
    $newDetails->addChild('service', htmlspecialchars($service));
    $newDetails->addChild('carNumber', htmlspecialchars($carNumber));
    $xml->asXML($xmlFile);
    echo "Uus broneering on edukalt lisatud.<br>";
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

$xmlFile = 'bookings.xml';

$xml = loadXml($xmlFile);
echo "<h2>Broneeringud:</h2><table border='1'>";
echo "<tr><th>Nimi</th><th>Telefon</th><th>Teenuse nimi</th><th>Auto number</th><th>Aeg</th></tr>";
foreach ($xml->booking as $booking) {
    echo "<tr>";
    echo "<td>" . $booking->contact->name . "</td>";
    echo "<td>" . $booking->contact['phone'] . "</td>";
    echo "<td>" . $booking->details->service . "</td>";
    echo "<td>" . $booking->details->carNumber . "</td>";
    echo "<td>" . $booking->datetime . "</td>";
    echo "</tr>";
}
echo "</table>";

if (isset($_GET['carNumber'])) {
    $carNumber = htmlspecialchars($_GET['carNumber']);
    searchByCarNumber($xmlFile, $carNumber);
}

if (isset($_GET['updateCarNumber']) && isset($_GET['newTime']) && isset($_GET['newService'])) {
    $updateCarNumber = htmlspecialchars($_GET['updateCarNumber']);
    $newTime = htmlspecialchars($_GET['newTime']);
    $newService = htmlspecialchars($_GET['newService']);
    updateBooking($xmlFile, $updateCarNumber, $newTime, $newService);
}

if (isset($_GET['newName']) && isset($_GET['newPhone']) && isset($_GET['newDatetime']) && isset($_GET['newService']) && isset($_GET['newCarNumber'])) {
    $newName = htmlspecialchars($_GET['newName']);
    $newPhone = htmlspecialchars($_GET['newPhone']);
    $newDatetime = htmlspecialchars($_GET['newDatetime']);
    $newService = htmlspecialchars($_GET['newService']);
    $newCarNumber = htmlspecialchars($_GET['newCarNumber']);
    addBooking($xmlFile, $newName, $newPhone, $newDatetime, $newService, $newCarNumber);
}

$services = getAvailableServices($xmlFile);
?>

<form method="get" action="">
    <label for="carNumber">Sisestage auto number otsimiseks:</label>
    <input type="text" id="carNumber" name="carNumber" required>
    <input type="submit" value="Otsi">
</form>

<form method="get" action="">
    <label for="updateCarNumber">Sisestage auto number muutmiseks:</label>
    <input type="text" id="updateCarNumber" name="updateCarNumber" required>

    <label for="newTime">Uus aeg:</label>
    <input type="datetime-local" id="newTime" name="newTime" required>

    <label for="newService">Uus teenus:</label>
    <select id="newService" name="newService" required>
        <?php foreach ($services as $service): ?>
            <option value="<?php echo $service; ?>"><?php echo $service; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Muuda broneeringut">
</form>

<form method="get" action="">
    <h2>Lisa uus broneering</h2>
    <label for="newName">Nimi:</label>
    <input type="text" id="newName" name="newName" required>

    <label for="newPhone">Telefon:</label>
    <input type="text" id="newPhone" name="newPhone" required>

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
