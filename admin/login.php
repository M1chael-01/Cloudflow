 <?php
  require "./pages/routing.php";
  ?>
 <!-- Přihlašovací formulář -->
 <div class="login-container">
        <h2>Přihlášení do webu <i class="ri-login-circle-line"></i></h2>
        <form id = "login-form" action="#" method="POST">
            <!-- Uživatelské jméno -->
            <div class="form-group">
                <label for="username">Uživatelské jméno:</label>
                <input type="text" name="name" id="username" placeholder="Zadejte uživatelské jméno" required>
            </div>

            <!-- Heslo -->
            <div class="form-group">
                <label for="password">Heslo:</label>
                <input type="password" name="password" id="password" placeholder="Zadejte heslo" required>
            </div>
            <div class="terms-group" style="display: flex;">
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
            </div>
            <!-- Tlačítko pro přihlášení -->
            <div class="form-group">
                <button>Přihlásit se</button>
            </div>
            <!-- Odkazy pro zapomenuté heslo a vytvoření účtu -->
            <div class="form-links" style="text-align: center;">
                <a href="?admin-zapomenuteHeslo">Zapomněl jsem heslo</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php
        require "./pages/footer.php";
    ?>

    <script>
         document.getElementById("login-form").addEventListener("submit", (e) =>{
            e.preventDefault();

    const name = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;

    // Check if both username and password are filled
    if (!name || !password) {
        alert("Please fill in both username and password.");
        return;  // Stop the form submission if any field is empty
    }
    $.ajax({
        type: "POST",
        url: "./backend/administration/admin.php",
        data: {login:true, username: name, password: password},
        success: function(data) {
            console.log(data);
            // return;
            if (data.includes("Login successful!")) {
                location.href = "?adminLogged";
                // redirect him then him to routing than another routing 
            }
            else if(data.includes("Username not found")) {
                
                location.href = "?admin404";
            }
            else{
                location.href = "?adminHeslo";
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            alert("Nastala chyba");
        }
    })


         })
    </script>