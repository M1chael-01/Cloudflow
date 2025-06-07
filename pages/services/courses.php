<?php
require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<section class="bezpecnostni-reseni">
    <main>
    <h1 class="section-title">Odborné kurzy</h1>
    <p class="section-description">
        Pořádáme odborné kurzy zaměřené na práci v cloudovém prostoru pro nové zákazníky. Naše školení vám pomohou zvládnout moderní cloudové technologie a zvýšit produktivitu.
    </p>
<div class="service-cards">
    <article class="card" aria-labelledby="cloud-intro">
        <i class="ri-book-open-line" aria-hidden="true"></i>
        <h3 id="cloud-intro">Úvod do cloudu</h3>
        <p>Seznamte se se základy cloudových technologií&nbsp;a jejich aplikací pro zefektivnění vašeho podnikání</p>
    </article>
    <article class="card" aria-labelledby="cloud-dev">
        <i class="ri-code-line" aria-hidden="true"></i>
        <h3 id="cloud-dev">Vývoj v cloudu</h3>
        <p>Naučte se navrhovat, vyvíjet a spravovat aplikace přímo v cloudovém prostředí pro optimální výkon.</p>
    </article>

    <article class="card" aria-labelledby="cloud-security">
        <i class="ri-git-repository-private-line" aria-hidden="true"></i>
        <h3 id="cloud-security">Bezpečnost</h3>
        <p>Kurzy zaměřené na ochranu dat a prevenci kybernetických hrozeb v cloudových prostředích.</p>
    </article>

    <article class="card" aria-labelledby="cloud-management">
        <i class="ri-settings-3-line" aria-hidden="true"></i>
        <h3 id="cloud-management">Správa cloudu</h3>
        <p>Osvojte si dovednosti potřebné pro efektivní správu a optimalizaci cloudové infrastruktury v různých podnicích.</p>
    </article>
</div>

    <div class="cta">
        <a href="?kontakty" class="cta-button" aria-label="Zjistit více o kurzu">Zjistit více o kurzu</a>
        <a href="?uvod" class="cta-button primary" aria-label="Vrátit se zpět na úvodní stránku">Vrátit se zpět</a>
    </div>
    </main>
</section>
<?php require "./pages/footer.php"; ?>