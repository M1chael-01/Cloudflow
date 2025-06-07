 let price = 0;
let picked = null;    // This will track the selected shipping option(it means if user choose a delivery company)
let deliveryComp = null;

document.querySelectorAll(".shipping-info").forEach((i, id) => {
    i.addEventListener("click", () => {
        if (picked !== null) {
            const prevInput = picked.querySelector("input.shipping");
            // Reset the value of the previously selected hidden input
            prevInput.value = '';  // Reset the value of the previously selected hidden input
            // Remove the 'current' class from the previously selected element
            picked.classList.remove("current");
        }
        // Get the hidden input associated with the clicked shipping option
        const selectedInput = i.querySelector("input.shipping");
        // Set the value of the hidden input to the id (or any unique identifier)
        selectedInput.value = id;
        // Store the current selected element as 'picked'
        picked = i;
        // Add the 'current' class to the selected element
        i.classList.add("current");
        
        // Set the shipping company based on the selected id
        switch(id) {
            case 0:deliveryComp = "Česká pošta";price = 100;break;
            case 1:deliveryComp = "PPL";price = 120;break;
            case 2:deliveryComp = "DPD";price = 130;break;
            case 3:deliveryComp = "GLS";price = 110;break;
            case 4:deliveryComp = "DHL";price = 150;break;            
        }
    });
});

let state = "Česká republika";
document.querySelector("#state").addEventListener("change" , (e) =>{
    state = e.target.value;
})

document.querySelector(".submit-btn").addEventListener("click" , () =>{
    const city = document.querySelector("#city").value,
    postal_code = document.querySelector("#postal_code").value,
    street = document.querySelector("#street").value;
   let  deliveryId = document.querySelector(".shipping-info.current");

    document.querySelectorAll(".shipping-method .shipping-info").forEach((item, index) => {
    if (item.classList.contains("current")) {
        deliveryId = index;
    }
});
    if(city.length>0 && postal_code.length>0 && street.length>0 && deliveryId !== null){
        // ajax data
        $.ajax({
            type: "POST",
            url: "./backend/submitDelivery.php",
            data:{
            deliveryId:deliveryId,
            city:city,
            state:state,
            postal_code:postal_code,
            street:street,
            deliveryComp:deliveryComp,
            price:price
            }
            ,
            success: function(response) {
                console.log("Response from server:", response);
            location.href = "?udaje";
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
                alert("Nastala chyba při odesílání formulářa");
            }
        })
    }
    else{
        alert("Prosím vyplňte všechna povinná pole.");
    }
})