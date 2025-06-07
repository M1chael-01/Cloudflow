<!--odkaz na obrázky  -->
<!-- photobank =>  https://www.freepik.com/--> 
<!-- <a href="https://www.freepik.com/free-photo/modern-business-career-concept_9660642.htm#fromView=image_search&page=1&position=0&uuid=7a93ae5b-5c22-4406-a74a-55714f9560db">Image by wayhomestudio on Freepik</a> -->
<!-- <a href="https://www.freepik.com/free-photo/candid-beautiful-blonde-woman-with-wavy-hair_7136340.htm#fromView=image_search&page=1&position=0&uuid=36345f60-3f8c-45af-9f17-624266af312f">Image by Racool_studio on Freepik</a>-->
<!-- <a href="https://www.freepik.com/free-photo/isolated-picture-handsome-successful-young-male-entrepreneur-with-handlebar-mustache-goatee-beard-posing-studio-wearing-white-formal-shirt-looking-camera-with-confident-smile_11697455.htm#fromView=image_search&page=1&position=0&uuid=e252470d-1f37-448a-a51d-b9967c942a3f">Image by karlyukav on Freepik</a>  -->
<!-- <a href="https://www.freepik.com/free-photo/business-woman-black-jacket-looking-camera_3836328.htm#fromView=image_search&page=1&position=0&uuid=871b6ca8-353b-4bbc-9f82-a4a4f127a3bc">Image by freepik</a> -->

<?php
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<main>
<section id="reference-zakazniku">
    <h2>Co o nás řekli naši zákazníci ?</h2>
    <p style="color: #fff; width: 80%; margin: 0 auto; margin-bottom: 20px;" class="ref-info">
        Podívejte se na zkušenosti našich spokojených klientů a zjistěte, jak jsme jim pomohli s jejich projekty a růstem.
    </p>
    <div class="testimonials">
        <div class="testimonial">
            <img src="./images/feedback/business-career.jpg" class="testimonial-img" alt="Jan Novák">
            <p class="testimonial-text">
                "CloudFlow nám pomohl s migrací do cloudu, což výrazně zefektivnilo naši každodenní práci. Oceňujeme jejich profesionální přístup."
            </p>
            <p class="testimonial-author">- Jan Novák, CEO Tech Solutions</p>
        </div>
        <div class="testimonial">
            <img src="./images/feedback/blonde-woman.jpg" class="testimonial-img" alt="Petra Dvořáková">
            <p class="testimonial-text">
                "Díky bezpečnostním řešením od CloudFlow jsou naše data chráněna před hrozbami. Skvělý tým, který opravdu rozumí své práci."
            </p>
            <p class="testimonial-author">- Petra Dvořáková, IT Manažerka, DataSecure</p>
        </div>
        <div class="testimonial">
            <img src="./images/feedback/young-male.jpg" class="testimonial-img" alt="Tomáš Hlaváček">
            <p class="testimonial-text">
                "Kurzy od CloudFlow nám přinesly nové znalosti a zlepšily naše schopnosti v IT. Mohu je jen doporučit!"
            </p>
            <p class="testimonial-author">- Tomáš Hlaváček, Vedoucí oddělení, EduPro</p>
        </div>
        <div class="testimonial">
            <img src="./images/feedback/business-woman.jpg" class="testimonial-img" alt="Anna Krejčí">
            <p class="testimonial-text">
                "Perfektní spolupráce při vývoji aplikace na míru. Vše bylo dokončeno včas a v nejvyšší kvalitě."
            </p>
            <p class="testimonial-author">- Anna Krejčí, Produktová manažerka, StartupHub</p>
        </div>
    </div>
</section>
</main>