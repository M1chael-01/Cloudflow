let account = "jeden_uzivatel", team = "";

function toggleTeamName() {
    var accountType = document.getElementById("account-type").value;
    var teamNameContainer = document.getElementById("team-name-container");

    // Show or hide the team name input field based on account type
    if (accountType === "team") {
        teamNameContainer.style.display = "block"; // Show team name input
        account = "team";  // Set account type to 'team'
        team = document.querySelector("#team-name").value;
    } else {
        teamNameContainer.style.display = "none"; // Hide team name input
        account = "individual";  // Set account type to 'individual'
        team = "" // Clear team value if 'individual' account is selected
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleTeamName(); // Ensure the form starts with the correct state
});

document.getElementById("register-form").addEventListener("submit", function(e) {
    e.preventDefault();  // Prevent the default form submission behavior

    // Get form field values
    const name = document.querySelector("#name").value;
    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;
    let team = ''; // Default empty team

    // If account type is 'team', get the team name value
    if (account === "team") {
        team = document.querySelector("#team-name").value;
    }

    console.log(account);

    // Check if required fields are filled
    if (!name || !email || !password || (account === "team" && !team)) {
        alert("Prosím vyplňte všechny pole");
        return; 
    }

    // Collect form data into an object
    let user = {
        name: name,
        email: email,
        password: password,
        account: account,
        team: team,
        createAccount: true
    };

    $.ajax({
        url: './backend/cloudApp/user.php',  
        type: 'POST',
        data: user,  // Send the user data
        success: function(response) {
            console.log(response);

            if (response.includes("successfully registered")) {
                // On success, redirect to the dashboard
                location.href = "././dashboard/app";
            } else if (response.includes("already exists")) {
                // Handle the case where the user already exists
                location.href = "?userExist";
            } else {
                // Handle any other messages
                alert("Error");
            }

            // Optionally, reset the form after submission
            document.getElementById("register-form").reset();
        },
        error: function(xhr, status, error) {
            // Handle any errors that occur during the AJAX request
            console.error("Error:", error);
            alert("Error");
        }
    });
});