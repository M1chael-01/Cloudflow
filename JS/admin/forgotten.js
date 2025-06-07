document.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();
    let token = document.getElementById("token").value;
    if(token) {
        $.ajax({
            type: "POST",
            url: "./backend/administration/admin.php",
            data: {resetAcc:true,token: token},
            success: function(data){
                console.log(data);
                
                // The server now returns valid JSON, so no need for JSON.parse if data is already parsed as JSON
                const response = data;  

                // Check if the token is valid and the password was generated
                if (data.includes("true")) {
                  
                    $.ajax({
                        type:"POST",
                        url: "./backend/administration/admin.php",
                        data:{getCookie:true},
                        success: function(data){
                          alert("Vaše nové heslo je:" + data);
                          alert("Heslo bylo aktulizováno");
                          console.log(data);
                       location.href = "?admin-prihlaseni";
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    })
                } else {
                    location.href = "?admin-heslo-false";
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
        })
    }
})