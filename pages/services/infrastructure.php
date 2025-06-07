<?php
require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<section class="bezpecnostni-reseni">
    <main>
        <h1 class="section-title">Infrastruktura</h1>
        <p class="section-description">
            Pomáháme s přechodem do cloudu a nastavíme infrastrukturu, která podpoří růst vaší firmy. Poskytujeme spolehlivá, bezpečná a škálovatelná řešení pro moderní podnikání.
        </p>

        <div class="service-cards">
            <div class="card">
                <i class="ri-cloud-line"></i>
                <h3>Přechod do cloudu</h3>
                <p>Efektivní migrace vašich systémů do cloudu pro lepší flexibilitu, dostupnost&nbsp;a optimalizaci nákladů&nbsp;a výkonu.</p>
            </div>

            <div class="card">
                <i class="ri-server-line"></i>
                <h3>Škálovanost</h3>
                <p>Infrastruktura, která se přizpůsobí růstu vašeho podnikání zaručující flexibilitu a optimalizaci bez ztráty výkonu.</p>
            </div>

            <div class="card">
                <i class="ri-shield-check-line"></i>
                <h3>Bezpečnost dat</h3>
                <p>Zajišťujeme vysokou úroveň ochrany dat&nbsp;a&nbsp;systémů proti kybernetickým hrozbám&nbsp;a&nbsp;jiným rizikům.</p>
            </div>

            <div class="card">
                <i class="ri-tools-line"></i>
                <h3>Maximální výkon</h3>
                <p>Optimalizace IT prostředí pro maximální efektivitu, stabilitu provozu&nbsp;a nové zlepšení&nbsp;a efektivní bezpečnost dat.</p>
    
            </div>
        </div>
        
        <div class="cta">
            <a id="mor" href="?kontakty" class="cta-button secondary">Zjistit více o infrastruktuře</a>
            <a id="mfore" href="?uvod" class="cta-button primary">Vrátit se zpět</a>
        </div>
        </main>

    </section>

    <?php require "./pages/footer.php" ?>;
