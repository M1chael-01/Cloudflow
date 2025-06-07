<?php




if (isset($_POST["send"]) && isset($_POST["cookie"])) {
    // Collect each piece of device info from the POST request
    $userAgent = $_POST["userAgent"];
    $screenResolution = $_POST["screenResolution"];
    $platform = $_POST["platform"];
    $timezone = $_POST["timezone"];
    $country = $_POST["country"];
    $ip = $_POST["userIP"];
    $deviceType = $_POST["deviceType"];
    
    
    // Generate a unique ID (use timestamp for simplicity or generate a unique ID)
    $uniqueId = uniqid(); // Unique ID for each entry, you can replace it with an auto-increment counter if preferred

    // Set cookie expiration date (90 days from now)
    $days = time() + (90 * 24 * 3600);  // 90 days from now

    // Set cookies for user data
    setcookie("cookie", $_POST["cookie"], $days, "/");
    setcookie("cookieData", $_POST["info"], $days, "/");
    setcookie("userAgent", $userAgent, $days, "/");
    setcookie("screenResolution", $screenResolution, $days, "/");
    setcookie("platform", $platform, $days, "/");
    setcookie("timezone", $timezone, $days, "/");
    setcookie("country", $country, $days, "/");
    setcookie("ip", $ip, $days, "/");

    // Prepare the data to be written into the CSV file
    $data = [
        'ID' => $uniqueId,
        'Timestamp' => date("Y-m-d H:i:s"),
        'UserAgent' => $userAgent,
        'ScreenResolution' => $screenResolution,
        'Platform' => $platform,
        'Timezone' => $timezone,
        'Country' => $country,
        'IP' => $ip,
        "deviceType" => $deviceType
    ];

    // Define file path
    $filePath = "../tracks/user_data.csv";

    // Check if the CSV file exists
    if (!file_exists($filePath)) {
        // Create the CSV file and add the header
        $file = fopen($filePath, 'w');
        // Write the header row
        fputcsv($file, array_keys($data));
        fclose($file);
    }

    // Append the new data to the CSV file
    $file = fopen($filePath, 'a');
    fputcsv($file, $data); // Write the data as a new row in the CSV file
    fclose($file);

    echo "Data has been saved.";
}
?>



<?php
if (!isset($_COOKIE["cookie"])) : // Check if the cookie is not set
?>
    <head>
        <link rel="stylesheet" href="./styles/others/cookie.css">
    </head>

    <body>
    <div id="cookie-consent-overlay">
        <div id="cookie-consent">
            <h2>Preferencí cookies</h2>
            <p>Používáme cookies k vylepšení vašeho zážitku. Kliknutím na "Povolit vše" souhlasíte s&nbsp;naším používáním cookies pro funkční, analytické&nbsp;a&nbsp;marketingové účely na tomto zařízení.</p>
            <div class="buttons">
                <button id="accept-all" onclick="cookieAll()">Povolit vše</button>
                <a href="./PDF/cookies.pdf" id="cookie-info-link">Více informací</a>
            </div>
        </div>
    </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="./JS/cookie.js"></script>
    </body>
<?php endif; ?>
