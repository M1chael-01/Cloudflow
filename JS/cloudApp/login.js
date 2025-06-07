document.getElementById("login-form").addEventListener("submit", (e) => {
    e.preventDefault();  // Prevent the default form submission behavior

    const name = document.querySelector("#username").value;
    const password = document.querySelector("#password").value;

    // Check if both username and password are filled
    if (!name || !password) {
        alert("Please fill in both username and password.");
        return;  // Stop the form submission if any field is empty
    }

    // Proceed with AJAX request if both fields are filled
$.ajax({
    url: './backend/cloudApp/user.php',
    type: 'POST',
    data: {
        login: true,
        username: name,
        password: password
    },
    success: function(response) {
        console.log("Server Response:", response);

        // Check if the response contains 'Username found' and 'Incorrect password'
        if (response.includes("Username found")) {
            if (response.includes("Incorrect password")) {
                // Incorrect password, alert user and redirect
              
                // location.href = "./pages/info?incorrect";
                location.href = "?cloud-app-incorrect";
            } else {
                // Successful login, redirect to the dashboard
                location.href = "././dashboard/app";
            }
        } else {
            // Username not found, alert user and redirect
            location.href = "./pages/info?user_not_found";
            location.href = "?cloud-app-user-404";
        }
    },
    error: function(xhr, status, error) {
        // Handle errors that occur during the AJAX request
        console.error("Error:", error);
    }
});

});