<?php
require "./sidebar.php";
?>
<!-- Bootstrap and Custom CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ4IQ6ZJAfJt9aV4I7Xg+pkDFSfXb4szpHcLg9LRB5yyD7AcMPXURl4M1A8" crossorigin="anonymous">

<head>
    <title>O aplikaci</title>
    <link rel="stylesheet" href="../styles/about.css">
</head>
<main>
    <div class="container my-5">
        <!-- Section 1: What is our app? -->
        <section id="first-part">
            <h2 class="text-primary mb-3">Seznamte se s naší aplikací!</h2>
            <div class="card shadow-lg">
            <div class="card-body">
    <p>Naše aplikace je inovativní nástroj, který vám umožňuje efektivně spravovat vaše úkoly&nbsp;a&nbsp;projekty. Cílem aplikace je usnadnit organizaci každodenní práce, zvýšit produktivitu&nbsp;a&nbsp;zjednodušit spolupráci mezi týmy.</p>
</div>
            </div>
        </section>
            </div>
        </section>

        <!-- Section 3: Why choose our app? -->
        <section class="mt-4">
            <h3 class="text-primary mb-3">Proč zvolit naši aplikaci?</h3>
            <div class="card shadow-lg">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li id="li" class="list-group-item"><i class="ri-check-line"></i> Snadné použití a přístupnost z různých zařízení</li>
                        <li id="li" class="list-group-item"><i class="ri-check-line"></i> Podpora týmové spolupráce a komunikace</li>
                        <li id="li" class="list-group-item"><i class="ri-check-line"></i> Bezpečné uložení vašich dat v cloudu</li>
                        <li id="li" class="list-group-item"><i class="ri-check-line"></i> Možnost přizpůsobení aplikace vašim potřebám</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Section 4: Help & Support -->
        <section class="mt-4">
            <h3 class="text-primary mb-3">Potřebujete pomoc?</h3>
            <div class="card shadow-lg">
                <div class="card-body">
                    <p id="help">Pokud máte jakékoliv dotazy nebo potřebujete pomoc, neváhejte nás kontaktovat na e-mail: <a href="mailto:cloudflowinf@gmail.com" class="help">cloudflowinf@gmail.com</a>.</p>
                </div>
            </div>
        </section>
    </div>
</main>


<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybpy3pQb9FjivFATK9aOsQ0F6Rha2/50nFLwP0nJgHz5tp9O" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0g7vLF5c0U8R9X8H2z6J7jD/RrI5gZ1XGz47fJjRo6h0ST2p" crossorigin="anonymous"></script>

<script>
// Image source update for footer logo (optional)
let img = document.querySelector("img").src = "../../images/others/footer-logo.png";
// Function to delete profile (with confirmation)
function deleteProfile() {
    if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
        window.location.href = 'delete-profile.php';
    }
}
function change() {
    const link = document.querySelectorAll(".sidebar a");
    link.forEach((item,id) =>{
        if(item.textContent.includes("Vytvoř")){
            item.href = "../app?vytvor";

        }   
        else if(item.textContent.includes("soubor")){
            item.href = "../app?upload";

        }  
        else if(item.textContent.includes("aplikaci")) {
            item.classList.add("active");
            item.href = "./about";
        } 
        else if(item.textContent.includes("Odhlásit")) {
            item.href = "../../backend/cloudApp/logout.php"
        }
        else{
            item.classList.remove("active");
        }
    })
}
onload = change();
</script>
