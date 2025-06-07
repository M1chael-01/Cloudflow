<?php
require_once "./backend/checkCookie.php";

require_once "./backend/adminLogged.php";
if(AdminLogged::AdminLogged())  {echo "<script>location.href = '?adminLogged'</script>";}

if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))
  {echo "<script>location.href = '?uvod'</script>";}
// ?>


<section class="bezpecnostni-reseni">
    <h1 class="section-title">Proč CloudFlow?</h1>
    <p class="section-description">
        CloudFlow je moderní IT společnost, která nabízí produkty a služby pro správu dat, síťové vybavení a kurzy. Naším cílem je usnadnit správu dat, zvýšit efektivitu a poskytnout skvělou podporu našim klientům.
    </p>
<div class="service-cards">
    <div class="card">
    <i class="ri-lock-line"></i>
        <h3>Bezpečnost</h3>
        <p>Ochrana dat je naší prioritou, využíváme šifrování a bezpečnostní audity. Vaše data jsou vždy v bezpečí.</p>
    </div>
    <div class="card">
        <i class="ri-user-community-line"></i>
        <h3>Škálovatelnost</h3>
        <p>Flexibilní řešení pro firmy i jednotlivce, přizpůsobitelné vašim potřebám. Můžete snadno přizpůsobit naše produkty.</p>
    </div>
    <div class="card">
    <i class="ri-safe-2-line"></i>
        <h3>Úspora nákladů</h3>
        <p>Optimalizujte procesy a šetřete čas i náklady s efektivními nástroji. Naše řešení vám pomohou dosáhnout vyšší efektivity.</p>
    </div>
    <div class="card">
        <i class="ri-device-line"></i>
        <h3>Kompatibilita</h3>
        <p>Naše produkty fungují na široké škále zařízení a systémů. Pracujte na jakémkoli zařízení bez omezení kompatibility.</p>
    </div>
</div>
    <div class="cta">
        <a href="#contact-form" class="cta-button">Kontaktujte nás</a>
        <a href="?info" class="cta-button">Zjistit více</a>
    </div>
</section>
<?php require "./pages/footer.php"; ?>
