<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it's not already started
}
?>
<link rel="stylesheet" href="../styles/dashboard.css">
<link rel="stylesheet" href="../styles/dashboard.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<div class="sidebar">
   <a class="link" id="link" href="../app"> <img src="../images/others/footer-logo.png" alt=""></a>
    <a class="links active" href="../app"><i class="ri-dashboard-line"></i> Přehled</a>
    <a class="links" id="upload-file"><i class="ri-upload-cloud-line"></i> Nahrát soubor</a>
    <a class="links" fx = "<?= $upload?>" id="create-folder-btn" href="?vytvor"><i class="ri-folder-add-line"></i> Vytvořit složku</a>
    <a class="links" href="./profile"><i class="ri-user-line"></i> Můj profil</a>
    <a class="links" id="upload-file" href="./pages/about" ><i class="ri-apps-line"></i>O aplikaci</a>
    <a id="logout" class="links" onclick = "logout()" href="#logout"><i class="ri-logout-box-line"></i> Odhlásit se</a>
</div>
<script src="../../JS/cloudApp/sidebar.js"></script>
<script>    
</script>

