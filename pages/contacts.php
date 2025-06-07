<?php
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<head>
  <title>Kontakty</title>
</head>
<main>
  <section id="kontakty">
    <h2>Kontaktujte nás</h2>
    <p class="contact-info" style="color: #fff; width: 80%; margin: 0 auto; margin-bottom: 20px;">
      Máte otázky? Rádi vám pomůžeme! Zde jsou naše kontaktní údaje a mapa, kde nás najdete.
    </p>

    <!-- contact info -->
    <div class="contact-details">
      <!-- Blok pro adresu -->
      <div class="contact-item">
        <h3>Adresa</h3>
        <p>CloudFlow, s.r.o.<br>
           Ulice 123, 110 00 Praha, Česká republika</p>
      </div>

      <!-- phones numbers -->
      <div class="contact-item">
        <h3>Telefon</h3>
        <p>+420 123 456 789</p>
        <p>+420 423 506 789</p>
      </div>

      <!-- emails -->
      <div class="contact-item">
        <h3>Email</h3>
        <p><a href="mailto:info@cloudflow.cz">info@cloudflow.cz</a></p>
        <p><a href="mailto:tvrdikmichael@gmail.com">tvrdikmichael@gmail.com</a></p>
      </div>

      <!--working hours -->
      <div class="contact-item">
        <h3>Pracovní doba</h3>
        <p>Pondělí - Pátek: 9:00 - 18:00<br>
           Sobota - Neděle: Zavřeno</p>
      </div>
    </div>

    <div class="map-calendar-container">
      
      <div id="calendar" class="calendar-container"></div>

      <div id="mapa" class="map-container">
        <iframe 
          allowfullscreen="" 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2580.955515709097!2d15.276189977002263!3d49.69281052145739!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470c5847d11791b9%3A0xc41523f9b495b2b3!2zR3ltbsOheml1bSwgU3TFmWVkbsOtIG9kYm9ybsOhIMWha29sYSBhIFZ5xaHFocOtIG9kYm9ybsOhIMWha29sYSBMZWRlxI0gbmFkIFPDoXphdm91!5e0!3m2!1scs!2scz!4v1733312966642!5m2!1scs!2scz" 
          width="600" height="450" 
          style="border:0;" 
          allowfullscreen=""  
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>
</main>

<script src="./JS/calendar.js"></script>
