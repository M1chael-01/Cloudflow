<?php
require "./admin/pages/sitebar.php";
require "./database/cloudApp/users.php";

$connection = users();


function decryptData($data){
    $encryptionKey = 'p@assWW03rd_ke1';
    return openssl_decrypt($data, 'aes-256-cbc', $encryptionKey, 0, substr(hash('sha256', $encryptionKey), 0, 16));
}
$GLOBALS["count"] = 0;
function selectIDs() {
    global $connection;
    $sql = "SELECT id FROM uzivatel"; // Query to get all user IDs
    $result = mysqli_query($connection, $sql); // Execute the query
    
    $userIDs = []; // Initialize an empty array to store IDs
    
    // Loop through the result set and fetch each user ID
    while ($row = mysqli_fetch_assoc($result)) {
        $GLOBALS["count"]++;
        $userIDs[] = $row['id']; // Add each user ID to the array
    }
    
    return $userIDs; // Return the array of IDs
}
$userIDs = selectIDs(); // Fetch all user IDs

if(empty($userIDs)) {
    echo "<script>window.location.href = '?zadnyUzivatel';</script>";
   return;
}

function selectUsers($id) {
    global $connection;
    $sql = "SELECT id, jmeno, email, account_type, team FROM uzivatel WHERE id = $id"; // Fetch user by ID
    $result = mysqli_query($connection, $sql); // Execute query
    return $result;
}

// Get the current user ID from the GET request or default to the first user
$currentUserId = isset($_GET['user_id']) ? $_GET['user_id'] : $userIDs[0];
$users = selectUsers($currentUserId); // Fetch the current user's data
$total =$GLOBALS["count"]; // Get the total number of users

// Handle "Next" button click
if (isset($_POST['next'])) {
    // Find the next user ID in the array
    $currentKey = array_search($currentUserId, $userIDs);
    $_SESSION["foundUser"] = $currentUserId;
    $nextUserId = $userIDs[($currentKey + 1) % count($userIDs)]; // Wrap around if the last user
    header("Location: ?aplikace-admin&user_id=$nextUserId");
    exit;
}

if (isset($_POST['hledat'])) {
    $searchQuery = $_POST['search_query'];
    $_SESSION["foundUser"] = $searchQuery;
    $filteredUsers = filterUsersByQuery($searchQuery); // Example function
}
?>
<head>
    <title>CLoudová aplikace</title>
    <link rel="stylesheet" href="admin/styles/cloudApp.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet"> <!-- Remix icons -->
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Seznam uživatelů aplikace</h2>

    <form class="faq-form">
        <?php
        // Check if we have users
        if ($users && $total > 0) {
            $counter = 1; 
            while ($user = mysqli_fetch_assoc($users)) {
                ?>
                <div class="faq-entry">
                    <div class="faq-header">
                        <div class="d-flex align-items-center">
                            <h4>Uživatel #<?php echo $counter; ?> / <?php echo $total; ?></h4>
                        </div>
                    </div>
                   
                    <div class="input-group mb-3">
                        <div class="form-group flex-fill">
                            <div>
                                <span>
                                    <i id="foundUserID" name="<?= $user['id'] ?>"></i>
                                </span>
                            </div>
                            <label for="question<?php echo $user['id']; ?>">Jméno</label>
                            <input id="username<?php echo $user['id']; ?>" type="text" name="faq[<?php echo $user['id']; ?>][question]" id="question<?php echo $user['id']; ?>" class="form-control" value="<?php echo htmlspecialchars(decryptData($user['jmeno'])); ?>" required>
                        </div>

                        <div class="form-group flex-fill">
                            <label for="icon<?php echo $user['id']; ?>">E-mail</label>
                            <input id="email<?php echo $user['id']; ?>" type="email" name="faq[<?php echo $user['id']; ?>][icon]" id="icon<?php echo $user['id']; ?>" class="form-control" value="<?php echo htmlspecialchars(decryptData($user['email'])); ?>" required>
                        </div>
                        <?php
                        ($user["account_type"] == "invidual") ? $ac = "0" : $ac = "1";
                        ?>
                 <div class="form-group flex-fill">
                            <label for="account_type<?php echo $user['id']; ?>">Typ účtu</label>
                            <select found = "<?= $ac?>"  name="faq[<?php echo $user['id']; ?>][account_type]" id="account_type<?php echo $user['id']; ?>" class="form-control">
                                <option value="Osobní" <?php echo ($user['account_type'] == 'Osobní') ? 'selected' : ''; ?>>Osobní</option>
                                <option value="Týmový" <?php echo ($user['account_type'] == 'Týmový') ? 'selected' : ''; ?>>Týmový</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <div class="form-group flex-fill">
                            <button id="delete" type="button" class="btn btn-danger delete-btn" data-id="<?php echo $user['id']; ?>">Smazat</button>
                        </div>
                        <div class="form-group flex-fill">
                            <button type="button" class="btn btn-primary save-btn" data-id="<?php echo $user['id']; ?>">Uložit</button>
                        </div>
                    </div>
                </div>
                <?php
                $counter++;
            }
        } else {
            echo '<p>No users found.</p>';
        }
        ?>
        <div class="count">
            <?php for ($i = 0; $i < $total; $i++) : ?>
                <div class="users-dot">
                    <a href="?aplikace-admin&user_id=<?= $userIDs[$i] ?>" class="user-navigation-link">
                        <i class="ri-circle-line"></i>
                    </a>
                </div>
            <?php endfor; ?>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="./JS/admin/cloudApp.js"></script>

</body>
</html>