<!-- Obrázky (původní odkazy na Freepik jsou zakomentovány) -->
<!-- <a href="https://www.freepik.com/free-photo/modern-data-center-providing-cloud-services-enabling-businesses-access-computing-resources-storage-demand-internet-server-room-infrastructure-3d-render-animation_56001615.htm#fromView=image_search&page=1&position=0&uuid=04a590cc-6f1a-4c4f-b85c-7bbee4033665">Obrázek od DC Studio na Freepik</a> -->
<!-- <a href="https://www.freepik.com/free-photo/modern-data-center-providing-cloud-services-enabling-businesses-access-computing-resources-storage-demand-internet-server-room-infrastructure-3d-render-animation_56001615.htm#fromView=image_search&page=1&position=0&uuid=a087d15f-8922-4481-b649-347c5bb2f69b">Obrázek od DC Studio na Freepik</a> -->
<!-- <a href="https://www.freepik.com/free-photo/medium-shot-people-learning-language_23370128.htm#fromView=image_search&page=1&position=0&uuid=31f2ec5b-20cd-490d-9bf1-ab637d9649b6">Obrázek od Freepik</a> -->
<!-- <a href="https://www.freepik.com/free-vector/abstract-waves-design_1967195.htm#fromView=image_search&page=1&position=0&uuid=ac44df7f-4d95-4ef8-aa80-2ae7cc0d795b">Obrázek od kjpargeter na Freepik</a> -->
 <!-- https://www.freepik.com/free-photo/young-beautiful-modern-woman-having-laptop-hand-isolated-white-surface_16491205.htm#from_view=detail_alsolike -->



<link rel="stylesheet" href="./styles/home/home.css">
<link rel="stylesheet" href="./styles/home/styles.css">
<?php
// Start output buffering at the very top to prevent "headers already sent" error
ob_start(); 
?>
<?php 
$title = "Cloudflow";

// Conditions for loading different CSS files based on parameters in the URL
if (!isset($_GET["uvod"]) || isset($_GET[""])) { ?>
    <link rel="stylesheet" href="./styles/others/footer.css">
<?php } ?>
<?php if (isset($_GET["uvod"])){ ?>
<link rel="stylesheet" href="./styles/others/inputLabel.css">
<?php } ?>

<?php if (isset($_GET["firma"])){ ?>
    <link rel="stylesheet" href="./styles/pages/company.css">
<?php } ?>
<?php if (isset($_GET["objednavka"])){ ?>
    <link rel="stylesheet" href="./styles/store/order.css">
<?php } ?>
<?php if (isset($_GET["bezpeci"])){ ?>
    <link rel="stylesheet" href="./styles/services/safety.css">
<?php } ?>

<?php if (isset($_GET["prihlaseni"]) || isset($_GET["admin-prihlaseni"]) || isset($_GET["reset"]) || isset($_GET["zmenaHesla"]) ){ ?>
    <link rel="stylesheet" href="./styles/app/login.css">
    <link rel="stylesheet" href="./styles/others/animation-form.css">
<?php } ?>

<?php if (isset($_GET["registrace"])){ ?>
    <link rel="stylesheet" href="./styles/app/createProfile.css">
    <link rel="stylesheet" href="./styles/others/animation-form.css">
<?php } ?>

<?php if (isset($_GET["obchod"])){ ?>
    <link rel="stylesheet" href="./styles/store/store.css">
<?php } ?>
<?php if (isset($_GET["doprava"]) && isset($_GET["platba"])){ ?>
    <link rel="stylesheet" href="./styles/store/shipping_payment.css">
<?php } ?>

<link rel="stylesheet" href="./styles/pages/reference.css">

<?php if (isset($_GET["kontakty"])){ ?>
    <link rel="stylesheet" href="./styles/pages/contacts.css">
    <link rel="stylesheet" href="./styles/others/animation-section.css">
<?php } ?>
<link rel="stylesheet" href="./styles/pages/services.css">

<?php if (isset($_GET["bezpecnost"])){ ?>
    <link rel="stylesheet" href="./styles/services/secured.css">
    <link rel="stylesheet" href="./styles/others/animation-section.css">
<?php } ?>

<?php if (isset($_GET["infrastuktura"])){ ?>
    <link rel="stylesheet" href="./styles/services/infrastructure.css">
    <link rel="stylesheet" href="./styles/others/animation-section.css">
<?php } ?>

<?php if (isset($_GET["proc"])){ ?>
    <link rel="stylesheet" href="./styles/others/whyCloudflow.css">
<?php } ?>
<?php if (isset($_GET["udaje"])){ ?>
<link rel="stylesheet" href="./styles/others/userDetailts.css">
<?php } ?>
<?php if (isset($_GET["detail"])){ ?>
    <link rel="stylesheet" href="./styles/store/checkout.css">
<?php } ?>

<?php if (isset($_GET["kurzy"])){ ?>
    <link rel="stylesheet" href="./styles/services/course.css">
    <link rel="stylesheet" href="./styles/others/animation-section.css">
<?php } ?>
<?php if (isset($_GET["server"])){ ?>
    <link rel="stylesheet" href="./styles/services/server_safety.css">
    <?php } ?>

<?php if (isset($_GET["zapomenuteHeslo"]) || isset($_GET["code"]) || isset($_GET["admin-zapomenuteHeslo"])){ ?>
    <link rel="stylesheet" href="./styles/app/forgottenPassword.css">
    <link rel="stylesheet" href="./styles/others/animation-form.css">
<?php } ?>

<?php if (isset($_GET["produkt"])){ ?>
    <link rel="stylesheet" href="./styles/store/buy.css">
<?php } ?>

<?php if (isset($_GET["faq"])){ ?>
    <link rel="stylesheet" href="./styles/others/faq.css">
<?php } ?>

<?php if (isset($_GET["aplikace"])){ ?>
    <link rel="stylesheet" href="./styles/pages/app.css">
    <link rel="stylesheet" href="./styles/others/animation-section.css">
<?php } ?>

<?php if (isset($_GET["dashboard"])){ ?>
    <link rel="stylesheet" href="./dashboard/styles/dashboard.css">
<?php } ?>
<?php if (isset($_GET["filter_min"])){ ?>
    <link rel="stylesheet" href="./styles/store/store.css">
<?php } ?>

<!-- Font Poppins z Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<!-- Ikony -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
<?php

// load a files
require "./pages/header.php";
require "./backend/visits.php";
require "./backend/redirectUser.php";
require "./backend/checkCookie.php";

Visits::createSessionIfNot();
RedirectUser::redirectUser();


// i dont see adminLogged function becuase
//Fatal error Cannot declare class AdminLogged, because the name is already in use in
// C:\Users\Acer\OneDrive\Plocha\programing_coding\PHP\WEB-FIRMA\WEB\backend\adminLogged.php  on line 8

// Dynamické načítání stránek na základě parametrů v URL
if (isset($_GET['firma'])) {$id = 1;require "./pages/company.php";$title = "O fimrě";return;}
elseif (isset($_GET['sluzby'])) {$id = 2;require "./pages/services.php";return;} 
elseif (isset($_GET['proc'])) {$id = 2;require "./pages/whyCloudflow.php";return;} 
elseif (isset($_GET['aplikace'])) {require "./pages/app.php";$id = 3;return;}
elseif (isset($_GET['obchod'])) {$id = 4;require "./pages/store.php";return;}
elseif (isset($_GET['reference'])) {$id = 5;require "./pages/reference.php";return;} 
elseif (isset($_GET['kontakty'])) {$id = 6;require "./pages/contacts.php";return;} 
elseif(isset($_GET["produkt"])){require "./pages/buy.php";return;}
elseif(isset($_GET["prihlaseni"])){require "./pages/app/login.php";return;}
elseif(isset($_GET["registrace"])){require "./pages/app/createProfile.php";return;}
elseif(isset($_GET["faq"])){require "./pages/faq.php";return;}
elseif(isset($_GET["bezpecnost"])){require "./pages/services/secured.php";return;}
elseif(isset($_GET["infrastuktura"])){require "./pages/services/infrastructure.php";return;}
elseif(isset($_GET["kurzy"])){require "./pages/services/courses.php";return;}
elseif(isset($_GET["zapomenuteHeslo"])){require "./pages/app/forgottenPassword.php";return;}
elseif(isset($_GET["objednavka"])){require "./pages/order.php";return;}
elseif(isset($_GET["doprava"]) && isset($_GET["platba"])){require "./pages/shipping_payment.php";return;}
elseif(isset($_GET["udaje"])){require "./pages/userDetailts.php";return;}
elseif(isset($_GET["detail"])){require "./pages/checkout.php";return;}
elseif(isset($_GET["info"]) ) {require "./pages/info.php";return;}
elseif(isset($_GET["bezpeci"]) ) {require "./pages/services/safety.php";return;}
elseif(isset($_GET["server"]) ) {require "./pages/services/server.php";return;}
elseif(isset($_GET["admin-prihlaseni"]) ) {require "./admin/login.php";return;}
elseif(isset($_GET["adminLogged"]) ) {require "./admin/pages/dashboard.php";return;}
elseif(isset($_GET["aplikace-admin"])) {require "./admin/pages/cloudApp.php";return;}
elseif(isset($_GET["faq-admin"])) {require "./admin/pages/faq.php";return;}
elseif(isset($_GET["produkty-admin"])) {require "./admin/pages/products.php";return;}
elseif(isset($_GET["objednavky-admin"])) {require "./admin/pages/orders.php";return;}
elseif(isset($_GET["statistiky-admin"])) {require "./admin/pages/graphs.php";return;}
elseif(isset($_GET["reklamace-admin"])) {require "./admin/pages/return.php";return;}
elseif(isset($_GET["editID"])) {require "./admin/pages/products/edit.php";return;}
elseif(isset($_GET["createID"])) {require "./admin/pages/products/insert.php";return;}
elseif(isset($_GET["reset"])) {require "./pages/app/reset.php";return;}
elseif(isset($_GET["code"])) {require "./pages/app/enterCode.php";return;}
elseif(isset($_GET["zmenaHesla"])) {require "./pages/app/changePassword.php";return;}
elseif(isset($_GET["cloud-app-incorrect"]) || isset($_GET["cloud-app-user-404"]) || isset($_GET["admin404"]) ||isset($_GET["adminHeslo"]) ) {require "./pages/info.php";return;}
elseif(isset($_GET["zadnyUzivatel"]) || isset($_GET["zadnaObjednavka"])) {require "./admin/pages/noUserNoOrder.php";return;}
elseif(isset($_GET["admin-zapomenuteHeslo"])) {require "./admin/pages/forgottenPassword.php";return;}
elseif(isset($_GET["zipError"])) {require "./pages/info.php";return;}
elseif(isset($_GET["email-send-true"]) || isset($_GET["zadostTrue"])
|| isset($_GET["userExist"]) || isset($_GET["resetFalse"])
|| isset($_GET["admin-heslo-false"]) || isset($_GET["objednavka-sluzba"])) {require "./pages/info.php";return;}
//filter(goods)
else if(isset($_GET["filter_min"])) {require "./pages/store.php";return;}
elseif (isset($_GET["dashboard"])) {require "./dashboard/app.php"; return; }
else{if(!isset($_GET["uvod"])) {echo "<script>location.href = '?uvod'</script>";}}

if(isset($_GET["uvod"]) && isset($_SESSION["admin-logged"])) {require "./admin/pages/dashboard.php";return;}


?>
<title><?php echo $title; ?></title>
<section class="home">
    <div class="overlay">
        <h2>Budoucnost cloudu, <span>bez hranic !</span></h2>
        <p>Budoucnost v cloudu je tu – bez hranic, s nekonečnými možnostmi, neomezenou silou a inovacemi.</p>
        <a href="?registrace"><button>Vyzkoušeje aplikace</button></a>
    </div>
    <img src="./images/others/home.png" alt="Background Image">
</section>

<div id = "cta">
    <h2>Požádejte o vlastní server</h2>
    <p>Získejte plnou kontrolu, neomezený výkon a flexibilitu, kterou potřebujete pro efektivní růst a úspěch vašeho podnikání.</p>
    <a href="?server"><button>Zažádat nyní</button></a>
</div>

<?php require "./pages/services.php";?>
<!-- gallery -->
<section class="images" id="gallery-section">
    <div>
        <h2>Měníme budoucnost</h2>
    </div>
    <div class="flex">
        <main class="image">
            <div class="image-container"> 
                <img src="./images/backstage/project.jpg" alt="Inovativní řešení" loading="lazy" width="600" height="400">
            </div>
            <h3>Práce na projektu</h3>
        </main>
        <main class="image">
            <div class="image-container">
                <img src="./images/backstage/idea.jpg" alt="Inovativní řešení" loading="lazy" width="600" height="400">
            </div>
            <h3>Inovativní řešení</h3>
        </main>
        <main class="image">
            <div class="image-container">
                <img src="./images/backstage/tech.jpg" alt="Inovativní řešení" loading="lazy" width="600" height="400">
            </div>
            <h3>Technologický pokrok</h3>
        </main>
    </div>
</section>


<?php
    require "./pages/cookie.php";
?>
<?php
//load a json file
$json_data = file_get_contents('./data/faq_data.json');
$faq_data = json_decode($json_data, true);
?>

<section id="faq">
    <div class="container">
        <h2>Často kladené otázky</h2>
        <!-- use foreach to loop through the FAQ data -->
        <?php foreach ($faq_data['faqs'] as $faq): ?>
            <div class="faq-item">
                <h3><?= $faq['question']; ?> <i class="<?= $faq['icon']; ?>"></i></h3>
                <p><?= $faq['answer']; ?></p>
                <a href="?faq=<?= $faq['id']; ?>"><button>Zjistit více</button></a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require "./pages/reference.php"; ?>

<!-- footer -->
<footer class="footer" id="footer">
    <div class="container">
        <!--logo -->
        <div class="logo">
            <img src="./images/others/footer-logo.png" alt="Logo společnosti">
        </div>
        <div class="row">
            <!-- section-company -->
            <div class="footer-col">
                <h4>O firmě</h4>
                <hr>
                <ul>
                    <li><a href="?firma">O nás</a></li>
                    <li><a href="#sluzby">Naše služby</a></li>
                    <li><a href="?kontakty">Kde nás najdete</a></li>
                    <li><a href="?aplikace">Naše aplikace</a></li>
                </ul>
            </div>
            <!-- section-help-->
            <div class="footer-col">
                <h4>Pomoc</h4>
                <hr>
                <ul>
                    <li><a href="#faq">Často kladené dotazy (FAQ)</a></li>
                    <li><a href="?kontakty">Kontaktujte nás</a></li>
                    <li><a href="./PDF/podminky.pdf" target="_blank">Podmínky užití</a></li>
                    <li><a href="?admin-prihlaseni">Správa webu</a></li>
                </ul>
            </div>
            <!-- section-shopping -->
            <div class="footer-col">
                <h4>Online obchod</h4>
                <hr>
                <ul>
                    <li><a href="?filter_min=&filter_max=&search=router&category=">Routery</a></li>
                    <li><a href="?filter_min=&filter_max=&search=switch&category=">Switch</a></li>
                    <li><a href="?filter_min=&filter_max=&search=usb&category=">USB-C</a></li>
                    <li><a href="?filter_min=&filter_max=&search=&category=Kabel">Kabely</a></li>
                </ul>
            </div>
            <!-- section-contact us -->
            <div class="footer-col" id="email">
                <h4>Kontaktujte nás</h4>
                <hr>
                <!-- form for send a message -->
                <form action="" method="POST">
                    <input id="email" type="email" name="email" placeholder="Váš e-mail" required>
                    <textarea id="msg"  name="query" placeholder="Zadejte váš dotaz" required></textarea><br>
                    <!--  -->
                    <div class="terms-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Souhlasím se <a href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
                    </div>
                    <button type="submit">Odeslat dotaz <i class="ri-send-plane-line"></i></button>
                </form>
            </div>
        </div>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/sendEmail.js"></script>

