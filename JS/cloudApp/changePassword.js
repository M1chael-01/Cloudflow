document.getElementById("send-form").addEventListener("submit", function(event) {
    // Prevent the form from submitting the traditional way
    event.preventDefault();

    // Get the values from the password fields
    let password = document.querySelector("#password").value,
        newPassword = document.querySelector("#new-password").value;

    // Check if both password fields have values
    if (password && newPassword) {
        if(password == newPassword) {
            // Send an AJAX request to update the password
            $.ajax({
                type: "POST",
                url: "./backend/cloudApp/user.php",  
                data: {
                    updatePassword: true,
                    password: password,
                    newPassword: newPassword
                },
                success: function(response) {
                    // Handle the response
                    console.log(response);
                    if(response.includes("Password updated")) {
                        alert("Heslo bylo změněno.");
                        location.href = "?prihlaseni"; 
                    } else {
                        alert("Něco se stalo: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.log(error);
                    alert("Došlo k chybě při změně hesla.");
                }
            });
        } else {
            alert("Hesla se neshodují.");
        }
    } else {
        alert("Prosím vyplňte obě pole.");
    }
});