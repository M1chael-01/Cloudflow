<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<section id="about-us">
    <div class="container">
        <div class="about-content">
            <div class="section">
                <h3>Kdo jsme?</h3>
                <hr>
                <p>Jsme <strong>CloudFlow.sro</strong>, poskytujeme moderní cloudové služby pro snadnou správu dat a efektivní spolupráci.</p>
            </div>

            <div class="section">
                <h3>Naše cesta</h3>
                <hr>
                <p>Jsme fiktivní firma, která vznikla v roce 2024 jako součást maturitní práce. Tuto firmu představuje student čtvrtého ročníku, Tvrdík Michael.</p>
            </div>

            <div class="section">
                <h3>Čeho chceme dosáhnout?</h3>
                <hr>
                <p>Chceme být lídrem v oblasti cloudových řešení a přinášet klientům bezpečná a flexibilní řešení.</p>
            </div>

            <div class="section">
                <h3>Co nás řídí?</h3>
                <hr>
                <ul>
                    <li><strong>Bezpečnost</strong> – Ochrana dat našich klientů je pro nás důležitá.</li>
                    <li><strong>Inovace</strong> – Neustále hledáme nové způsoby, jak zlepšit naše služby.</li>
                </ul>
            </div>
            <div class="section">
                <h3>Proč si vybrat nás?</h3>
                <hr>
                <ul>
                    <li><strong>Špičková technologie</strong> – Využíváme nejnovější technologie pro zajištění spolehlivých a rychlých služeb.</li>
                    <li><strong>Individuální přístup</strong> – Každému klientovi poskytujeme řešení na míru.</li>
                </ul>
            </div>
            <div class="section">
                <h3>Naši lidé</h3>
                <hr>
                <p>Náš tým tvoří odborníci na cloudové technologie, kteří jsou odhodláni poskytovat vysoce kvalitní a velmi spolehlivé služby.</p>
            </div>
        
        </div>
    </div>
</section>
<?php require "./pages/footer.php"?>
