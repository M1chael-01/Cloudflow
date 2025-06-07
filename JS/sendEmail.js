document.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();  // Prevent the default form submission

    let email = document.querySelector("form input#email").value;
    let msg = document.querySelector("form #msg ").value;


    $.ajax({
        type: 'POST', 
        url: './backend/sendEmail.php',  
        data: {
            sendEmail:true,
            email: email,
            message: msg
        },
        success: function(response) {
            console.log('Success:', response);
            location.href = "?email-send-true";
        },
        error: function(xhr, status, error) {
            // Handle error
            alert("Nastala chyba pri odeslani emailu");
        }
    });
});
