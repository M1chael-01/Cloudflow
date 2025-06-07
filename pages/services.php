<?php
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<div id="sluzby"></div>
<section id="nas-sluzby">
    <main>
        <h2>Nabízíme</h2>
        <p style="color: #fff; width: 80%; margin: 0 auto; margin-bottom: 10px;" class="inf">
            Široké spektrum služeb zaměřených na zefektivnění vašich procesů a&nbsp;optimalizaci pracovního prostředí.
            Naše cloudové technologie jsou navrženy tak, aby pomohly vaší firmě růst a&nbsp;inovovat.
        </p>
        <div class="features">
            <div class="feature">
                <i class="ri-cloud-line"></i>
                <h3>Cloudová aplikace</h3>
                <p>Vyvíjíme na&nbsp;míru aplikace, které vám pomohou optimalizovat pracovní procesy a&nbsp;zlepšit efektivitu.</p>
                <a href="?aplikace"><button>Vyzkoušet</button></a>
            </div>
            <div class="feature">
                <i class="ri-git-repository-private-line"></i>
                <h3>Bezpečnostní řešení</h3>
                <p>Poskytujeme robustní bezpečnostní služby, které chrání vaše data před kybernetickými hrozbami.</p>
                <a href="?bezpecnost"><button>Zjistit více</button></a>
            </div>
            <div class="feature">
                <i class="ri-building-2-line"></i>
                <h3>Infrastruktura</h3>
                <p>Pomáháme s&nbsp;přechodem do&nbsp;cloudu a&nbsp;nastavíme infrastrukturu, která podpoří růst vaší firmy.</p>
                <a href="?infrastuktura"><button>Zjistit více</button></a>
            </div>
            <div class="feature">
                <i class="ri-expand-left-right-line"></i>
                <h3>Odborné kurzy</h3>
                <p>Pořádáme odborné kurzy zaměřené na&nbsp;práci v&nbsp;cloudovém prostoru pro&nbsp;nové zákazníky.</p>
                <a href="?kurzy"><button>Zjistit více</button></a>
            </div>
        </div>
    </main>
</section>
