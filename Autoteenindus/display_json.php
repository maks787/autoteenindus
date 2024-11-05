<?php
$jsonFile = 'bookings.json';


function loadJson($jsonFile) {
    $jsonData = file_get_contents($jsonFile);
    return json_decode($jsonData, true);
}
echo '<nav>';
echo '<ul>';
echo '<li><a href="bookings.php">XML Broneeringud</a></li>';
echo '<li><a href="display_json.php">JSON Broneeringud</a></li>';
echo '</ul>';
echo '</nav>';
echo '</nav>';
function searchByCarNumber($data, $carNumber) {
    $results = array_filter($data['booking'], function ($booking) use ($carNumber) {
        return isset($booking['details']['carNumber']) && $booking['details']['carNumber'] === $carNumber;
    });

    if ($results) {
        echo "<h2>Tulemused auto numbri $carNumber järgi:</h2>";
        foreach ($results as $booking) {
            echo "Nimi: " . htmlspecialchars($booking['contact']['name']) . "<br>";
            echo "Telefon: " . htmlspecialchars($booking['contact']['@attributes']['phone']) . "<br>";
            echo "Teenuse nimi: " . htmlspecialchars($booking['details']['service']) . "<br>";
            echo "Auto number: " . htmlspecialchars($booking['details']['carNumber']) . "<br>";
            echo "Aeg: " . htmlspecialchars($booking['datetime']) . "<br>";
        }
    } else {
        echo "Broneeringu auto numbriga $carNumber ei leitud.<br>";
    }
}

// Update a booking
function updateBooking(&$data, $carNumber, $newDatetime, $newService) {
    foreach ($data['booking'] as &$booking) {
        if (isset($booking['details']['carNumber']) && $booking['details']['carNumber'] === $carNumber) {
            $booking['datetime'] = $newDatetime;
            $booking['details']['service'] = $newService;
            file_put_contents('bookings.json', json_encode($data, JSON_PRETTY_PRINT));
            echo "Broneering on edukalt uuendatud auto numbrile $carNumber.<br>";
            return;
        }
    }
    echo "Broneeringu auto numbriga $carNumber ei leitud.<br>";
}

// Add a new booking
function addBooking(&$data, $name, $phone, $datetime, $service, $carNumber) {
    $newBooking = [
        '@attributes' => [
            'id' => count($data['booking']) + 1, // Assign a new id
        ],
        'contact' => [
            '@attributes' => [
                'phone' => htmlspecialchars($phone),
            ],
            'name' => htmlspecialchars($name),
        ],
        'datetime' => htmlspecialchars($datetime),
        'details' => [
            'service' => htmlspecialchars($service),
            'carNumber' => htmlspecialchars($carNumber),
        ],
    ];

    $data['booking'][] = $newBooking;
    file_put_contents('bookings.json', json_encode($data, JSON_PRETTY_PRINT));
    echo "Uus broneering on edukalt lisatud.<br>";
}

// Load JSON data
$data = loadJson($jsonFile);

// Display JSON Bookings
echo "<h2>JSON Broneeringud:</h2><table border='1'>";
echo "<tr><th>Nimi</th><th>Telefon</th><th>Teenuse nimi</th><th>Auto number</th><th>Aeg</th></tr>";
foreach ($data['booking'] as $booking) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($booking['contact']['name']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['contact']['@attributes']['phone']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['details']['service']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['details']['carNumber']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['datetime']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Search for a booking by car number
if (isset($_GET['carNumber'])) {
    $carNumber = htmlspecialchars($_GET['carNumber']);
    searchByCarNumber($data, $carNumber);
}

// Update a booking
if (isset($_GET['updateCarNumber']) && isset($_GET['newTime']) && isset($_GET['newService'])) {
    $updateCarNumber = htmlspecialchars($_GET['updateCarNumber']);
    $newTime = htmlspecialchars($_GET['newTime']);
    $newService = htmlspecialchars($_GET['newService']);
    updateBooking($data, $updateCarNumber, $newTime, $newService);
}

// Add a new booking
if (isset($_GET['newName']) && isset($_GET['newPhone']) && isset($_GET['newDatetime']) && isset($_GET['newService']) && isset($_GET['newCarNumber'])) {
    $newName = htmlspecialchars($_GET['newName']);
    $newPhone = htmlspecialchars($_GET['newPhone']);
    $newDatetime = htmlspecialchars($_GET['newDatetime']);
    $newService = htmlspecialchars($_GET['newService']);
    $newCarNumber = htmlspecialchars($_GET['newCarNumber']);
    addBooking($data, $newName, $newPhone, $newDatetime, $newService, $newCarNumber);
}
?>

<!-- Form to search by car number -->
<form method="get" action="">
    <label for="carNumber">Sisestage auto number otsimiseks:</label>
    <input type="text" id="carNumber" name="carNumber" required>
    <input type="submit" value="Otsi">
</form>

<!-- Form to update a booking -->
<form method="get" action="">
    <label for="updateCarNumber">Sisestage auto number muutmiseks:</label>
    <input type="text" id="updateCarNumber" name="updateCarNumber" required>

    <label for="newTime">Uus aeg:</label>
    <input type="datetime-local" id="newTime" name="newTime" required>

    <label for="newService">Uus teenus:</label>
    <input type="text" id="newService" name="newService" required>

    <input type="submit" value="Muuda broneeringut">
</form>

<!-- Form to add a new booking -->
<form method="get" action="">
    <h2>Lisa uus broneering</h2>
    <label for="newName">Nimi:</label>
    <input type="text" id="newName" name="newName" required>

    <label for="newPhone">Telefon:</label>
    <input type="text" id="newPhone" name="newPhone" required>

    <label for="newDatetime">Aeg:</label>
    <input type="datetime-local" id="newDatetime" name="newDatetime" required>

    <label for="newService">Teenuse nimi:</label>
    <input type="text" id="newService" name="newService" required>

    <label for="newCarNumber">Auto number:</label>
    <input type="text" id="newCarNumber" name="newCarNumber" required>

    <input type="submit" value="Lisa broneering">
</form>
