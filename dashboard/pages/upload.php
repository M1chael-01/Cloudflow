<?php
require "../../backend/isTeamAcc.php"; //   
require "../../backend/cloudApp/hasStorage.php"; //   
require "./sidebar.php";

require "../../backend/getID.php";
require "../../database/cloudApp/data.php";

$tc =  IsTeamAccount::isTeamAccount();
if($tc == "team") {
    $_SESSION["max-storage"] = 5 * 1024 * 1024 * 1024;
}
else{
    $_SESSION["max-storage"]= 1 * 1024*1024*1024;;;;
}


if(CheckStorage::checkStorage()) {
        
    $GLOBALS["upload"] = "upload-true";
}
else{
    $GLOBALS["upload"] = "upload-false";
}

$data = data();

$id = GetID::getID();


function getName($id) {
    global $data; 

    if (isset($_GET["file"])) {
        // Decode the base64 encoded file parameter
        $file = base64_decode($_GET["file"]);

        // Prepare the SQL statement to query for the folder name and disk name
        $stmt = $data->prepare("SELECT folder_name, disk_name FROM data WHERE folder_name = ? AND user = ?");
        
        // Bind the parameters to prevent SQL injection
        $stmt->bind_param("si", $file, $id);
        
        // Execute the query
        $stmt->execute();
        
        // Bind result variables
        $stmt->bind_result($folder_name, $disk_name); // Also bind $disk_name

        // Check if a result is found
        if ($stmt->fetch()) {
            // Folder name exists, set the cookie for disk_name
            setcookie("folder", base64_encode($disk_name), time() + 3600, "/"); // Cookie expires in 1 hour
            $_SESSION["folder"] =  base64_encode($disk_name);


            if(isset($_SESSION["folder"] ) && isset($_COOKIE["folder"])) { 
            if($_SESSION["folder"] != $_COOKIE["folder"]) {
                setcookie("folder",  $_SESSION["folder"], time() + 3600, "/");
            }
        }
            
            // Return true to indicate the folder exists
            return true;
        } else {
            // Folder name does not exist, return false
            return false;
        }

        // Close the statement
        $stmt->close();
    }

    // If $_GET["file"] is not set, return false
    return false;
}


if (getName($id)) {
    $GLOBALS["name"] = base64_decode($_GET["file"]);
} else {
    echo "<script>location.href = '../app'</script>";
}


function readData() {
    global $data, $id;
    
    // Check if "file" parameter is passed in the URL (base64 decoded)
    if (isset($_GET["file"])) {
        $file = base64_decode($_GET["file"]);
    }
    
    $sql = "SELECT files FROM data WHERE user = ? AND folder_name = ? AND disk_name != ?";
    
    // Prepare the query
    $stmt = $data->prepare($sql);
    $user = $id;
    $disk_name = "-"; 
    
    // Bind the parameters (Note: we now have 3 parameters to bind)
    $stmt->bind_param("iss", $user, $file, $disk_name);  // "i" for integer and "s" for strings
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Initialize an array to store files
    $files = [];
    
    // Fetch the files from the result
    while ($row = $result->fetch_assoc()) {
        // Decode the JSON string into an array of files
        $decodedFiles = json_decode($row['files'], true); // Decode the JSON into an associative array
        
        // Merge the decoded files into the $files array
        if (is_array($decodedFiles)) {
            $files = array_merge($files, $decodedFiles);
        }
    }
    
    // Close the statement
    $stmt->close();
    
    // Return the array of files
    return $files;
}

$files = readData();  // Get the list of files


function getDbFileName() {
    global $data, $id;

    if (!isset($_GET["file"])) {
        return false; // Return false if the file parameter is missing
    }

    // Get the user ID
    $user = $id;

    // Decode the file parameter from base64
    $file = base64_decode($_GET["file"]);

    // Prepare the SQL query
    $stmt = $data->prepare("SELECT disk_name FROM data WHERE folder_name = ? AND user = ?");
    
    // Bind the parameters to the query
    // Both file and user are strings, so we use "ss" for binding
    $stmt->bind_param("ss", $file, $user);

    // Execute the statement
    $stmt->execute();

    // Bind the result variable
    $stmt->bind_result($disk_name);

    // Fetch the result
    if ($stmt->fetch()) {
        // Return the disk name if found
        return $disk_name;
    } else {
        // Return false if no matching record was found
        return false;
    }

    // Close the statement
    $stmt->close();
}

$diskName = getDbFileName();
$GLOBALS["diskName"] = $diskName ;


if($diskName) {
    $_SESSION["fs"] = $diskName ;;
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
    $totalSize = 0;

    if (is_dir($folder)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        // Loop through each file and add its size to the total
        foreach ($files as $fileinfo) {
            if ($fileinfo->isFile() && file_exists($fileinfo->getRealPath())) {
                $totalSize += $fileinfo->getSize(); // Add file size to total
            }
        }
    }
    return $totalSize;
}

function getFsName() {}
$GLOBALS["url"] =  "../../dashboard/uploads/users/".$_SESSION["user_id"]."/".base64_decode($_SESSION["folder"]);

?>

<!-- used image:
 <a href="https://www.freepik.com/free-vector/no-data-concept-illustration_5928292.htm#fromView=image_search_similar&page=1&position=2&uuid=f2538c04-6abd-4171-bd35-f1c9e7d2e4a3&query=no+folder">Image by storyset on Freepik</a>
  -->
 <head>
    <title>Složka</title>
    <link rel="stylesheet" href="../styles/upload.css">
<link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"
/>
 </head>
 <?php
// Utility function to get file icon based on file extension
function getFileIcon($extension) {
    $iconMap = [
        'png' => '../../images/app/photo.png',
        'jpg' => '../../images/app/photo.png',
        'jpeg' => '../../images/app/photo.png',
        'gif' => '../../images/app/photo.png',
        'pdf' => '../../images/app/pdf.png',
        'doc' => '../../images/app/doc.png',
        'docx' => '../../images/app/doc.png',
        'txt' => '../../images/app/txt.png',
        'ppt' => '../../images/app/ppt.png',
        'pptx' => '../../images/app/ppt.png',
    ];

    // Default icon for unsupported file types
    return $iconMap[strtolower($extension)] ?? '../../images/app/nf.png';
}

// Utility function to safely get file size
function getFileSize($filePath) {
    return file_exists($filePath) ? convertUnit(filesize($filePath)) : 'Unknown size';
}

// Check if folder and files are set
if (isset($GLOBALS["name"]) && isset($files)) {
    $_SESSION["currentFolder"] = $GLOBALS["diskName"]; // Set current folder in session
}
?>

<section>
    <div class="content">
        <h2>
            <i class="ri-folder-line"></i> Složka : <span> <?= htmlspecialchars($GLOBALS["name"]) ?></span>
        </h2>
        <p>
            Nahrajte na svůj účet soubory, jako jsou obrázky, soubory PDF nebo dokumenty. Podporované formáty: JPG, PNG, PDF atd.
        </p>
        <button fx="<?= htmlspecialchars($GLOBALS["upload"]) ?>" class="upload">Nahrajte soubory </button>
        <input type="file" multiple id="files" class="files" name="files">
    </div>

    <?php if (empty($files)): ?>
        <!-- No files available -->
        <img src="../../images/app/no-data (2).png" alt="No files">
        <h3>Zatím zde nemáme žádné soubory :(</h3>
    <?php else: ?>
        <!-- Files available -->
        <div class="flex">
            <?php foreach ($files as $file): ?>
                <?php
                    // Skip invalid files (e.g., empty or placeholder)
                    if (empty($file) || $file == "-") {
                        continue;
                    }

                    // Generate file path
                    $filePath = $GLOBALS["url"] . "/" . $file;

                    // Check if file exists before proceeding
                    if (!file_exists($filePath)) {
                        continue;
                    }

                    // Get file extension and icon
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $icon = getFileIcon($extension);
                    $fileSize = getFileSize($filePath);

                    // Encode file and disk name for use in links and controls
                    $encodedFileName = base64_encode(htmlentities($file));
                    $encodedDiskName = base64_encode($GLOBALS["diskName"]);
                    $encodedFileLink = base64_encode($filePath);
                ?>

                <div class="one" link="<?= htmlspecialchars($encodedFileLink) ?>">
                    <img src="<?= htmlspecialchars($icon) ?>" alt="File Icon">
                    <h2><?= htmlspecialchars($file) ?></h2> <!-- Display file name -->
                    <br>
                    <h3><?= htmlspecialchars($fileSize) ?></h3> <!-- Display file size -->

                    <div class="control">
                        <div link="<?= htmlspecialchars($encodedFileLink) ?>" 
                             filename="<?= htmlspecialchars($encodedFileName) ?>" 
                             disk="<?= htmlspecialchars($encodedDiskName) ?>" 
                             id="download" title="stáhněte si svůj soubor" 
                             class="one-control">
                            <i class="ri-download-line"></i>
                        </div>

                     
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

   
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- this library is used to read a files word .... -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
<script>
    // Set image source for footer logo (optional)
    let img = document.querySelector("img").src = "../../images/others/footer-logo.png";

    // Get file input and button
    const input = document.querySelector("#files");
    const btn = document.querySelector(".upload");

    btn.addEventListener("click" , () =>{
        let opt = btn.getAttribute("fx");
        if(opt == "upload-false") {
            alert("Nemáte dostatek místa");
        }
        else{
            input.click();
        }
    })

    // Event listener for when files are selected
    input.addEventListener("change", () => {
        // Get the folder name from the span element in the header
        let folderName = document.querySelector(".content h2 span").textContent;

        // Get the selected files
        const files = input.files;
        if (files.length > 0) {
            // Displaying selected file names
            const fileList = Array.from(files);
            let fileNames = fileList.map(file => file.name).join(", ");
            const fileMessage = document.querySelector("h3");
         //   fileMessage.textContent = `Vybrané soubory: ${fileNames}`;

            // Creating FormData object to send files via AJAX
            const formData = new FormData();
            
            // Append the folder name to the FormData object
            formData.append("folderName", folderName);

            // Append each file to the FormData object
            for (let i = 0; i < files.length; i++) {
                formData.append("files[]", files[i]);
            }

            $.ajax({
    url: '../../backend/cloudApp/user.php', 
    type: 'POST',
    data: formData,
    contentType: false, 
    processData: false, 
    dataType: 'json',  // Expect JSON response
    success: function(response) {
        console.log('Response from server:', response);

        if (response.success) {
            console.log('Files uploaded:', response.files);
           location.reload();
        } else {
            console.error('Upload error:', response.error);
            alert('Error  ' + response.error);
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown);
        alert('error, více v konzoli.');
    }
});

        }
    });

document.querySelectorAll(".flex .one").forEach((item) => {
    item.addEventListener("click", () => {
        let link =decodeText(item.getAttribute("link"));
     
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
                    downloadLink.innerText = 'Stáhnout jako TXT';
                })
                .catch(error => console.log('Error reading the text file:', error));
        }
        // For Word documents (.doc, .docx), extract text and offer a .txt download
        else if (fileExtension === 'doc' || fileExtension === 'docx') {
            fetch(link)
                .then(response => response.blob())
                .then(blob => {
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
                                downloadLink.innerText = 'Stáhnout jako TXT';
                            })
                            .catch(function (error) {
                                console.log('Error extracting text from Word file:', error);
                            });
                    };
                    reader.readAsArrayBuffer(blob);
                })
                .catch(error => console.log('Error fetching Word file:', error));
        }
        // For PowerPoint documents (.ppt, .pptx), try opening in Google Slides (no text extraction for PPT files)
        else if (fileExtension === 'ppt' || fileExtension === 'pptx') {
            // this would work if wouldnt use localhost 
            let googleSlidesUrl = 'https://docs.google.com/viewer?url=' + encodeURIComponent(link);
            window.open(googleSlidesUrl, '_blank');
        } else {
            alert("Soubor nelze otevřít,zkuste ho stáhnout");
            console.log('Unsupported file type');
        }
    });
});

document.querySelectorAll(".flex .one").forEach((item, id) => {
    // Add the touch event listeners for mobile
    item.addEventListener("touchstart", function() {
        document.querySelectorAll(".control")[id].classList.add("show");
    });
    item.addEventListener("touchend", function() {
        document.querySelectorAll(".control")[id].classList.remove("show");
    });

    // Add mouse events for desktop (mouseenter and mouseleave)
    item.addEventListener("mouseenter", function() {
        document.querySelectorAll(".control")[id].classList.add("show");
    });
    item.addEventListener("mouseleave", function() {
        document.querySelectorAll(".control")[id].classList.remove("show");
    });
});
document.querySelectorAll(".one-control").forEach(control => {
    control.addEventListener("click", function(event) {
        event.stopPropagation();  // This prevents the click from reaching the parent card
        // You can add specific actions for each control here (e.g., delete, download, etc.)
        console.log("Control clicked, action will be performed.");
    });
});


let folderName = "";
document.querySelectorAll(".control div").forEach((item,id) =>{
    item.addEventListener("click" , () =>{
        let file = item.getAttribute("filename");
        folderName = item.getAttribute("disk");
        let link = item.getAttribute("link");

        switch(item.id) {
            case "download":
                
                donwloadFile(link,file);
                // let url = item.getAttribute("link");
                // donwloadFile(url,file);
                // // when user downlaod his file i have to update the time in db 
                break;
            case "rename":
                 let newName = prompt("Zadejte nový název");
                 if(!newName) {
                    alert("Zadejte název");
             }
                 else{
                    let current = decodeText(item.getAttribute("filename"));
                    let url = item.getAttribute("link");
                     renameFile(newName,file,url,current)
                 }
                break;
             default:
                
                // let folderName = 
                let q = confirm("Opravdu chcete smazat následující soubor");
             if (q){
                        deleteFile(file);
                 }
               
                break;       

        }
    })
})

function renameFile(newName, file, url, current) {
    console.log(newName);
    console.log(decodeText(file));  
    console.log(decodeText(url));   
    console.log(current);
    let extension = current.split('.').pop();
    if (!checkName(newName)) {
            return alert("Chyba: Název složky obsahuje neplatné znaky.");
        }

    $.ajax({
        type: "POST",
        url: "../../backend/cloudApp/user.php",
        data: {
            renameGroup: true,
            newName: newName,
            file: decodeText(file),  
            url: decodeText(url),    
            currentName: current, 
             exetion:extension   
        },
        success: function(data) {
        // location.reload(true)
        console.log(data);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);  
        }
    });
}

function donwloadFile(url,file) {
    console.log(decodeText(url));

    let a = document.createElement("a");
    a.download = decodeText(file)
    a.href = decodeText(url);
    a.click();
    return;
}

function deleteFile(file) {
    $.ajax({
        type: "POST",
        url: "../../backend/cloudApp/user.php",
        data: {deleteFile:true,fileName:decodeText(file),inUserFolder:true,folderName:decodeText(folderName)},
        success: function(data) {
          location.reload(true);
        }
        ,
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
        }
    })
}

function decodeText(name) {
   return decodeURIComponent(escape(atob(name))); // decode the base64 encoded string, escape any special characters that might exist in the decoded string
   //decode the escaped string using decodeURIComponent to get the original text
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

function change() {
    const link = document.querySelectorAll(".sidebar a");
    link.forEach((item,id) =>{
        if(item.textContent.includes("Vytvoř")){
            item.href = "../app?vytvor";

        }   
        else if(item.textContent.includes("soubor")){
            item.href = "../app?upload";

        }  
        else if(item.textContent.includes("aplikaci")) {
            item.href = "./about";
        } 
        else if(item.textContent.includes("Odhlásit")) {
            item.href = "../../backend/cloudApp/logout.php"
        }
        else{
            item.classList.remove("active");
        }
    })
}
onload = change();
</script>
