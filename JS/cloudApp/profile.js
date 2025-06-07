function deleteProfile(){
    let q = confirm("Opravdu chcece smazat svůj profil?");
    if(q) {

        let id = document.querySelector("h2").getAttribute("id");
        if(id) {
            $.ajax({
        url: '../../backend/cloudApp/user.php',  
        type: 'POST',
        data: {
            deleteUserAdmin:true,
            id: id,
        },
        success: function(response) {
            location.reload(true);
        },
        error: function(error) {
            alert("Error saving profile!");
            console.log(error);
        }
    });
        }
    }
}

function saveProfile() {
    let name = document.getElementById("user-name").value;
    let email = document.getElementById("user-email").value;
    let id = document.querySelector("h2").getAttribute("id");

    // Regular expression to check if email is valid
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Check if name and email are not empty and email is valid
    if (name && email && emailRegex.test(email)) {
        if (id) {
            $.ajax({
                url: '../../backend/cloudApp/user.php',  
                type: 'POST',
                data: {
                    saveUserInfo: true,
                    name: name,
                    email: email,
                    accType: "Osobní",
                    id: id
                },
                success: function(response) {
                    if(response.includes("exist")) {
                        alert("Uživatel již existuje");
                    }
                    else {
                        location.reload(true);
                    }
                    // Redirect or handle success
                },
                error: function(error) {
                    alert("Error saving profile!");
                    console.log(error);
                }
            });
        } else {
            alert("Zadejte jméno a e-mail");
        }
    } else {
        if (!emailRegex.test(email)) {
            alert("Zadejte platný e-mail");
        } else {
            alert("Zadejte jméno a e-mail");
        }
    }
}


function logout() {
   location.href = "../../backend/cloudApp/logout.php";
}