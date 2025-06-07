<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<section id="cloud-app">
    <main>
        <h2>Cloudová aplikace</h2>
        <p class="app-description">
            Naše cloudová aplikace usnadňuje správu dat, podporuje spolupráci, zvyšuje produktivitu a poskytuje bezpečné prostředí pro všechny uživatele – od jednotlivců po velké týmy.
        </p>
        <div class="service-cards">
            <article class="service-card">
                <i class="ri-user-line" aria-hidden="true"></i> 
                <h3>Pro osobní účely</h3>
                <p>Ukládejte a spravujte svá data snadno a bezpečně. Naše aplikace vám nabízí intuitivní rozhraní a dostupnost odkudkoliv.</p>
                <a href="?prihlaseni">
                    <button class="btn-try" aria-label="Vyzkoušet aplikaci pro osobní účely">Vyzkoušet</button>
                </a>
            </article>
            <article class="service-card">
                <i class="ri-group-line" aria-hidden="true"></i> 
                <h3>Pro týmy</h3>
                <p>Podpořte spolupráci ve vašem týmu. Naše aplikace umožňuje sdílení souborů a efektivní správu projektů.</p>
                <a href="?prihlaseni">
                    <button class="btn-try" aria-label="Vyzkoušet aplikaci pro týmy">Vyzkoušet</button>
                </a>
            </article>
        </div> 

    </main>
</section>
<?php require "./pages/footer.php"; ?>
