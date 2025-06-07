  // Handle form submission with AJAX
  document.getElementById("login-form").addEventListener("submit", (e) => {
    e.preventDefault();  // Prevent the default form submission (so the page won't reload)

    // Get the new password and repeat password values
    const newPassword = document.querySelector("#new-password").value;
    const repeatPassword = document.querySelector("#password").value;
    const termsAccepted = document.querySelector("#terms").checked;

    // Step 1: Validate the input fields
    if (!newPassword || !repeatPassword) {
        alert("Please enter both passwords.");
        return;  // Exit the function if either password is empty
    }

    if (newPassword !== repeatPassword) {
        alert("The passwords do not match.");
        return;  // Exit the function if the passwords don't match
    }

    if (!termsAccepted) {
        alert("You must agree to the terms and conditions.");
        return;  // Exit the function if terms are not accepted
    }

    // Step 2: Proceed with the AJAX request to change the password
    $.ajax({
        url: './backend/cloudApp/user.php',  
        type: 'POST',  // HTTP request type (POST)
        data: {
            resetPassword: true, 
            newPassword: newPassword 
        },
        success: function(response) {
            console.log(response);  

            if (response === "success") {
                alert("Password changed successfully.");
                window.location.href = "?login";  
            } else {
                alert("There was an error changing the password. Please try again later.");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);  // Log any errors from the server
            alert("There was an error processing your request. Please try again later.");  // Alert the user about the error
        }
    });
});
