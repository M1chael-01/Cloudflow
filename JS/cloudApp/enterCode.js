document.querySelector(".reset-code-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    const verificationCode = document.querySelector("#verification-code").value; 

    // Validate the verification code is not empty
    if (verificationCode === "") {
        alert("Please enter the verification code.");
        return;
    }
    $.ajax({
        type: "POST",
        url: "./pages/app/enterCode.php",  
        data: {
            reset: true,
            code: verificationCode
        },
        success: function (response) {
            if(response.includes("inccorect")) {
                alert("inccorect");
            }
            else{
                location.href = "?zmenaHesla";
            }
            console.log(response);
        },
        error: function () {           
            alert("An error occurred. Please try again later.");
        }
    });
});