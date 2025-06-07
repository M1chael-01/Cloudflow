<!-- 
 Phone icon:
<a href="https://www.flaticon.com/free-icons/image-gallery" title="image gallery icons">Image gallery icons created by Anggara - Flaticon</a> 
-->
<!-- PDF 
<a href="https://www.flaticon.com/free-icons/pdf" title="pdf icons">Pdf icons created by egorpolyakov - Flaticon</a> 
-->
<!-- DOC:
<a href="https://www.flaticon.com/free-icons/document" title="document icons">Document icons created by Driss Lebbat - Flaticon</a> 
-->

<!-- TXT:
 <a href="https://www.flaticon.com/free-icons/txt-file" title="txt file icons">Txt file icons created by The Chohans - Flaticon</a>
  -->
 <!-- PPT:
  <a href="https://www.flaticon.com/free-icons/ppt" title="ppt icons">Ppt icons created by Dimitry Miroliubov - Flaticon</a> -->

  <!-- other files -->
  <!-- https://www.freepik.com/free-vector/vector-document-vector-colorful-design_84597519.htm#fromView=search&page=1&position=27&uuid=0890aaf3-1dcd-4d3c-90dc-e7133a7a0a98&query=unsupport+file -->

<?php
require "../database/cloudApp/data.php";
require "../backend/getID.php";
require "../backend/isTeamAcc.php";
require "../backend/cloudApp/hasStorage.php";
$data = data();

if (isset($_GET["dashboard"])) {
    require "./database/cloudApp/users.php";
} else {
    require "../database/cloudApp/users.php";
}

if(isset($_GET["stahnout"])) {
    require "./pages/control.php";return;
}
if(isset($_GET["vymazat"])) {
    require "./pages/control.php";return;
}
if(isset($_GET["prejmenovat"])) {
    require "./pages/control.php";return;
}

// Define size data
$size = array(
    "individual" => "1GB",
    "team" => "5GB"
);

$users = users();  

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if session is not active
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["active"])) {
    header("Location: ../?uvod"); // Using header for redirection
    exit();
}

// Function to retrieve User ID from session with fallback handling
function getUserID() {
    return isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 7;
}

// Function to fetch only account type using mysqli
function getAccountType($id) {
    global $users;  // Assuming $users is your database connection

    if (!$id) {
        return null; // If no user ID, return null
    }

    // Prepare SQL query to only select account_type
    $sql = "SELECT account_type FROM uzivatel WHERE id = ?";
    
    // Prepare the SQL statement
    $stmt1 = mysqli_prepare($users, $sql);
    
    if ($stmt1) {
        // Bind the parameter to the prepared statement
        mysqli_stmt_bind_param($stmt1, "i", $id);
        
        // Execute the query
        mysqli_stmt_execute($stmt1);
        
        // Bind the result to the variable
        mysqli_stmt_bind_result($stmt1, $account_type);

        // Fetch the result
        if (mysqli_stmt_fetch($stmt1)) {
            // // Close the prepared statement
            mysqli_stmt_close($stmt1);
            
            // Return the account type
            return $account_type;
        } else {
            // Close the statement if no results are found
            mysqli_stmt_close($stmt1);
            return null; // No results found
        }
    } else {
        // Handle SQL preparation error
        return null;
    }
}

function getFolders($id) {
    global $data;
    // Prepare the SQL statement to fetch folder_name and disk_name
    $stmt = $data->prepare("SELECT folder_name, disk_name,files FROM data WHERE user = ?");
    $stmt->bind_param("i", $id);  // Bind the user ID parameter (assuming $id is an integer)

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($folder_name, $disk_name,$files);

    // Fetch the result into an array
    $result = [];
    while ($stmt->fetch()) {
        $result[] = [
            'folder_name' => $folder_name,
            'disk_name' => $disk_name,
            'files' => $files
        ];
    }
    $stmt->close();
    return $result; // Return the array of folders
}

function getFiles($id) {
    global $data;

    // Prepare SQL to fetch the JSON-encoded file details and folder name
    $stmt = $data->prepare("SELECT files, folder_name,disk_name FROM data WHERE user = ?");
    $stmt->bind_param("i", $id);  // Bind the user ID

    // Execute the statement
    $stmt->execute();
    $stmt->bind_result($files, $folder_name,$disk_name);
    $result = [];
    while ($stmt->fetch()) {
        // Filter out folders with name "-"
        if ($folder_name == "-") {
            // Assuming 'files' contains JSON data, decode it into an array
            $fileArray = json_decode($files, true); // Decode JSON into an (associative array(abstract data type that stores a collection of (key, value) pairs)
            $result[] = [
                'folder_name' => $folder_name,
                'files' => $fileArray,
                'disk_name' => $disk_name
            ];
        }
    }
    $stmt->close();

    return $result; // Return the array of files with their respective folder names
}
// Get user ID from session 
$id = GetID::getID();

// Fetch the account type (use it wherever necessary, like checking storage size, etc.)
$userData = getAccountType($id);

// Get folders and files for the current user
$folders = getFolders($id);
$files = getFiles($id);

// Combine folders and files into one array for rendering or other uses
$fileAndFolder = [
    [
        'folders' => $folders  // Array of folders
    ],
    [
        'files' => $files  // Array of files (decoded from JSON)
    ]
];
$GLOBALS["user"] = getAccountType($id);


if($GLOBALS["user"]) {
    $_SESSION["role"] = $GLOBALS["user"];
}
function removeFolderHistory() {
    setcookie("folder", "", time() - 3600, "/"); // Cookie expires in 1 hour
   unset( $_SESSION["folder"]); 
}

function convertUnit($bytes) {
    if ($bytes < 1024) {
        return $bytes . ' B'; // If it's less than 1 KB, just return bytes
    } elseif ($bytes < 1048576) {
        return round($bytes / 1024, 2) . ' KB'; // If it's less than 1 MB
    } elseif ($bytes < 1073741824) {
        return round($bytes / 1048576, 2) . ' MB'; // If it's less than 1 GB
    } elseif ($bytes < 1099511627776) {
        return round($bytes / 1073741824, 2) . ' GB'; // If it's less than 1 TB
    } else {
        return round($bytes / 1099511627776, 2) . ' TB'; // If it's greater than or equal to 1 TB
    }
}

function getFolderSize($folder) {
    // Initialize a variable to store the total size of files in the folder
    $totalSize = 0;
    // Check if the provided path is a valid directory
    if (is_dir($folder)) {
        // Create a RecursiveIteratorIterator to loop through the folder and its subdirectories
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS), // Avoids "." and ".." directories
            RecursiveIteratorIterator::CHILD_FIRST // Processes files and subdirectories from the deepest level first
        );
        // Loop through each item (file or directory) in the folder and its subfolders
        foreach ($files as $fileinfo) {
            // Check if the current item is a file (skip directories)
            if ($fileinfo->isFile()) {
                // Add the size of the current file to the total size
                $totalSize += $fileinfo->getSize(); // getSize() returns the size of the file in bytes
            }
        }
    }
    // Return the total size of all files in the folder (in bytes)
    return $totalSize;
}

if(isset($_GET["filter-name"])) {
    $fileAndFolder = [
        [
            'folders' => [],
        ],
        [
            'files' => $files  // Array of files (decoded from JSON)
        ]
    ];
}
else if (isset($_GET["filter-size"])) {}
else if(isset($_GET["filter-type"])) {}
else{
    $fileAndFolder = [
        [
            'folders' => $folders  // Array of folders
        ],
        [
            'files' => $files  // Array of files (decoded from JSON)
        ]
    ];
}
// CREATE SESSION FILES,FOLDER
$_SESSION["files-data"] = $files;
$_SESSION["folder-data"] = $folders;
?>
<head>
    <title>CloudFlow - Soubory</title>
    <link rel="stylesheet" href="./styles/dashboard.css">
    <link rel="stylesheet" href="./styles/app.css">
   
</head>
<body>

<?php require "./pages/sidebar.php"; ?>
<?php
    $folderPath = "../dashboard/uploads/users/" . $_SESSION["user_id"];
         // Get the used folder size in bytes
    $folderSizeInBytes = getFolderSize($folderPath);
    $folderSize = convertUnit($folderSizeInBytes);

    if($GLOBALS["user"] == "team") {
        $totalStorage = 5 * 1024 * 1024 * 1024;
    }
    else{
        $totalStorage = 1 * 1024*1024*1024;
    } 
    // 1 = gb 
    $progress = ($folderSizeInBytes / $totalStorage) * 100; // Calculate progress percentage

    // Convert the total storage to a readable format (e.g., MB, GB)
    $storage_readable = convertUnit($totalStorage);

    $_SESSION["used-storage"] = $folderSizeInBytes;
    $_SESSION["max-storage"] = $totalStorage;

    if(CheckStorage::checkStorage()) {  // check if user has storage if so,the class name is upload-true,else it will block user
        $GLOBALS["upload"] = "upload-true";
    }
    else{
        $GLOBALS["upload"] = "upload-false";
    }
    ?>
<?php
// Check if the 'files' parameter is set and load the upload page
if (isset($_GET["files"])) {
    require "./pages/uploadFiles.php";
}

?>
<!-- Main Content -->
<div class="main-content">
    <!-- Header Bar -->
    <div class="header-bar">
        <input type="file" name="file" id="input-file" required hidden>
        <button  fx = "<?=$GLOBALS["upload"] ?>" id="upload-file-btn-header">+ Nahrát soubor</button>
        <button fx = "<?=$GLOBALS["upload"] ?>" id="create-folder-btn-header">+ Vytvořit složku</button>
    </div>
<!-- User Menu -->
<div class="user-menu">
    <div class="content">
    <i onclick="removeDiv()" id="close-tag" class="ri-close-line"></i>
        <h2 class="menu-title">Nahrávání souboru</h2>
      
        <p class="menu-description">Klikněte na tlačítko níže pro nahrání souboru.</p>
        <button id="upload-button">Nahrajte si svůj soubor</button>
    </div>
</div>
<!-- Folder and Files Grid -->
<div class="folder-grid" id="folder-grid">
</div>
    <!-- Storage Info -->
    <div class="storage-info">
         <div class="storage-text">Využité úložiště: <?=$folderSize?> / <?=$storage_readable?></div>
        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar" style="background-color: #1a73e8;width: <?=min($progress, 100)?>%"></div>
        </div>
        <div></div>
    </div>
<!-- The Folder Creation Modal -->
<div id="folder-modal" class="folder-modal">
        <!-- Modal content -->
        <div class="folder-modal-content">
            <span class="close">&times;</span>
            <h2>Vytvořit novou složku</h2>
            <input type="text" id="folder-name" placeholder="Zadejte název složky" />
            <button onclick="createFolder()" id="create-folder-btn">Vytvořit složku</button>
        </div>
    </div>
    <div class="folder-grid">
    <!-- Display Folders -->
    <?php foreach ($fileAndFolder[0]['folders'] as $folder): ?>
        <?php if ($folder['folder_name'] !== "-"): ?>
            <?php
            $folderPath = "../dashboard/uploads/users/" . $_SESSION["user_id"] . "/" . $folder["disk_name"];
            // Check if the folder exists
            if (is_dir($folderPath)) {
                // Get the total size of the folder in bytes
                $folderSizeInBytes = getFolderSize($folderPath);
                
                // Convert the size to a human-readable format (e.g., MB, GB)
                $folderSize = convertUnit($folderSizeInBytes);
            } else {
                // If the folder doesn't exist, skip rendering it
                continue;
            }
            ?>
            <div id="folder-card" class="folder-card" link="./pages/upload?file=<?= base64_encode($folder['folder_name']) ?>" dbname="<?= base64_encode($folder['folder_name']) ?>" foldername="<?= base64_encode($folder['disk_name']) ?>">
                <!-- just send diskname instead of foldername -->
                <i class="ri-folder-line"></i> <!-- Folder icon -->
                <h3><?= htmlspecialchars($folder['folder_name']) ?></h3> <!-- Folder name -->
                <h4><?= $folderSize ?></h4>
                <div class="control folder-control"> <!-- Change id to class -->
                    <div link="<?= $folder["disk_name"] ?>" foldername="<?= base64_encode($folder["disk_name"]) ?>" dbname="<?= base64_encode($folder["folder_name"]) ?>" id="download" title="stáhněte si svůj soubor" class="one-control"><i class="ri-download-line"></i></div>
                    <div foldername="<?= base64_encode($folder["disk_name"]) ?>" dbname="<?= base64_encode($folder["folder_name"]) ?>" id="delete" title="Vymažte soubor" class="one-control"><i class="ri-close-line"></i></div>
                    <div link="<?= $folder["disk_name"] ?>" foldername="<?= base64_encode($folder["disk_name"]) ?>" dbname="<?= base64_encode($folder["folder_name"]) ?>" id="rename" title="přejmenujte složku" class="one-control"><i class="ri-pencil-line"></i></div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<!-- Display Files -->
<?php foreach ($fileAndFolder[1]['files'] as $fileSet): ?>
    <?php foreach ($fileSet as $file): ?>
        <?php
            // Check if $file is an array, it may have the file name inside as a value
            if (is_array($file)) {
                // Access the first element in the array
                $fileName = htmlspecialchars(implode(", ", $file)); // Make sure you use the $file array to extract names
            } else {
                // If $file is a string directly, use it
                $fileName = htmlspecialchars($file); // If it's a string, just sanitize it
            }

            // Skip files with a name of "-" or empty strings
            if ($fileName == "-" || empty($fileName)) {
                continue; // Skip this file if the condition is met
            }

            // Define the full file path
            $filePath = "../dashboard/uploads/users/".$_SESSION["user_id"]."/".$fileName;

            // Check if the file exists before displaying it
            if (!file_exists($filePath)) {
                continue; // Skip the file if it doesn't exist
            }

            // If the file exists, get the file size
            $fileSize = convertUnit(filesize($filePath));
        ?>
        
        <div id="file-card" class="file-card" link="../dashboard/uploads/users/<?=$_SESSION["user_id"]."/".$fileName?>">
            <!-- Process the file extension and display corresponding image -->
            <?php if (!empty($fileName)): ?>
                <?php
                    $pathInfo = pathinfo($fileName);
                    $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : '';
                    
                    // Check the file extension and display the corresponding image
                    switch ($extension) {
                        case 'png':
                        case 'jpg':
                        case 'jpeg':
                        case 'gif':
                            echo '<img src="../images/app/photo.png" alt="Image File" class="file-image" />';
                            break;
                        case 'pdf':
                            echo '<img src="../images/app/pdf.png" alt="PDF File" class="file-image" />';
                            break;
                        case 'doc':
                        case 'docx':
                            echo '<img src="../images/app/doc.png" alt="Word File" class="file-image" />';
                            break;
                        case 'ppt':
                        case 'pptx':
                            echo '<img src="../images/app/ppt.png" alt="PowerPoint File" class="file-image" />';
                            break;
                        case 'txt':
                            echo '<img src="../images/app/txt.png" alt="Text File" class="file-image" />';
                            break;
        
                        default:
                            echo '<img src="../images/app/nf.png" alt="File" class="file-image" />';
                    }
                ?>
            <?php else: ?>
                <img src="../images/app/pdf.png" alt="File" class="file-image" />
            <?php endif; ?>

            <!-- Display File Name -->
            <h3><?= htmlspecialchars($fileName) ?></h3>
            <h4><?= $fileSize ?></h4>
            <input type="text" name="" id="name" hidden value="<?= $fileName ?>">
            <?php
                $id = $_SESSION["user_id"];
                $link = base64_encode("./uploads/users/".$id."/".($fileName));
            ?>
            <div class="control">
                <?php
                    $fileNameEncoded = base64_encode($fileName);
                ?>
                <div link="<?=($link )?>" filename="<?=$fileNameEncoded?>" id="download" title="Download file" class="one-control"><i class="ri-download-line"></i></div>
                <div filename="<?=$fileNameEncoded?>" id="delete" title="Delete file" class="one-control" data-file-name="<?= $fileName ?>"><i class="ri-close-line"></i></div>
                <div link="<?=($link )?>" filename="<?=$fileNameEncoded?>" id="rename" title="Rename file" class="one-control"><i class="ri-pencil-line"></i></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

<script>
const fileInput = document.querySelector("#input-file");
    document.getElementById('create-folder-btn-header').addEventListener('click', function() {});
    // Upload file button functionality (if you want to add it later)
    document.querySelector('.header-bar button').addEventListener('click', function() {});
    let url = ".././backend/cloudApp/user.php";

    function logout(){
        $.ajax({
        url: '.././backend/cloudApp/user.php',  
        type: 'POST',
        data: {logout:true,date:new Date()},  
        success: function(response) {
            console.log("Server Response:", response);
          location.href = "?uvod";
        },
        error: function(xhr, status, error) {
            url = "./backend/cloudApp/user.php";
        }
    });
    }
// Select all folder and file cards
const folderCards = document.querySelectorAll(".folder-card");
const fileCards = document.querySelectorAll(".file-card");

// Function to add mouse and touch event listeners to cards
const addCardEvents = (card, id, controlsClass) => {
    // Desktop (Mouse) Events
    card.addEventListener("mouseenter", function() {
        // Show control when mouse enters the card
        const controls = document.querySelectorAll(controlsClass);
        controls[id].classList.add("show");  // Show on mouse enter
    });

    card.addEventListener("mouseleave", function() {
        // Hide control when mouse leaves the card
        const controls = document.querySelectorAll(controlsClass);
        controls[id].classList.remove("show");
    });

    // Mobile (Touch) Events
    card.addEventListener("touchstart", function() {
        const controls = document.querySelectorAll(controlsClass);
        controls[id].classList.add("show");  // Show on touch start
    });

    card.addEventListener("touchend", function() {
        const controls = document.querySelectorAll(controlsClass);
        controls[id].classList.remove("show");  // Hide on touch end
    });
};

// Add events for each folder card
folderCards.forEach((card, index) => {
    addCardEvents(card, index, ".folder-control");  // Pass the appropriate class for folder control
});

// Add events for each file card
fileCards.forEach((card, index) => {
    addCardEvents(card, index, ".file-card .control");  // For file cards, use the specific file card control class
});

// Add event listeners for control actions (to prevent card interaction)
document.querySelectorAll(".one-control").forEach(control => {
    control.addEventListener("click", function(event) {
        event.stopPropagation();  // This prevents the click from reaching the parent card
        console.log("Control clicked, action will be performed.");
    });
});

  const modal = document.getElementById("folder-modal");
        const modalContent = document.querySelector(".folder-modal-content");
        const closeBtn = document.querySelector(".close");
        const createFolderBtnHeader = document.getElementById("create-folder-btn-header");
        const createFolderBtn = document.getElementById("create-folder-btn");
        const folderNameInput = document.getElementById("folder-name");
        const folderContainer = document.getElementById("folder-container");

        // Show modal when the "create folder" button is clicked
        createFolderBtnHeader.addEventListener("click", function() {
            if(getResponse() == "upload-true") {
                modal.style.display = "block";
            }
            else{
                alert("Nemáte dostatek místa");
            } 
        });
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('vytvor')) {
        if(getResponse() == "upload-true") {
        modal.style.display = 'block';
        }
        else{
            alert("Nemáte dostatek místa");
        }
    }
        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
            urlParams.delete('vytvor'); 
        });

        // Close the modal if the user clicks outside the modal
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                urlParams.delete('vytvor'); 
                // remove it 
            }
        });
        function createFolder() {
            let title = document.querySelector("#folder-name").value;
            if(title) {
                $.ajax({
                    type:"POST",
                    url: '.././backend/cloudApp/user.php',
                    data: {createFolder:true,folderName:title.trim()}, 
                    success: function(response) {
                        urlParams.delete('vytvor'); 
                    location.reload();
            console.log("Server Response:", response);

        },
        error: function(xhr, status, error) {
            console.log(error);
        }
                })
            }
        }
        function getResponse() {
            let style = document.querySelector("#upload-file-btn-header").getAttribute("fx");
            return style;
        }
        document.querySelector("#upload-file-btn-header").addEventListener("click" , () =>{

            if(getResponse() == "upload-true") {
            fileInput.click();
            }
            else{
                alert("Nemáte dostatek místa");
            }
        })
    document.querySelector("#upload-file").addEventListener("click" , () =>{
        if(getResponse() == "upload-true") {
            fileInput.click();
        }
        else{
            alert("Nemáte dostatek místa");
            }
    })
    document.querySelector(".user-menu button").addEventListener("click" , () =>{
        if(getResponse() == "upload-true") {
            fileInput.click();
        }
        else{
            alert("Nemáte dostatek místa");
            }
    })
           
    if(urlParams.has("upload")) {
        if(getResponse() == "upload-true") {
       document.querySelector(".user-menu").style.display = "block";
        }
        else{
            alert("Nemáte dostatek místa");
        }
    }
        fileInput.addEventListener("change", (e) => {
    let file = e.target.files[0]; // Get the first file selected
    let reader = new FileReader();
    
    // Read the file
    reader.onload = function(event) {
        let fileData = event.target.result;
        let fileBlob = new Blob([fileData], { type: file.type });
        let formData = new FormData();

        // Extract the file name (no path)
        let name = fileInput.value.split("\\").pop();
      
        // Append the file to the FormData object
        formData.append("file", fileBlob, file.name); // file is the original file, not the blob
        formData.append("fileName", name); // fileName is extracted from the input value

        // Make the AJAX request to upload the file
        $.ajax({
            type: "POST", 
            url: "../backend/cloudApp/user.php", 
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response) {
                console.log(response);
                location.reload(true);
                // Handle success (e.g., display a success message, update the UI)
            },
            error: function(xhr, status, error) {
                console.error("File upload failed:", error);
                // Handle error (e.g., display an error message)
            }
        });
    };

    // Read the file as an ArrayBuffer
    reader.readAsArrayBuffer(file);
});


document.querySelectorAll(".folder-card").forEach((item) => {
    item.addEventListener("click", () => {
        let url = item.getAttribute("link");
        window.open(url , "_self");  // Open in a new tab
    });
});

document.querySelectorAll(".file-card").forEach((item, id) => {
    item.addEventListener("click", () => {
        let link = item.getAttribute("link");
        let fileExtension = link.split('.').pop().toLowerCase(); // Get file extension

        // Open PDF, Image, or other files directly in a new window
        if (fileExtension === 'pdf' || fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png' || fileExtension === 'gif') {
            window.open(link);
        }
        // For text files, read and display content in a new page
        else if (fileExtension === 'txt') {
            fetch(link)
                .then(response => response.text())
                .then(data => {
                    let newWindow = window.open();
                    newWindow.document.write('<pre>' + data + '</pre>'); // Display content in <pre> for better formatting
                    
                    // Provide download link for txt content
                    let downloadLink = document.createElement('a');
                    downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(data);
                    downloadLink.download = 'download.txt';
                    newWindow.document.body.appendChild(downloadLink);
                    downloadLink.innerText = 'Státhnout jako txt';
                })
                .catch(error => console.log('Error reading the text file:', error));
        }
        // For Word documents (.doc, .docx), extract text and offer a .txt download
        else if (fileExtension === 'doc' || fileExtension === 'docx') {
            fetch(link)
                .then(response => response.blob())
                .then(blob => {
                    // Using the `mammoth.js` library to extract text from Word files
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        let arrayBuffer = event.target.result;
                        mammoth.extractRawText({ arrayBuffer: arrayBuffer })
                            .then(function (result) {
                                let textContent = result.value;
                                let newWindow = window.open();
                                newWindow.document.write('<pre>' + textContent + '</pre>'); // Display extracted content

                                // Provide download link for extracted .txt content
                                let downloadLink = document.createElement('a');
                                downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(textContent);
                                downloadLink.download = 'download.txt';
                                newWindow.document.body.appendChild(downloadLink);
                                downloadLink.innerText = 'Státhnout jako txt';
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    };
                    reader.readAsArrayBuffer(blob);
                })
                .catch( alert("Soubour nelze otevřít jako word"));
        }
        // For PowerPoint documents (.ppt, .pptx), try opening in Google Slides (no text extraction for PPT files)
        else if (fileExtension === 'ppt' || fileExtension === 'pptx') {
            console.log(link)
            let googleSlidesUrl = 'https://docs.google.com/viewer?url=' + encodeURIComponent(link);
            window.open(googleSlidesUrl, '_blank');
        } else {
             alert("Soubour nelze otevřít,ale lze stáhnout")
        }
    });
});

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    console.log(urlParams);
    
    // Check if 'vytvor' exists in the URL
    if (urlParams.has('vytvor')) {
        urlParams.delete('vytvor'); // Remove the 'vytvor' parameter
        // Update the URL without reloading the page
        window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
    }
    // Check if 'onlySoubory' exists in the URL
    else if (urlParams.has('onlySoubory')) {
        // alert('The "onlySoubory" parameter exists in the URL!');
    }

   
  
};



// Check if 'upload' exists in the URL
if(urlParams.has("upload")) {
        // Hide the user menu
        // document.querySelector(".user-menu").style.display = "none";
        
        // Remove the 'upload' parameter from the URL
        urlParams.delete('upload');
        
        // Update the URL to remove 'upload' parameter and reload the page
        // If no other parameters remain, set the URL to just the base path
        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.replaceState({}, document.title, newUrl);
    }
    

function changeLink() {
    const link = document.querySelectorAll(".sidebar .links");
    link.forEach((item,id) =>{
        if(item.textContent.includes("profil")){
            item.href = "./pages/profile";

        }   
       
    })
}
onload = changeLink();
function downloadF(e) {
    e.preventDefault();
}

function deleteFolder(e) {
    let q = confirm("Are you sure you want to delete this folder?");
    if (q) {
        console.log("Folder deleted");
    } else {
        // If user cancels, redirect to the previous page or home page
        window.location.href = "../"; // Redirect to a different page (e.g., go back to the list page)
    }
}

document.querySelectorAll(".file-card .control div").forEach((item,id) =>{
    item.addEventListener("click" , () =>{
        let file = item.getAttribute("filename");

        switch(item.id) {
            case "download":
                let url = item.getAttribute("link");
                donwloadFile(url,file);
                // when user downlaod his file i have to update the time in db 
                break;
            case "rename":
                let newName = prompt("Zadejte nový název");
                if(!newName) {
                    alert("Zadejte název");
                }
                else{

                    let url = item.getAttribute("link");
                    // Example usage
                    if (!checkName(newName)) {
                        return alert("Chyba: Název složky obsahuje neplatné znaky.");
                    }

                    renameFile(newName,file,url);
                }
                break;
             default:
                let q = confirm("Opravdu chcete smazat následující soubor");
                if (q){
                    console.log(decodeText(file))
                  
                       deleteFile(file);
                }
               
                break;       

        }
    })
})

// FOLDERS
document.querySelectorAll(".folder-card .control div").forEach((item,id) =>{
    item.addEventListener("click" , () =>{
        let file = item.getAttribute("filename");
        let pathName = (item.getAttribute("foldername"));
        let dbName = (item.getAttribute("dbname"));


        // h2 => foreach,input forach 

        // find id by name in h2 and the input get id , filter it 

        switch(item.id) {
            case "download":
                // Example of dynamic parameters from input fields or variables
let folderId = 2;    // Can be dynamically assigned

// get atributs intead 
// Construct the URL dynamically
let downloadUrl = `../backend/cloudApp/downloadZIP.php?id=${folderId}&name=${decodeText(pathName)}`;

// Redirect to the dynamically constructed URL
location.href = downloadUrl;

                break;
                case "rename":
    let newName = prompt("Zadejte název: ");
    
    // Check if the user entered a new name
    if (!newName) return;


// Example usage
if (!checkName(newName)) {
    return alert("Chyba: Název složky obsahuje neplatné znaky.");
}
    $.ajax({
        type: "POST",
        url: "../backend/cloudApp/user.php",
        data: {
            renameFolder: true,
            pathName: decodeText(pathName),
            dbName: decodeText(dbName),
            newName: newName.trim()
        },
        success: function(response) {

            console.log(response);
            location.reload(true)
            // console.log(newName)
            
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
    break;

             default:


    $.ajax({
        type: "POST",
        url: "../backend/cloudApp/user.php",
        data: {
            removeFolder: true,
            pathName: decodeText(pathName),
            dbName: decodeText(dbName), // this is wrong 
        },
        success: function(response) {
        
            location.reload(true)
            console.log(response);

            // location.reload(true); // Uncomment if you want to reload the page after renaming
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
              
               
                break;       

        }
    })
})



function donwloadFile(url,file) {
    let decodedUrl = decodeText(url);
    let decodedFile =decodeText(file);
    let element = document.createElement("a");
    element.download = decodedFile;
    element.href = decodedUrl;
    element.click();
}

function deleteFile(file) {
    $.ajax({
        type: "POST",
        url: "../backend/cloudApp/user.php",
        data: {deleteFile:true,fileName:decodeText(file)},
        success: function(data) {
            console.log(data)
            
       
           location.reload(true);
        }
        ,
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
        }
    })
}

function renameFile(newName,file,url) {
    // let x = decodeURIComponent(escape(atob(name)))
    let extension = decodeText(file).split('.');
    let f = "";
    

    // If the file has an extension
    if (extension.length > 1) {
        // Extract the extension (last part of the array after split)
        let fileExtension = extension[extension.length - 1];
        
        // Create the new file name
        let newFile = newName + "." + fileExtension;
        f = newFile;
    } 

    let decodedFile = decodeText(file);
    if(newName != decodedFile) {
        $.ajax({
        type: "POST",
        url: "../backend/cloudApp/user.php",
        data: {renameFile:true,fileName:decodedFile,newName:f},
        success: function(data) {
          location.reload(true);
        }
        ,
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
        }
    }) 
    }
    
}

function decodeText(name) {
   // Decode the base64 first
   let decodedBase64 = atob(name); // Decode base64 string

   return decodeURIComponent(escape(decodedBase64));
} 

// Check if the 'vytvor' query parameter exists in the URL
// const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('vytvor')) {
    // Remove the 'vytvor' parameter
    urlParams.delete('vytvor');
    
    // Construct the new URL without the 'vytvor' parameter
    const newUrl = window.location.pathname + '?' + urlParams.toString();

    // Replace the current URL in the browser without reloading the page
    window.history.replaceState(null, '', newUrl);
}

function checkName(newName) {
    let unsupported = ["%", "#", "*", ";", "¤"];

    // Check if the newName contains any unsupported characters
    if (unsupported.some(char => newName.includes(char))) {
        return false; // Invalid name
    } else {
        return true; // Valid name
    }
}
document.querySelector(".sidebar #link").href = "./app";
function removeDiv(){
   location.reload();
}

</script>


</body>
</html>
