const titles = [
    "Objednávka Bezpečnostního Řešení",
    "Vyplňte kontaktní údaje",
    "Vaše doručovací adresa",
];

let i = 0;
let basePrice = 1000; 
let additionalPrice = 0;

// Initialize user order data structure
let userOrder = JSON.parse(localStorage.getItem('userOrder-safety')) || {
    billing: {
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        note: null,
    },
    services: {},
    delivery: {
        city: null,
        state: "Czech Republic",
        pascal_code: 0,
        street: null,
    },
};

// Prices of different services
const prices = {
    'firewall': 500,              
    'detection': 800,             
    'encryption': 600,            
    'privacy': 400,               
    'audit': 1200,                
    'backup': 350,                 
};

// Event listeners for user info
document.querySelector("#full-name").addEventListener("change", (e) => {
    const fullName = e.target.value.split(" ");
    userOrder.billing.first_name = fullName[0];
    userOrder.billing.last_name = fullName[1] || "";
    updateUserOrder();
});

document.querySelector("#step-2 #email").addEventListener("change", (e) => {  
    userOrder.billing.email = e.target.value;
    localStorage.setItem("user-email" ,e.target.value );
});

document.querySelector("#phone").addEventListener("change", (e) => {
    userOrder.billing.phone = e.target.value;
    updateUserOrder();
});

document.querySelector("#notes").addEventListener("change", (e) => {
    userOrder.billing.note = e.target.value;
    updateUserOrder();
});

document.querySelector("#city").addEventListener("change", (e) => {
    userOrder.delivery.city = e.target.value;
    updateUserOrder();
});

document.querySelector("#postal-code").addEventListener("change", (e) => {
    userOrder.delivery.pascal_code = e.target.value;
    updateUserOrder();
});

document.querySelector("#street-name").addEventListener("change", (e) => {
    userOrder.delivery.street = e.target.value;
    updateUserOrder();
});

// Function to update the user order and save it to localStorage
function updateUserOrder() {
    localStorage.setItem('userOrder-safety', JSON.stringify(userOrder));
    updatePrice();
}

function updatePrice() {
    additionalPrice = 0;
    userOrder.services = {}; // Reset services to add proper data

    // Retrieve selected security type
    let securityType = document.getElementById("security-type")?.value;
    if (securityType) {
        additionalPrice += prices[securityType]; // Add the selected security type price
        userOrder.services.securityType = {
            name: securityType,  // Set the name of the service
            price: prices[securityType],  // Set the price of the service
        }; // Store in userOrder
    }

    // Retrieve selected services
    if (document.getElementById("monitoring")?.checked) {
        additionalPrice += 200; // Monitoring service price
        userOrder.services.monitoring = {
            name: "monitoring",
            price: 200,
        };
    } else {
        userOrder.services.monitoring = false;
    }

    if (document.getElementById("penetration-testing")?.checked) {
        additionalPrice += 300; // Penetration testing service price
        userOrder.services.penetrationTesting = {
            name: "penetration-testing",
            price: 300,
        };
    } else {
        userOrder.services.penetrationTesting = false;
    }

    // Update total price display
    let totalPrice = basePrice + additionalPrice;
    document.getElementById("total-price").innerHTML = `Cena: ${totalPrice} CZK`;

    // Save the updated order to localStorage
    localStorage.setItem('userOrder-safety', JSON.stringify(userOrder));
}


// Form step tracking
let currentStep = 1;
const totalSteps = 3; // Total number of steps in the form

// Function to move to the next step and save current step data
function nextStep(step) {
    // Step 1 Validation
    if (step === 2) {
        let fullName = document.getElementById("full-name")?.value.trim().split(" ");
        let email = document.querySelector("#step-2 #email")?.value.trim();
        let phone = document.getElementById("phone")?.value.trim();

        // Validate Full Name
        if (!fullName || fullName.length < 2) {
            alert("Please enter both first and last name.");
            return;
        }
        
        // Validate Email
        if (!isValidEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        // Validate Phone Number
        if (!isValidPhone(phone)) {
            alert("Please enter a valid phone number.");
            return;
        }

        // Save data for Step 1 to userOrder object
        userOrder.billing.first_name = fullName[0];
        userOrder.billing.last_name = fullName[1] || "";
        userOrder.billing.email = email;
        userOrder.billing.phone = phone;
        userOrder.billing.note = document.getElementById("notes")?.value || "";
    }
    // Step 2 Validation
    else if (step === 3) {
        let city = document.getElementById("city")?.value.trim();
        let pascalCode = document.getElementById("postal-code")?.value.trim();
        let street = document.getElementById("street-name")?.value.trim();

        // Validate Delivery Fields
        if (!city || !street || !pascalCode) {
            alert("Please fill in all delivery fields: City, Street, and Postal Code.");
            return;
        }

        // Validate Pascal Code (Postal Code)
        if (!isValidPascalCode(pascalCode)) {
            alert("Please enter a valid postal code (only numbers).");
            return;
        }

        // Save data for Step 2 to userOrder object
        userOrder.delivery.city = city;
        userOrder.delivery.pascal_code = pascalCode;
        userOrder.delivery.street = street;
    }

    // Update the progress bar
    let progressBar = document.getElementById('progress-bar');
    let progress = (step / totalSteps) * 100; // Divide by total steps (3)
    progressBar.style.width = progress + '%';

    // Hide current step and show next step
    document.getElementById('step-' + step)?.classList.remove('active');
    document.getElementById('step-' + (step + 1))?.classList.add('active');
    
    // Update the localStorage on each step change
    localStorage.setItem('userOrder-safety', JSON.stringify(userOrder));

    // Update currentStep in localStorage
    localStorage.setItem("status", step + 1);
    
    // Move to next step
    currentStep++;
}

// Helper function to check if the email is in a valid format
function isValidEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

// Helper function to check if the phone number is in a valid format (basic example)
function isValidPhone(phone) {
    const phonePattern = /^\d{3}(\s?\d{2,3}){2,3}$|^\d{9}$/; // Basic phone number format (10-15 digits)
    return phonePattern.test(phone);
}

// Helper function to check if the postal code is in a valid format (only numbers)
function isValidPascalCode(pascal_code) {
    const pascalCodePattern = /^\d+$/; // Only digits allowed (no spaces, no letters)
    return pascalCodePattern.test(pascal_code); // Returns true if valid, false otherwise
}


// Initialize form fields with data from localStorage (if available)
function loadFormData() {
    if (userOrder.services.securityType) {
        document.getElementById("security-type").value = userOrder.services.securityType;
    }
    if (userOrder.services.monitoring) {
        document.getElementById("monitoring").checked = true;
    }
    if (userOrder.services.penetrationTesting) {
        document.getElementById("penetration-testing").checked = true;
    }

    // Fill in billing info if available
    if (userOrder.billing.first_name) {
        document.getElementById("full-name").value = userOrder.billing.first_name + " " + userOrder.billing.last_name;
    }
    if (userOrder.billing.email) {
        document.getElementById("email").value = userOrder.billing.email;
       
    }
    if (userOrder.billing.phone) {
        document.getElementById("phone").value = userOrder.billing.phone;
    }
    if (userOrder.billing.note) {
        document.getElementById("notes").value = userOrder.billing.note;
    }

    // Fill in delivery info if available
    if (userOrder.delivery.city) {
        document.getElementById("city").value = userOrder.delivery.city;
    }
    if (userOrder.delivery.pascal_code) {
        document.getElementById("postal-code").value = userOrder.delivery.pascal_code;
    }
    if (userOrder.delivery.street) {
        document.getElementById("street-name").value = userOrder.delivery.street;
    }

    // Call updatePrice to reflect any saved prices
    updatePrice();
}

// Load data when the page is loaded
window.onload = function() {
    loadFormData();
};

// Listen to changes on the security type and services
document.querySelector("#security-type")?.addEventListener("change", updatePrice);
document.querySelector("#monitoring")?.addEventListener("change", updatePrice);
document.querySelector("#penetration-testing")?.addEventListener("change", updatePrice);

let isSubmitting = false;



// Show the correct form step based on saved status in localStorage
if (localStorage.getItem("status")) {
    let value = localStorage.getItem("status");

    document.querySelectorAll(".contact-form").forEach((form, id) => {
        if (id == value - 1) {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });
}





// Handle form submission on the last step
document.querySelector("#step-3").addEventListener("submit", (e) => {
    e.preventDefault();

    if(localStorage.getItem("user-email") && localStorage.getItem("userOrder-safety")) {
        let data = localStorage.getItem("userOrder-safety");
        let email = localStorage.getItem("user-email");
        let userOrder = JSON.parse(data);

        $.ajax({
            type: "POST",
            url:"./pages/services/safety.php",
            data: {
                email: email,
                sendReq: true,
                userOrder: JSON.stringify(userOrder) 
            },
            success: function(response) {
               
                clean("userOrder-safety");
            },  
            error: function(xhr, status, error) {
               
                clean("userOrder-safety");;
            }
        });
    }
});


onload = function() {
    document.querySelectorAll("select option")[0].selected = true;
}

if(localStorage.getItem("status")) {
    if(localStorage.getItem("status") == 3) {
        localStorage.removeItem("status");
    }
    else{
        let id = localStorage.getItem("status");

        if(id == 2 ) {
           let steps =  document.querySelectorAll(".step");
           steps.forEach((item,key) =>{
            // 2 = 1
                console.log(id);
                if(id == 2 ) {
                    
                    document.querySelector(".order-form").style.display = "block";
                    document.querySelector("#step-2").classList.add("active");
                    document.querySelector(".step").classList.remove("active");
                }
                else if(id == 3) {
                    location.reload();
                }
                else{
                    localStorage.removeItem("userOrder-safety");
                    localStorage.removeItem("user-email");
                    localStorage.setItem("status" , 1);
                    location.reload();

                }
           })
        }
    }
}

if(localStorage.getItem("status-server") && !localStorage.getItem("status")) {
    localStorage.setItem("status" , 1);
}