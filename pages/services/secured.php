<style>
     
</style>
<?php
require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>
<main>
    <section class="bezpecnostni-reseni">
        <h1 class="section-title">Bezpečnostní řešení</h1>
        <p class="section-description">
    Poskytujeme bezpečnostní služby pro ochranu vašich dat před kybernetickými hrozbami. Naše řešení zahrnují šifrování, audity&nbsp;a compliance s GDPR pro maximální ochranu.
</p>

<div class="service-cards">
    <article class="card" aria-labelledby="encryption">
        <i class="ri-lock-2-line" aria-hidden="true"></i>
        <h3 id="encryption">Silné šifrování</h3>
        <p>Šifrujeme data při přenosu a ukládání, vždy abychom zajistili efektivně ochranu před neoprávněným přístupem.</p>

    </article>

    <article class="card" aria-labelledby="security-audits">
        <i class="ri-shield-check-line" aria-hidden="true"></i>
        <h3 id="security-audits">Audity</h3>
        <p>Provádíme pravidelné audity, aby byla bezpečnostní opatření aktuální a účinná proti novým hrozbám.</p>
    </article>
    <article class="card" aria-labelledby="privacy">
        <i class="ri-user-3-line" aria-hidden="true"></i>
        <h3 id="privacy">Ochrana soukromí</h3>
        <p>Splňujeme požadavky GDPR&nbsp;a chráníme vaše osobní údaje proti zneužití&nbsp;a neoprávněnému přístupu.</p>

    </article>

    <article class="card" aria-labelledby="monitoring">
        <i class="ri-eye-line" aria-hidden="true"></i>
        <h3 id="monitoring">Monitorování</h3>
      <p>Monitorujeme vaši infrastrukturu a okamžitě reagujeme na všechny potenciální hrozby v&nbsp;reálném čase.</p>

    </article>
</div>

        <div class="cta">
            <a href="#services-info" class="cta-button secondary" aria-label="Zjistit více informací o našich bezpečnostních řešeních">Zjistit více informací</a>
            <a id="mfore" href="?uvod" class="cta-button primary" aria-label="Vrátit se zpět na úvodní stránku">Vrátit se zpět</a>
        </div>
    </section>
</main>

<?php require "./pages/footer.php"; ?>
