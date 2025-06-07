

<?php
require "../../backend/isTeamAcc.php";       
require "../../database/cloudApp/users.php"; 
require "../../backend/getID.php";          
require "./sidebar.php";                     

// Establish database connection
$connection = users();  

// Get the logged-in user's ID
$id = GetID::getID();  // Assuming GetID::getID() returns the user's ID

// Function to fetch user information (name and email)
function userInfo($id, $connection) {
    $query = "SELECT jmeno, email FROM uzivatel WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);  // Bind the user ID to the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user exists
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        return $user;
    } else {
        return null;  // No user found
    }
}
$user = userInfo($id, $connection);

// If user info is available, populate the profile page
if ($user) {
    $GLOBALS["userName"] = decrypt($user['jmeno']);
    $GLOBALS["userEmail"] = decrypt($user['email']);;
} else {
    session_destroy();
    echo "<script>location.href = '../../?uvod';</script>";
}
  // AES Decryption (Decrypt sensitive data when needed)
 function decrypt($data) {
    $key = "p@assWW03rd_ke1";
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, substr(hash('sha256', $key), 0, 16));
}
?>
<head>
    <title>Můj profil</title>
    <link rel="stylesheet" href="../styles/profile.css">
</head>


<section class="profile">
    <main class="content">
        <div class="container">
            <div class="profile-container">
                <h2 id="<?= $id ?>">Váš osobní profil <i class="ri-user-line"></i></h2>
               
                <div class="profile-info">
                    <input type="text" name="user-name" id="user-name"  value="<?= $GLOBALS["userName"]?>" required>
                    <input type="email" name="user-email" id="user-email" value="<?= $GLOBALS["userEmail"]?>" required>
                   
                </div>
                <div class="action-buttons">
                    <button class="delete-profile" onclick="deleteProfile()">Smazat profil</button>
                    <button class="save-info" onclick="saveProfile()">Uložit změny</button>
                </div>
            </div>
            <div class="additional-section">
                <div class="additional-links"> 
                    <a href="mailto:cloudflowinf@gmail.com" class="help"> <span>❓</span> Pomoc & Podpora</a> 
                    <a href="../../PDF/zpracovaniUdaju.pdf" class="privacy"><span>🔒</span> Ochrana osobních údajů</a>
                    <a href="../../PDF/podminky.pdf" class="help"><span>📚</span> Podmínky užití</a>   
                </div>
              
            </div>
        </div>
    </main>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../JS/cloudApp/profile.js"></script>

<script>
    let img = document.querySelector("img").src = "../../images/others/footer-logo.png";
  

    function change() {
        const link = document.querySelectorAll(".sidebar a");
        link.forEach((item, id) => {
            if (item.textContent.includes("profil")) {
                item.classList.add("active");
            } else if (item.textContent.includes("Vytvoř")) {
                item.href = "../app?vytvor";
            } else if (item.textContent.includes("soubor")) {
                item.href = "../app?upload";
            }
            else if(item.textContent.includes("aplikaci")) {
                item.href = "./about";
            }          
               else {
                item.classList.remove("active");
            }
        });
    }
    change();
</script>