<?php

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require "../../database/cloudApp/users.php";
require "../../database/cloudApp/data.php";
require "../../backend/getID.php";
require "../sendCode.php";

// Create the database connection
$connection = users();
$data = data();;

// Check if the connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

class User {
    public $name;
    public $email;
    public $accountType;
    public $team;
    private $password;
    private $connection;

    private static $encryptionKey = 'p@assWW03rd_ke1'; 

    // Constructor to initialize the properties and database connection
    public function __construct($name, $email, $password, $accountType, $team = null, $connection) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password; // Password will be hashed
        $this->accountType = $accountType;

        // If team is not provided, set it to "no-team"
        $this->team = $team ? $team : 'no-team';

        $this->connection = $connection; // Store the database connection
    }

    // AES Encryption (Encrypt sensitive data like name, email, and team)
    private function encrypt($data) {
        return openssl_encrypt($data, 'aes-256-cbc', self::$encryptionKey, 0, substr(hash('sha256', self::$encryptionKey), 0, 16));
    }

    // AES Decryption (Decrypt sensitive data when needed)
    private function decrypt($data) {
        return openssl_decrypt($data, 'aes-256-cbc', self::$encryptionKey, 0, substr(hash('sha256', self::$encryptionKey), 0, 16));
    }

    // Method to save the user data to the database
    public function saveUser() {
        // Check if the user already exists
        if ($this->checkUser()) {
            setcookie("userExist", true, time() + (86400 * 30), "/");
            return "User already exists with this email address.";
        }

        // Encrypt sensitive data like name, email, and team
        $encryptedName = $this->encrypt($this->name); // Encrypt the name
        $encryptedEmail = $this->encrypt($this->email);
        $encryptedTeam = $this->encrypt($this->team); // Encrypt team value

        // Password hashing
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert the user data into the database
        $stmt = $this->connection->prepare("INSERT INTO uzivatel (jmeno, email, heslo,account_type, team) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Error preparing statement: ' . $this->connection->error); // Detailed error message
        }

        // Bind parameters with sanitized data
        $stmt->bind_param("sssss", $encryptedName, $encryptedEmail, $hashedPassword, $this->accountType, $encryptedTeam);

        // Execute the statement and handle any error
        if ($stmt->execute()) {
            // Get the last inserted ID (this is typically the `AUTO_INCREMENT` id column)
            $userId = $stmt->insert_id;  // This retrieves the inserted user's ID

            // Set session variables
            $_SESSION["active"] = true;  // This sets the user as active
            $_SESSION["user_id"] = $userId;  // Store the user ID in session

            $this->rememberUser($userId); // Optionally, store the user ID for remembering

            return "User {$this->name} has been successfully registered with ID {$userId}."; // Return the user ID in the response
        } else {
            // Log the specific error for debugging purposes
            return "There was an error during registration. Please try again. Error: " . $stmt->error;
        }
    }

   private function rememberUser($id) {
        // Store user ID in session
        $_SESSION["user_id"] = $id;
        $_SESSION["active"] = true;
        // Additional system and location information
        $_SESSION["user_info"] = [
            "user_id" => $id,
            "user_agent" => $_SERVER['HTTP_USER_AGENT'], // Browser/OS information
            "ip_address" => $_SERVER['REMOTE_ADDR'],     // User IP address 
            // "location" =>                // Custom function to get location
            "timestamp" => time(),                       // Time of login or last action
        ];
    
    }
    
    public function login($username, $password) {
        global $connection;
    
        // SQL query to fetch all user data
        $sql = "SELECT * FROM uzivatel";
        
        // Execute the query
        $result = mysqli_query($connection, $sql);
        
        // If no users found, return false or handle error
        if (mysqli_num_rows($result) == 0) {
            echo "No users found.<br>";
            return false;
        }
    
        // Fetch each user and decrypt their 'jmeno' field
        while ($user = mysqli_fetch_assoc($result)) {
            // Decrypt the 'jmeno' field (assuming it's encrypted)
            $decryptedJmeno = $this->decrypt($user['jmeno']);
            
            // Check if the decrypted 'jmeno' matches the provided username
            if ($decryptedJmeno == $username) {
                // Echo the username before any further logic if needed
                echo "Username found: " . $username . "<br>";
                
                // Verify password if it's hashed
                if (password_verify($password, $user['heslo'])) {
                    // Password matches, user is logged in
                    // Call rememberUser function (passing the user id)
                    $this->rememberUser($user['id']);
        
                    // Return user data with decrypted name and other necessary details
                    return [
                        'username' => $user['jmeno'],
                        'jmeno' => $decryptedJmeno,
                        'id' => $user['id'],
                        // Add any other relevant user details, such as email, etc.
                    ];
                } else {
                    // Password doesn't match
                    echo "Incorrect password.<br>";
                    return false;
                }
            }
        }
    
        // If no match found for username, return false
        echo "Username not found.<br>";
        return false;
    }

   // Create a new folder on the server
   public function createFolder($name) {
    // Input validation: Ensure folder name isn't empty and is safe
    if (empty($name)) {
        echo "Folder name is required.";
        return;
    }
    $storedName = $name;
    // 

    // Sanitize the folder name
    $folderName = preg_replace("/[^a-zA-Z0-9_-]/", "", $name);  // Only allow alphanumeric, dashes, and underscores
    
    if (empty($folderName)) {
        echo "Invalid folder name.";
        return;
    }

    // Path where you want to create the folder
    $targetPath = "../../dashboard/uploads/users/" . $_SESSION["user_id"] . "/" . $folderName;

    // Check if the folder already exists
    if (is_dir($targetPath)) {
        echo "Folder already exists.";
        return;
    }

    // Create the folder on the server
    if (mkdir($targetPath, 0777, true)) {
        echo "Folder '$folderName' created successfully!";
        $this->saveFolder($storedName,$targetPath,$folderName);
    } else {
        echo "Error creating folder.";
    }
}

private function saveFolder($storedName,$targetPath,$folderName) {
    global $data;
    if (isset($_SESSION["user_id"])) {
        $user = $_SESSION["user_id"];
        $diskName = $folderName;  
        $folderName = $storedName;
        $files = json_encode([]);  
        $backup = json_encode([]);
        $users = json_encode([]);
        $createdTime = date("Y-m-d H:i:s");
        var_dump($createdTime); 
    
        // Prepare the SQL statement
        $stmt = $data->prepare("INSERT INTO data (user, folder_name, disk_name, files, backup, users, last_change) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
        // Bind the parameters to the placeholders
        $stmt->bind_param("sssssss", $user, $folderName, $diskName, $files, $backup, $users, $createdTime);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo "Data inserted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the statement
        $stmt->close();
    }
}

// Handle file upload
public function uploadFile($fileName) {
    // Check if the file was uploaded without errors
    if ($_FILES['file']['error'] == 0) {
        // Get file information
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileType = $_FILES['file']['type'];

        $allowedTypes = [
            'image/jpeg',    // JPEG images
            'image/png',     // PNG images
            'application/pdf',  // PDF files
            'text/plain',    // TXT files
            'application/msword',  // Word 97-2003 (DOC) files
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  // Word 2007+ (DOCX) files
            'application/vnd.ms-powerpoint',  // PowerPoint 97-2003 (PPT) files
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',  // PowerPoint 2007+ (PPTX) files
            'video/mp4',     // MP4 video files
            'video/webm',    // WebM video files
            'video/ogg',      // Ogg video files
            'audio/mp3'
        ];
        // Check if file type is allowed
        if (in_array($fileType, $allowedTypes)) {
            // Ensure that the user is logged in
            if (isset($_SESSION["user_id"])) {
                $userId = $_SESSION["user_id"];

                // Define the upload directory
                $uploadDir = '../../dashboard/uploads/users/' . $userId;

                // Create the directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);  // Create the directory if it doesn't exist
                }

                // Sanitize the file name to avoid conflicts and security issues
                $sanitizedFileName = $fileName;

                // Define the full path where the file will be saved
                $uploadFilePath = $uploadDir . '/' . $sanitizedFileName;

                // Move the uploaded file to the upload directory
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    echo "File uploaded successfully!";

                    // Optionally: Save file details to the database
                    $this->saveFileToDatabase($sanitizedFileName, $uploadFilePath, $_FILES['file']['size'], $fileType);
                } else {
                    echo "Error: There was an issue uploading the file.";
                }
            } else {
                echo "Error: User is not logged in.";
            }
        } else {
            echo "Error: Invalid file type. Only JPEG, PNG, PDF, and TXT files are allowed.";
        }
    } else {
        // Handle different upload errors
        $this->handleFileUploadError($_FILES['file']['error']);
    }
}
// Function to handle specific file upload errors
private function handleFileUploadError($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "Error: The file exceeds the maximum upload size.";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "Error: The file was only partially uploaded.";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "Error: No file was uploaded.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "Error: Missing temporary folder.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "Error: Failed to write file to disk.";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "Error: File upload stopped by extension.";
            break;
        default:
            echo "Error: An unknown error occurred during the file upload.";
            break;
    }
}

// Optionally, save file details to the database
private function saveFileToDatabase($fileName, $filePath, $fileSize, $fileType) {
    global $data;

    // Set default values for folder and disk names, or assign dynamically
    $folderName = "-"; // This should be dynamically set if needed
    $diskName = "-";   // This should be dynamically set if needed

    // Get current timestamp for last change
    $lastChange = date("Y-m-d H:i");

    // Debug: Check if the date is generated properly
    echo "Last change timestamp: " . $lastChange;

    // Prepare the SQL query to insert into the 'data' table
    $query = "INSERT INTO data (user, folder_name, disk_name, files, backup, users, last_change) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $data->prepare($query)) {

        // JSON-encode the file and other necessary arrays
        $files = json_encode([$fileName]);   // Assuming you're saving file names as a JSON array
        $backup = json_encode([]);           // Backup array, empty for now
        $users = json_encode([]);            // Users array, empty for now

        // Bind the parameters (user_id, folder_name, disk_name, files, backup, users, last_change)
        $stmt->bind_param("sssssss", $_SESSION["user_id"], $folderName, $diskName, $files, $backup, $users, $lastChange);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            echo "File details saved successfully!";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $data->error;
    }
}
private function createBackup() {}


    public function sendEmail($email) {
        global $connection;
         // SQL query to fetch all user data
         $sql = "SELECT email FROM uzivatel";
        
         // Execute the query
         $result = mysqli_query($connection, $sql);
         
         // If no users found, return false or handle error
         if (mysqli_num_rows($result) == 0) {
             mysqli_close($connection);
             return false;
         }
         while ($user = mysqli_fetch_assoc($result)) {
            $decryptedEmail= $this->decrypt($user['email']);
            if($decryptedEmail == $email) {
                echo $user;
                break;
            }
         }

    }
    public function uploadFolderFiles() {
        global $data;
    
        // Retrieve the user ID
        $id = GetID::getID();
    
        // Folder name (can be dynamic or from the cookie)
        $folderName = base64_decode($_COOKIE["folder"]);
    
        // Build the directory path where files will be stored
        $uploadDir = "../../dashboard/uploads/users/$id/$folderName"; // Directory path for the user
    
        // Ensure the directory exists, otherwise create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory with proper permissions
        }
    
        // Check if files are uploaded
        if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {
            $uploadedFiles = [];
            $errors = [];
    
            // Loop through the uploaded files and handle them
            for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                $fileName = $_FILES['files']['name'][$i];
                $fileTmpName = $_FILES['files']['tmp_name'][$i];
                $fileSize = $_FILES['files']['size'][$i];
                $fileError = $_FILES['files']['error'][$i];
                $fileType = $_FILES['files']['type'][$i];
    
                // Ensure the file was uploaded without errors
                if ($fileError === UPLOAD_ERR_OK) {
                    // Build the full target path for the uploaded file
                    $targetPath = $uploadDir . DIRECTORY_SEPARATOR . basename($fileName);
    
                    // Move the file to the new directory
                    if (move_uploaded_file($fileTmpName, $targetPath)) {
                        $uploadedFiles[] = $fileName; // Keep track of successfully uploaded files
                    } else {
                        $errors[] = "Error moving file: $fileName";
                    }
                } else {
                    $errors[] = "Error uploading file: $fileName (Error code: $fileError)";
                }
            }
    
            // If there are no errors, proceed to update the database
            if (empty($errors)) {
                // Get the existing files from the database
                $existingFiles = $this->getFilesArray($id, $folderName); // Get old files array
                
                // If no files exist in the database, initialize the existing files array to an empty array
                if (empty($existingFiles)) {
                    $existingFiles = [];
                }
    
                // Merge new files with existing ones and remove duplicates
                $mergedFiles = array_merge($existingFiles, $uploadedFiles);
                $mergedFiles = array_values(array_unique($mergedFiles)); // Re-index and remove duplicates
    
                // Convert array to JSON (to be saved in DB)
                $jsonEncodedArray = json_encode($mergedFiles, JSON_UNESCAPED_UNICODE);  //Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
                
                // Directly execute the UPDATE statement
                $stmt = $data->prepare("UPDATE data SET files = ? WHERE user = ? AND disk_name = ?");
                $stmt->bind_param("sis", $jsonEncodedArray, $id, $folderName);
                $stmt->execute();
                $stmt->close();
    
                // Set correct headers for JSON response
                header('Content-Type: application/json');
    
                // Return success with the uploaded files
                echo json_encode([
                    'success' => true,
                    'files' => implode(', ', $uploadedFiles) // Join file names into a string for the response
                ]);
                exit; // Ensure no additional output is sent
            } else {
                // Return error messages if there were any issues during upload
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => implode(', ', $errors)
                ]);
                exit; 
            }
        } else {
            // No files uploaded
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'No files uploaded.'
            ]);
            exit; 
        }
    }
    
    // Assuming your User class has a $data property for the database connection
    public function getFilesArray($userId, $folderName) {
        global $data;  // Global database connection
    
        // Prepare the SQL query to retrieve the existing files for the user and folder
        $query = "SELECT files FROM data WHERE user = ? AND disk_name = ?";
        $stmt = mysqli_prepare($data, $query);
        mysqli_stmt_bind_param($stmt, 'is', $userId, $folderName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $filesJson);
        mysqli_stmt_fetch($stmt);
    
        // Check if files are found, and if so, decode the JSON into an array
        if ($filesJson) {
            return json_decode($filesJson, true);  // Return the files as an array
        }
    
        // No files found, return an empty array
        return [];
    }
    
    public function deleteFile($name) {
        // Retrieve the user ID
        $id = GetID::getID();
    
        $GLOBALS["inFolder"] = "";
    
        // Construct the file path using proper string concatenation
        if (isset($_POST["folderName"])) {
            if (!empty($_POST["folderName"])) {
                $GLOBALS["folderName"] = $_POST["folderName"];
                $GLOBALS["inFolder"] = true;


                // IMPORTNAT SESSION 
                $ff = $_SESSION["fs"];
                $GLOBALS["direct"] = "../../dashboard/uploads/users/" . $id . "/" .$ff . "/" . $name;
            }
        } else {
            $GLOBALS["inFolder"] = false;
            $GLOBALS["direct"] = "../../dashboard/uploads/users/" . $id . "/" . $name;
        }
    
        // Check if the file exists before attempting to delete
        if (file_exists($GLOBALS["direct"])) {
            // Attempt to delete the file
            if (unlink($GLOBALS["direct"])) {
                // File deleted successfully 
                echo "File deleted successfully.";
    
                // Add third parameter to delete file from DB based on whether it's in a folder or not
                if ($GLOBALS["inFolder"]) {
                    $this->updateFileDbFolderArray($name, $id);
                } else {
                    $this->deleteFileDB($name, $id);
                }
    
            } else {
                // Error deleting the file
                echo "Error deleting the file.";
            }
        } else {
            // File does not exist
            echo $GLOBALS["direct"];
        }
    }
    
    private function deleteFileDB($name, $id) {
        global $data;
        // Prepare the SQL statement to remove the file from the 'files' array
        $sql = "UPDATE data SET files = JSON_REMOVE(files, JSON_UNQUOTE(JSON_SEARCH(files, 'one', ?))) 
                WHERE user = ? AND JSON_CONTAINS(files, ?)";
    
        // Prepare the SQL statement
        $stmt = $data->prepare($sql);
    
        // Convert the name into a JSON format for matching
        $namePattern = json_encode([$name]);
    
        // Bind the parameters and execute the query
        $stmt->bind_param("sis", $name, $id, $namePattern);
        $stmt->execute();
    
        // Optionally check if the delete was successful
        if ($stmt->affected_rows > 0) {
            echo "File removed from the database.";
        } else {
            echo "No file found to remove from the database.";
        }
    }
    private function updateFileDbFolderArray($name, $id) {
        global $data;
    
        // Step 1: Select the 'files' column for the user and folder
        $folderName = $_SESSION["fs"];  // Make sure 'fs' session variable holds the correct folder name
        $stmt = $data->prepare("SELECT files FROM data WHERE user = ? AND disk_name = ?");
        $stmt->bind_param("is", $id, $folderName);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Step 2: Check if the folder and files exist
        if ($result->num_rows > 0) {
            // Fetch the existing 'files' column, which is stored as a JSON array
            $row = $result->fetch_assoc();
            $filesArray = json_decode($row['files'], true);  // Convert JSON to PHP array
    
            // Step 3: Check if the file exists in the array and remove it
            if (($key = array_search($name, $filesArray)) !== false) {
                // File found, now remove it
                unset($filesArray[$key]);
    
                // Re-index the array if necessary
                $filesArray = array_values($filesArray);
    
                // Step 4: Encode the modified array back to JSON
                $newFilesJson = json_encode($filesArray);
    
                // Step 5: Update the database with the modified file list
                $sqlUpdate = "UPDATE data SET files = ? WHERE user = ? AND disk_name = ?";
                $stmtUpdate = $data->prepare($sqlUpdate);
                $stmtUpdate->bind_param("sis", $newFilesJson, $id, $folderName);
                $stmtUpdate->execute();
    
                // Step 6: Check if the update was successful
                if ($stmtUpdate->affected_rows > 0) {
                    echo "File removed from the folder and database.";
                } else {
                    echo "Failed to update the folder's file list.";
                }
            } else {
                // File not found in the array
                echo "File not found in the array.";
            }
        } else {
            // Folder or files not found in the database
            echo "Folder or files not found in the database.";
        }
    }
  
    public function renameFile($name, $newName) {
        // Retrieve the user ID
        $id = GetID::getID();
    
        // For group rename, use the URL and the group name
        if (isset($_POST["renameGroup"])) {
            $current = $_POST["url"];
            $extension = pathinfo($current, PATHINFO_EXTENSION); // get exetion png,tct etc.
            $new = "../../dashboard/uploads/users/" . $id . "/" . base64_decode($_SESSION["folder"]) . "/" . $newName . "." . $extension;
            $direct = $current;
            $newDirect = $new;

        } else {
            // For individual file rename
            $direct = "../../dashboard/uploads/users/" . $id . "/" . $name;
            $newDirect = "../../dashboard/uploads/users/" . $id . "/" . $newName;
        }
    
        echo "Original file path: " . $direct . "<br>";
        echo "New file path: " . $newDirect . "<br>";
    
        // Step 1: Check if the file exists
        if (file_exists($direct)) {
            // Step 2: Rename the file
            if (rename($direct, $newDirect)) {
                // File renamed successfully
                echo "File renamed successfully.<br>";
    
                // Update database if necessary
                if (!isset($_POST["renameGroup"])) {
                    $this->updateFileNameInDB($name, $newName, $id);
                } else {
                    $folder = base64_decode($_SESSION["folder"]);
                    $this->updateGroupFileName($id, $newName, $folder, $name);
                }
            } else {
                echo "Error renaming the file. Ensure proper file permissions.<br>";
                error_log("Failed to rename: " . $direct . " to " . $newDirect);
            }
        } else {
            echo "The file does not exist at: " . $direct . "<br>";
        }
    }
    
    private function updateGroupFileName($id, $newName, $folder, $current) {
        global $data;
        // 1. Prepare and execute the query to get the files associated with the user and folder
        $stmt = $data->prepare("SELECT files FROM data WHERE user = ? AND disk_name = ?");
        $stmt->bind_param("is", $id, $folder);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // 2. Check if files were returned from the database
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filesArray = json_decode($row['files'], true);  // Convert JSON to PHP array
    
            // 3. Check if the current file exists in the array and replace it
            $found = false;
            $currentExtension = pathinfo($current, PATHINFO_EXTENSION); // Get the current file extension
    
            // Add the correct extension to the new name
            $newFileName = $newName . '.' . $currentExtension;
    
            // Loop through files and replace the correct file with the new name
            foreach ($filesArray as $key => &$file) {
                if ($file === $current) {
                    $file = $newFileName;  // Replace the old file with the new name
                    $found = true;
                    break;  // Exit loop after replacing the file
                }
            }
    
            // 4. If the file was found and renamed, update the database
            if ($found) {
                // Convert the updated array back to JSON
                $updatedFiles = json_encode($filesArray);
    
                // Prepare the update query
                $updateStmt = $data->prepare("UPDATE data SET files = ? WHERE user = ? AND disk_name = ?");
                $updateStmt->bind_param("sis", $updatedFiles, $id, $folder);
    
                // Execute the update query
                if ($updateStmt->execute()) {
                    echo "File name updated in the database successfully.<br>";
                } else {
                    echo "Error updating the database.<br>";
                }
            } else {
                echo "File not found in the list.<br>";
            }
        } else {
            echo "No files found for the specified user and folder.<br>";
        }
    }
    // Optionally, update the file name in the database
    private function updateFileNameInDB($oldName, $newName, $id) {
        global $data;
        // $name = 
    
        // Update the file name in the database if it's stored there
        $sql = "UPDATE data SET files = REPLACE(files, ?, ?) WHERE user = ? AND disk_name = '-'";
        $stmt = $data->prepare($sql);
        $stmt->bind_param("ssi", $oldName, $newName, $id);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            echo "Database updated successfully.";
        } else {
            echo "No update needed in the database.";
        }
    
        $stmt->close();
    }

public function renameFolder($disk, $folder, $new) {
        // Retrieve the user ID
        $id = GetID::getID();
        
        // Set the current and new folder paths
        $current = "../../dashboard/uploads/users/" . $id . "/" . $disk . "/" ;
        $newPath = "../../dashboard/uploads/users/" . $id . "/" . $new . "/";
    
        // Check if the current folder exists
        if (is_dir($current)) {
            // Rename the folder
            if (rename($current, $newPath)) {
                echo "Folder renamed successfully.";
                $this->renameFolderDb($id, $disk, $folder, $new);
            } else {
                echo "Failed to rename the folder.";
            }
        } else {
            echo "The folder does not exist.";
        }
        echo $current;
    }
    
    private function renameFolderDb($id, $disk, $folder, $new) {
        global $data;
        $newEncoded =  $new;
    
        $sql = "UPDATE data SET folder_name = ?, disk_name = ? WHERE folder_name = ? AND disk_name = ? AND user = ?";
        
        // Prepare the query
        $stmt = $data->prepare($sql);
        
        if ($stmt === false) {
            echo "Error preparing the statement.";
            return;
        }
    
        // Bind the parameters
        $stmt->bind_param("ssssi", $new, $newEncoded, $folder, $disk, $id);
        
        // Execute the query
        if ($stmt->execute()) {
            echo "Folder name updated in database.";
        } else {
            echo "Failed to update folder name in database: " . $stmt->error;
        }
        
        // Close the prepared statement
        $stmt->close();
    }
    public function removeFolder($folderName, $diskName) {
        // Retrieve the user ID
        $id = GetID::getID();
        // Construct the full folder path, including the folder name
        $folderPath = "../../dashboard/uploads/users/".$id."/".$folderName."/";

        echo "Folder Path: " . realpath($folderPath); // Show the absolute path for debugging
        
        // Check if the folder exists
        if (!is_dir($folderPath)) {
            echo $folderPath;
            echo "<br/>";
            exit("The folder does not exist.");
        }
    
        // Check if the folder is writable, and if not, attempt to change the permissions
        if (!is_writable($folderPath)) {
            echo "Folder is not writable, attempting to change permissions...<br>";
            if (chmod($folderPath, 0777)) {
                echo "Permissions successfully changed to 0777.<br>";
            } else {
                echo "Failed to change folder permissions. Please check your Apache user permissions.<br>";
                exit;
            }
        }
        
        // Recursive function to delete the folder contents (files and subdirectories)
        $this->deleteFolderContents($folderPath);
        
        // After deleting contents, try removing the folder itself
        if (rmdir($folderPath)) {
            echo "Folder and its contents have been successfully removed.<br>";
        } else {
            echo "Failed to remove the folder. Please check permissions.<br>";
        }
    
        // Now delete the folder record from the database
        $this->deleteFolderDB($folderName, $diskName, $id);
    }
    
    // Helper function to recursively delete folder contents (files and subdirectories)
    private function deleteFolderContents($folderPath) {
        $files = array_diff(scandir($folderPath), array('.', '..'));
        
        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                // Recursively delete subdirectories
                $this->deleteFolderContents($filePath);
                rmdir($filePath); // After deleting files inside, remove the subdirectory
            } else {
                // Delete file
                unlink($filePath);
            }
        }
    }
    
    // Function to delete folder record from the database
    private function deleteFolderDB($folderName, $diskName, $id) {
        global $data;
    
        // SQL query to delete the folder record from the database
        $sql = "DELETE FROM data WHERE user = ? AND disk_name = ? AND folder_name = ?";
        
        // Prepare the SQL statement
        $stmt = $data->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("iss", $id, $folderName, $diskName);
        
        // Execute the query
        if ($stmt->execute()) {
            echo "Folder record removed from the database.<br>";
        } else {
            echo "Error removing folder record from the database.<br>";
        }
    
        // Close the prepared statement
        $stmt->close();
    }
    
    // delete user profile 

    public function removeWholeFolder($id) {
        $folderPath = "../../dashboard/uploads/users/" . $id;
        // Check if the folder exists
        if (is_dir($folderPath)) {
            // Set the folder and its contents to be writable (chmod 0777 allows read, write, and execute permissions for everyone)
            $this->chmodRecursive($folderPath, 0777); // Change permissions for the folder and all files inside it
    
            // Recursively delete files inside the folder
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );  // get all files inside in the folder
    
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath()); // Delete file or folder
            }
    
            // After files are deleted, remove the directory itself
            rmdir($folderPath);
            echo "Folder and its contents were deleted successfully.";
        } else {
            echo "The folder does not exist.";
        }
        // Remove the user data from the database after the folder is deleted
        $this->deleteAllUserDataFromDatabase($id);
    }
    // Helper function to recursively change file/folder permissions
    private function chmodRecursive($dir, $mode) {
        if (is_dir($dir)) {
            // Change the permissions of the folder itself
            chmod($dir, $mode);
    
            // Loop through all files and directories inside the current directory
            $files = new DirectoryIterator($dir);
            foreach ($files as $fileinfo) {
                if ($fileinfo->isDot()) continue; // Skip '.' and '..'
    
                $filePath = $fileinfo->getRealPath();
    
                if ($fileinfo->isDir()) {
                    // Recursively change permissions for subdirectories
                    $this->chmodRecursive($filePath, $mode);
                } else {
                    // Change permissions for files
                    chmod($filePath, $mode);
                }
            }
        }
    }
    
  // Function to delete all user data associated with the user from the 'data' table
private function deleteAllUserDataFromDatabase($id) {
    global $data;

    // Prepare the SQL statement to delete records associated with the user from the 'data' table
    $sql = "DELETE FROM data WHERE user = ?";

    // Prepare the query
    $stmt = mysqli_prepare($data, $sql);
    if ($stmt) {
        // Bind the user ID as a parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "User data deleted successfully from the 'data' table.";
        } else {
            echo "Error deleting user data from the 'data' table.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the SQL query for deleting user data.";
    }
    $this->deleteProfileUserDataFromDatabase($id);
}

// Function to delete the user's profile data from the 'uzivatel' table
private function deleteProfileUserDataFromDatabase($id) {
    global $connection;

    // Prepare the SQL statement to delete the user's profile from the 'uzivatel' table
    $sql = "DELETE FROM uzivatel WHERE id = ?";

    // Prepare the query
    $stmt = mysqli_prepare($connection, $sql);
    if ($stmt) {
        // Bind the user ID as a parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "User profile deleted successfully from the 'uzivatel' table.";
        } else {
            echo "Error deleting user profile from the 'uzivatel' table.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the SQL query for deleting user profile.";
    }
}

public function updateProfile($id, $name, $email, $accType) {
    global $connection;

    // Use prepared statement to prevent SQL injection
    $selectAll = "SELECT jmeno, email FROM uzivatel WHERE id != ?";
    $stmtSelectAll = mysqli_prepare($connection, $selectAll);

    if (!$stmtSelectAll) {
        // error_log("Failed to prepare SELECT query");
        return false;
    }

    // Bind the id parameter to the query to prevent SQL injection
    mysqli_stmt_bind_param($stmtSelectAll, "i", $id);

    // Execute the SELECT query
    mysqli_stmt_execute($stmtSelectAll);
    mysqli_stmt_bind_result($stmtSelectAll, $dbName, $dbEmail);

    // Check if the name or email already exists
    while (mysqli_stmt_fetch($stmtSelectAll)) {
        // Decrypt and compare names and emails
        $decryptedName = $this->decrypt($dbName);
        $decryptedEmail = $this->decrypt($dbEmail);


        // Check for duplicate name or email
        if ($decryptedName == $name || $decryptedEmail == $email) {
            echo "exist";
            mysqli_stmt_close($stmtSelectAll); // Close the SELECT statement
          
        }
        else{
            echo "good";
        }
    }

    // Close the SELECT statement since we don't need it anymore
    mysqli_stmt_close($stmtSelectAll);

    // If name/email does not exist, proceed with the update
    // Set default team name based on account type
    $teamName = ($accType == "team") ? "default" : ".......";

    // Encrypt the name, email, and team name
    $encryptedName = $this->encrypt($name);
    $encryptedEmail = $this->encrypt($email);
    $encryptedTeamName = $this->encrypt($teamName);

    // Prepare the UPDATE SQL query
    $sql = "UPDATE uzivatel SET jmeno = ?, email = ?, account_type = ?, team = ? WHERE id = ?";

    // Prepare the statement
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        // Handle error if the prepare failed
        // error_log("Failed to prepare UPDATE query");
        return false;
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssi", $encryptedName, $encryptedEmail, $accType, $encryptedTeamName, $id);

    // Execute the query
    if ($stmt->execute()) {
        // If successful, set a cookie indicating success

       
        return true; // Successful update
    } else {
        
        // Log error and return false
        // error_log("Failed to execute query: " . $stmt->error);
    
        return false; // Error in the update
    }
}
 

    // Method to check if a user already exists based on email
    private function checkUser() {
        // Encrypt the email before checking
        $encryptedEmail = $this->encrypt($this->email);

        // Check if the encrypted email already exists in the database
        $stmt = $this->connection->prepare("SELECT * FROM uzivatel WHERE email = ?");
        if ($stmt === false) {
            die('Error preparing statement for checking user: ' . $this->connection->error); 
        }

        $stmt->bind_param("s", $encryptedEmail); // Use the encrypted email
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0; // Returns true if user exists, false otherwise
    }


    public function forgottenPassword() {
        //check an email
        // if exist generate code , save the code into session
        // send him an email with that code
        // after sent redirect user to page where he type the code
        // then redirect page where he change his passwod
        if ($this->checkUser()) {
            $code = $this->generateCode();
            $_SESSION["user-code"] = $code;
            echo $this->email;
            SendCode::sendCode($this->email, $code);
        } else {
            echo "wrong";  // Provide error message if user not found
        }

    }

    private function generateCode() {
        // Generate a random 6-digit code
        return rand(100000, 999999);
    }
    
    public function checkCode($code) {
        // Check if the code is stored in the session
        if (isset($_SESSION["user-code"])) {
            return $code == $_SESSION["user-code"];
        }
        return false;  // Return false if no code is found in the session
    }

    public function changePassword($newPassword) {
        global $connection;
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $email =  $this->encrypt($_SESSION["user-email"]);
        echo $email;
    
        // SQL query to update the password for the user
        $query = "UPDATE uzivatel SET heslo = ? WHERE email = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $hashedPassword, $email);
    
        if ($stmt->execute()) {
            // If the query executed successfully, echo a success message
            echo "Password updated successfully!";
            unset($_SESSION["change-password"]);
            unset($_SESSION["user-email"]);
            unset($_SESSION["reset"]);
            unset($_SESSION["reset-time"]);
        } else {
            // If there was an error with the query, echo an error message
            echo "Error updating password.";
        }
    }
    

    public function logout() {
        session_destroy();
    }


}


// Check if the 'createAccount' key exists in the POST request
if (isset($_POST["createAccount"])) {
    // Sanitize input data to prevent XSS and other issues
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Don't sanitize passwords since they will be hashed
    $accountType = $_POST['account'];
    $team = isset($_POST['team']) ? htmlspecialchars($_POST['team']) : null;

    // Create a new User object
    $user = new User($name, $email, $password, $accountType, $team, $connection);

    // Save the user to the database
    $response = $user->saveUser();

    // Return a JSON response to the client
    echo json_encode(['message' => $response]);
}


if(isset($_POST["logout"])){
// Create a new User object
$user = new User(null,null,null,null,null, $connection);
$user->logout();
}

if (isset($_POST["login"])) {
    // Sanitize input data to prevent XSS and other issues
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Don't sanitize passwords since they will be hashed
    $user = new User($username, null, $password, null, null, $connection);
    $user->login($username,$password); // Error here: the login method expects 2 arguments
}
if(isset($_POST["createFolder"])) {
    $folderName = $_POST["folderName"];
    $user = new User(null, null,null, null, null, $connection);
    $user->createFolder($folderName);

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    echo $fileName;
    // return;
    $user = new User(null, null,null, null, null, $connection);
    $user->uploadFile($fileName);
}

if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {
    $user = new User(null, null,null, null, null, $connection);
    $user->uploadFolderFiles();
}
if(isset($_POST["deleteFile"])) {
    $fileName = $_POST["fileName"];
    $user = new User(null, null,null, null, null, $connection);
    
    $user->deleteFile($fileName);
}
if(isset($_POST["renameFile"])) {
    $fileName = $_POST["fileName"];
    $newName = $_POST["newName"];
    $user = new User(null, null,null, null, null, $connection);
    $user->renameFile($fileName,$newName);
}
if(isset($_POST["renameFolder"])) {
    $diskName = $_POST["pathName"];
    $dbName = $_POST["dbName"];
    $newName = $_POST["newName"];
    $user = new User(null, null,null, null, null, $connection);
    $user->renameFolder($diskName,$dbName,$newName);
}
if(isset($_POST["removeFolder"])) {
    $diskName = $_POST["pathName"];
    $dbName = $_POST["dbName"];
    $user = new User(null, null,null, null, null, $connection);
    $user->removeFolder($diskName,$dbName);
}
if (isset($_POST["renameGroup"])) {
    $newName = $_POST["newName"];
    $fileName = $_POST["file"];
    $url = $_POST["url"];
    $currentName = isset($_POST["currentName"]) ? $_POST["currentName"] : null;  // Check if currentName is set

    if ($currentName === null) {
        // Handle the error if currentName is missing
        echo "Error: 'currentName' is missing from the request.";
        exit;
    }

    // Proceed with renaming the file
    $user = new User(null, null, null, null, null, $connection);
    $user->renameFile($currentName, $newName, $fileName, $url);
}


if(isset($_POST["deleteUserAdmin"])) {
    $id = $_POST["id"];
    $user = new User(null, null,null, null, null, $connection); 
    $user->removeWholeFolder($id);
}

if (isset($_POST["saveUserInfo"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $accountType = $_POST["accType"];
    if($accountType == "Osobní") $accountType = "invidual";
    else $accountType = "team";
    $user = new User(null, null,null, null, null, $connection); 
    $user->updateProfile($id,$name,$email,$accountType);
}

if (isset($_POST["forgotten"])) {
    $email = $_POST["email"];
    $user = new User(null, $email, null, null, null, $connection);
    $_SESSION["user-email"] = $email;
    $user->forgottenPassword($email);
}

if (isset($_POST["verifyCode"])) {
    $code = $_POST["code"];
    $user = new User(null, null,null, null, null, $connection); 
    if ($user->checkCode($code)) {
        $_SESSION["reset"] = true;
        $_SESSION["reset-time"] =  time();

        echo "valid";

    } else {
        echo "incorrect-code";  // Return specific error message
    }
}
if(isset($_POST["updatePassword"]) && isset($_POST["newPassword"])) {
    $newPassword = $_POST["newPassword"];
    $user = new User(null, null,null, null, null, $connection); 
    $user->changePassword($newPassword);
}
?>