document.querySelector(".submit-btn").addEventListener("click", function (e) {
    e.preventDefault();  // Prevent the form from submitting
    const firstName = document.querySelector("input[name='user_first_name']").value;
    const lastName = document.querySelector("input[name='user_last_name']").value;
    const email = document.querySelector("input[name='user_email']").value;
    const phone = document.querySelector("input[name='user_phone']").value;
    const notes = document.querySelector("textarea[name='user_notes']").value;
    const termsAccepted = document.querySelector("input[name='terms']").checked;
    // Email validation regex
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; 

    // Helper function to check if the phone number is in a valid format (basic example)
    function isValidPhone(phone) {
        const phonePattern = /^\d{3}(\s?\d{2,3}){2,3}$|^\d{9}$/; // Basic phone number format (10-15 digits) 
        // valid formats:123456789, 123 456 789, 123 45 678 123, 123 456 789 123,123 45 678 123 456
        return phonePattern.test(phone);
    }

    // Validate required fields
    if (firstName && lastName && email && phone && termsAccepted) {
        // Validate email format
        if (!emailRegex.test(email)) {
            alert("Prosím zadejte platnou e-mailovou adresu.");
            return;  
        }

        if (!isValidPhone(phone)) {
            alert("Prosím zadejte platné telefonní číslo.");
            return;  
        }

        $.ajax({
            type: "POST",
            url: "./backend/submitUserDetails.php",  
            data: {
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone: phone,
                notes: notes
            },
            success: function (response) {
                const data = JSON.parse(response); 
                if (data.success) {
                    window.location.href = `?detail&datum=${new Date().toISOString()}`;  //YYYY-MM-DDTHH:mm:ss.sssZ,=2025-03-16T08:48:59.201Z
                } else {
                    alert(data.message);  
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                alert("Nastala chyba, zkuste to znovu");
            }
        });
    } else {
        alert("Prosím vyplňte všechna povinná pole.");
    }
});