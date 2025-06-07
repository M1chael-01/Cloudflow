<?php
require "../getID.php";
$id = GetID::getID();

// Function to zip a folder securely using ZipArchive
function zipFolder($folderPath, $zipFileName) {
    // Validate folder path to ensure it's inside the allowed directory
    if (!is_dir($folderPath)) {
        exit("Folder does not exist.");
    }
    if (!class_exists('ZipArchive')) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error - Missing ZipArchive</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f8f8;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .error-container {
                    background-color: #ffdddd;
                    color: #900;
                    border: 1px solid #900;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    max-width: 500px;
                    width: 100%;
                }
                h1 {
                    font-size: 24px;
                    margin-bottom: 10px;
                }
                p {
                    font-size: 16px;
                    line-height: 1.5;
                }
                .back-link {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 10px 20px;
                    background-color: #900;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 5px;
                }
                .back-link:hover {
                    background-color: #b33b3b;
                }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>Chyba: Váš prohlížeč nepodporuje ZipArchive</h1>
                <p>Pro pokračování musíte mít nainstalovanou PHP rozšíření ZipArchive. Kontaktujte správce serveru pro více informací.</p>
                <a href='javascript:history.back()' class='back-link'>Zpět</a>
            </div>
        </body>
        </html>";
        exit;
    
    }

    // Create a new ZipArchive instance
    $zip = new ZipArchive();
    
    // Temporary file for the ZIP
    $tempZipFile = tempnam(sys_get_temp_dir(), 'zip'); // Create a temporary file
    
    // Open the zip file for writing
    if ($zip->open($tempZipFile, ZipArchive::CREATE) !== TRUE) {
        exit("Cannot open <$zipFileName>.\n");
    }

    // Recursive function to add files to the zip
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folderPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($folderPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Close the zip file
    $zip->close();

    // Serve the file to the browser for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($tempZipFile));

    // Output the zip file to the browser
    readfile($tempZipFile);

    // Remove the temporary zip file from the server
    unlink($tempZipFile);
}

// Get dynamic parameters from the URL (id and name)
global $id;

$folderName = isset($_GET['name']) ? $_GET['name'] : null;  // Example: "abcd"

$folderId = $id;
// Validate and process
if ($folderId && $folderName) {
    // Assuming the folder path is based on id and name dynamically
    $folderPath = "../../dashboard/uploads/users/$id/$folderName/";  // Adjust the path as needed
    $zipFileName = $folderName . '_downloaded_folder.zip'; // Dynamically name the zip file

    // Call the function to zip the folder and serve it
    zipFolder($folderPath, $zipFileName);
} else {
    exit("Invalid parameters.");
}
?>
