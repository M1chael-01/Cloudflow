
let account_type = "";
$(document).ready(function () {
// Handle Save button click
$('.save-btn').on('click', function () {
    let userId = $(this).data('id');
    let username = $('#username' + userId).val();
    let email = $('#email' + userId).val();
    accountType = $('#account_type' + userId).val();

    // Check if username and email are filled
    if (!username || !email) {
        alert("Please enter both name and email.");
        return;
    }

    // Validate email using a regular expression
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    // Send AJAX request to save user data
    $.ajax({
        type: 'POST',
        url: './backend/cloudApp/user.php',
        data: {
            saveUserInfo: true,
            id: userId,
            name: username,
            email: email,
            accType: accountType
        },
        success: function (response) {
            console.log(response);
            if(response.includes("exist")) {
                alert("takový uživatel již existuje");
            }
            else{
               location.reload(true);
            }
  
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ' + error);
            alert('Something went wrong. Please try again.');
        }
    });
});

// Handle Delete button click
$('.delete-btn').on('click', function () {
    let userId = $(this).data('id');
    let confirmDelete = confirm("Opravdu chcete smazat uživatele ?");

    if (confirmDelete) {
        $.ajax({
            type: 'POST',
            url: './backend/cloudApp/user.php',
            data: {
                deleteUserAdmin: true,
                id: userId
            },
            success: function (response) {
                location.href = "?aplikace-admin"; 
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + error);
                alert('Something went wrong. Please try again.');
            }
        });
    }
});
});

const selector = document.querySelector("select");
let ac = selector.getAttribute("found");
document.querySelectorAll("select option").forEach((item,id) =>{
        if(id == ac){
            item.selected = true;
        }
})

selector.addEventListener("change" , (e) =>{
   account_type = e.target.value;
})