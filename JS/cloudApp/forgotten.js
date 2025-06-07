
// i do twice the ajax cuz when i make it first time 
// it shows an error but the second req work yet

// C:\Users\Acer\OneDrive\Plocha\programing_coding\PHP\v5\Web\backend\cloudApp\user.php:1148
// Stack trace:
// #0 C:\Users\Acer\OneDrive\Plocha\programing_coding\PHP\v5\Web\backend\cloudApp\user.php(1295): User-&gt;forgottenPassword('tvrdikmichael@g...')
// #1 {main}
//   thrown in <b>C:\Users\Acer\OneDrive\Plocha\programing_coding\PHP\v5\Web\backend\cloudApp\user.php</b> on line <b>1148</b><br />

// Web/?zapomenuteHeslo:521 tvrdikmichael@gmail.comMessage has been sent

document.getElementById("send-form").addEventListener("submit", (e) => {
    e.preventDefault();  // Prevent the default form submission

    const email = document.querySelector("#username").value;
    const termsAccepted = document.querySelector("#terms").checked;

    // Check if email is not empty and terms are accepted
    if (!email) {
        alert("Please enter a valid email.");
        return;
    }

    if (!termsAccepted) {
        alert("You must agree to the terms and conditions.");
        return;
    }

    // Proceed with the first AJAX request to send the email for password reset
    $.ajax({
        url: './backend/cloudApp/user.php',
        type: 'POST',
        data: {
            forgotten: true,
            email: email
        },
        success: function(response) {
            console.log(response);

            // Check if the response does not contain an error message
            if (!response.includes("wrong")) {
                // If email was successfully sent, save the state in session storage
                sessionStorage.setItem("emailSent", "true");

                // Check if the user has already been prompted to enter a code
                if (!sessionStorage.getItem("codeEntered")) {
                    // Trigger the second identical AJAX request after the first one succeeds
                    $.ajax({
                        url: './backend/cloudApp/user.php',
                        type: 'POST',
                        data: {
                            forgotten: true,
                            email: email
                        },
                        success: function(secondResponse) {
                            console.log(secondResponse);
                            if (secondResponse.includes("sent")) {
                              location.href = "?code";
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error in second AJAX request:", error);
                            alert("There was an error processing the second request. Please try again later.");
                        }
                    });
                }
            } else {
                location.href = "?resetFalse";
                 // User not found
            }
        },
        error: function(xhr, status, error) {
            console.error("Error in first AJAX request:", error);
            alert("There was an error processing your request. Please try again later.");
        }
    });
});