<?php

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require "../../database/administration/admin.php";  // Assuming this returns the DB connection

$connection = admins();  // Function to get DB connection

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

class Admin {
    public $username;
    private $password;
    private $connection;
    private static $encryptionKey = 'p@assWW03rd_ke1';  // Encryption key for sensitive data 

    public function __construct($username, $password, $connection) {
        $this->username = $username;
        $this->password = $password;
        $this->connection = $connection;
    }
    public function login($username, $password) {
        global $connection;
    
        // Query to select id, username, and password from the admin table
        $sql1 = "SELECT id, username, password FROM admin WHERE username = ?";
        $stmt1 = mysqli_prepare($connection, $sql1);
    
        if ($stmt1) {
            // Bind the username parameter to prevent SQL injection
            mysqli_stmt_bind_param($stmt1, "s", $username);
    
            // Execute the prepared statement
            mysqli_stmt_execute($stmt1);
    
            // Bind the result to variables
            mysqli_stmt_bind_result($stmt1, $id, $resultUsername, $resultPassword);
    
            // Loop through each row of the result
            while (mysqli_stmt_fetch($stmt1)) {
                // Compare the fetched username with the input username
                if ($resultUsername === $username) {
                    // If the username matches, verify the password (assuming hashed password)
                    if (password_verify($password, $resultPassword)) {
                        // Password is correct, login is successful
                        echo "Login successful!";
                        $this->rememberUser($id);
                        return true; 
                    } else {
                        // Incorrect password
                        echo "Incorrect password!";
                        
                        return false;
                    }
                }
            }
    
            // If no matching username found
            echo "Username not found!";
            return false;
    
            // Close the statement
            mysqli_stmt_close($stmt1);
        } else {
            // Handle the error if the statement preparation fails
            echo "Error preparing the query: " . mysqli_error($connection);
            return false;
        }
    }
    
    private function rememberUser($id) {
       
        $_SESSION["admin-logged"] = true;
        $_SESSION["admin-id"] = $id;
    }
    
    public function checkToken($token) {
        global $connection;
    
        // SQL query to fetch all tokens from the admin table
        $sql = "SELECT token,id FROM admin";  
    
        // Prepare the SQL query
        $stmt = $connection->prepare($sql);
    
        // Execute the query
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
    
        // Check if any tokens match using a while loop
        while ($row = $result->fetch_assoc()) {
            // Check if the token matches
            if (password_verify($token,$row["token"])) {
                $_SESSION["foundedID"] = $row["id"];
                return true;  // Token matched, return true
            }
        }
        return false;  // Token not found, return false
    }
    public function generatePassword($length = 8) {
        // Define allowed characters (numbers, lowercase, uppercase, special characters)
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomPassword = '';
    
        // Generate a cryptographically secure random password
        for ($i = 0; $i < $length; $i++) {
            // Use random_int for cryptographically secure random number generation
            $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomPassword; // Return the generated password
    }
    

    public function updatePassword($password) {
        global $connection;
    
        // Check if the session has a valid 'foundedID'
        if (isset($_SESSION["foundedID"])) {
            $id = $_SESSION["foundedID"];
            // Hash the new password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            // SQL query to update the password for the user
            $query = "UPDATE admin SET password = ? WHERE id = ?";
    
            // Prepare the SQL statement
            if ($stmt = $connection->prepare($query)) {
                // Bind the parameters (password and id)
                $stmt->bind_param("si", $hashedPassword, $id); // 'si' means string and integer
    
                // Execute the query
                if ($stmt->execute()) {
                    echo "Password updated successfully.";
                } else {
                    echo "Error updating password: " . $stmt->error;
                }
    
                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing statement: " . mysqli_error($connection);
            }
        } else {
            echo "User ID not found in session.";
        }
    }
}

// Handling form submission
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    // Create an instance of Admin and attempt login
    $admin = new Admin($username, $password, $connection);  // Pass the correct connection
    $admin->login($username,$password);  // Attempt login
}

// Handling form submission for resetting account
if (isset($_POST["resetAcc"])) {
    $token = $_POST["token"];
    $admin = new Admin(null, null, $connection);

    if ($admin->checkToken($token)) {
        // Generate a new password
        $newPassword = $admin->generatePassword();

        // Return valid JSON response
        echo json_encode([
            "status" => "true",
            "password" => $newPassword
        ]);
        
        // Set cookie for the new password (optional)
        setcookie('user_password_cookie', $newPassword, time() + 3600, '/');  

        // Update the password in the database
        $admin->updatePassword($newPassword);  

        echo  $_SESSION["foundedID"];
    } else {
        // Return a JSON response indicating failure
        echo json_encode([
            "status" => "false"
        ]);
    }
}
if(isset($_POST["getCookie"])) {
    echo $_COOKIE['user_password_cookie'];
    exit;
}