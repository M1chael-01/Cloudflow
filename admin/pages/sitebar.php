<link rel="stylesheet" href="./admin/styles/dashboard.css">
<?php
require "./backend/adminLogged.php";

if(!AdminLogged::AdminLogged()) { echo "<script>location.href = '?uvod'</script>";}

$current = null;
if(isset($_GET["produkty-admin"]) || isset($_GET["editID"]) || isset($_GET["createID"])) { $current = 1;}
else if(isset($_GET["objednavky-admin"])) { $current = 2;}
else if(isset($_GET["statistiky-admin"])) {$current = 3;}
else if(isset($_GET["reklamace-admin"])) {$current = 4;}
else if(isset($_GET["aplikace-admin"])) {$current = 5;}
else if(isset($_GET["faq-admin"])) {$current = 6;}
?>
<div class="sidebar" current = "<?=$current?>">
    <a id="logo" href="?adminLogged"><img src="./images/others/footer-logo.png" alt=""></a>
    <a href="?produkty-admin"><span>📦</span> Produkty</a>
    <a href="?objednavky-admin"><span>📑</span> Objednávky</a>
    <a href="?statistiky-admin"><span>📊</span>  Statistiky</a>
    <a href="?reklamace-admin"><span>⚠️</span> Reklamace</a>
    <a href="?aplikace-admin"><span>📱</span>Cloud aplikace</a>
    <a href="?faq-admin"><span>ℹ️</span> FAQ </a>
    <a style="color:#000000" id="logout" onclick="logout()">Odhlašení</a>
</div>
<!-- Hamburger icon for mobile -->
<div class="hamburger" onclick="toggleSidebar()">
<i class="ri-menu-line"></i>
</div>
<script>
let id = document.querySelector(".sidebar").getAttribute("current");
if(id != "") {
    document.querySelectorAll(".sidebar a").forEach((i,key) => {
        if(id == key) i.classList.add("current");
    });
}

function logout() {
   location.href = "./backend/administration/logout.php?true";
}

function toggleSidebar() {
    const sidebar = document.querySelector(".sidebar");
    sidebar.classList.toggle("active"); // Toggle active class to show/hide sidebar

    try{
        const container1 = document.querySelector(".container");
        container1.classList.toggle("hide");

    }   
    catch{

    }

}
</script>
