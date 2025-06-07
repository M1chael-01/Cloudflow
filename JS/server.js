
// Form step tracking
let currentStep = 1;
const totalSteps = 3; // Total number of steps in the form

// Helper function to check if the email is in a valid format
function isValidEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

// Helper function to check if the phone number is in a valid format (basic example)
function isValidPhone(phone) {
    const phonePattern =/^\d{3}(\s?\d{2,3}){2,3}$|^\d{9}$/; // Basic phone number format (10-15 digits)
    return phonePattern.test(phone);
}

function isValidPascalCode(pascal_code) {
    const pascalCodePattern = /^\d+$/; // Only digits allowed (no spaces, no letters)
    return pascalCodePattern.test(pascal_code); // Returns true if valid, false otherwise
}


// Move to the next step in the form
function nextStep() {

    // Step 1: No validation for Step 1 (if needed, add it here)

    if(currentStep == 1)  {

    }

    else if (currentStep == 2) {
        // Check if all required fields are filled
        const { email, phone, first_name, last_name } = userOrder.billing;

        // Check if the email and phone are valid and if all required fields are filled
        if (first_name && last_name && email && phone) {
            if (!isValidEmail(email)) {
                alert("Please enter a valid email address.");
                return; // Stop moving to the next step if the email is invalid
            }
            if (!isValidPhone(phone)) {
                alert("Please enter a valid phone number.");
                return; // Stop moving to the next step if the phone number is invalid
            }
        } else {
            alert("Please fill in all required fields: First Name, Last Name, Email, and Phone.");
            return; // Stop moving to the next step if any required field is missing
        }
    }

    else if (currentStep == 3) {
        // Step 3: Check if delivery fields are filled and validate postal code
        const { city, pascal_code, street } = userOrder.delivery;

        if (city && street && pascal_code) {
            // Validate if the pascal_code only contains numbers and optional spaces
            if (!pascalCodePattern.test(pascal_code)) {
                alert("Please enter a valid postal code (only numbers and spaces).");
                return; // Stop moving to the next step if the postal code is invalid
            }
        } else {
            alert("Please fill in all delivery fields: City, Postal Code, and Street.");
            return; // Stop moving to the next step if validation fails
        }
    }

    // If all validations pass, proceed to the next step
    if (currentStep < totalSteps) {
        // Hide current step
        document.getElementById(`step-${currentStep}`).style.display = 'none';
        // Increment to the next step
        currentStep++;
        // Show the next step
        document.getElementById(`step-${currentStep}`).style.display = 'block';

        // Update the progress bar
        const progressPercentage = (currentStep / totalSteps) * 100;
        document.querySelectorAll('.progress-bar span').forEach(bar => {
            bar.style.width = progressPercentage + '%';
        });

        // Store the current step status in localStorage
        // localStorage.setItem("status", currentStep);
        localStorage.setItem("status-server", currentStep);
    }
}


// Prices for different storage sizes and service

let oneTB = 1000;
const prices = {
    '500GB': 1000,   
    '1TB': oneTB ,     
    '2TB': oneTB*2,     
    '4TB': oneTB*4,    
    '5TB': oneTB*5,    
    '6TB': oneTB*6,   
    '8TB': oneTB*8,   
    '10TB': oneTB*10,   
    'Nastavení serveru': 1500,  // Správa serveru
    'Ladění serveru': 1000       // Optimalizace výkonu
};

// User order data structure
let userOrder = {
    billing: {
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        note: null
    },
    services: {},
    delivery: {
        city: null,
        state: "Czech Republic",
        pascal_code: 0,
        street: null,
    },
};

// Function to update the price
function updatePrice() {
    let total = 0;

    // Add price for selected storage size
    const storageSize = userOrder.services.storage ? userOrder.services.storage.name : null;
    if (storageSize && prices[storageSize]) {
        total += prices[storageSize];
    }

    // Add price for any selected services
    for (let service in userOrder.services) {
        // Check if the service exists and has a price
        if (userOrder.services[service] && userOrder.services[service].price) {
            total += userOrder.services[service].price;
        }
    }

    // Update the total price on the page
    document.getElementById('total-price').innerText = total + ' Kč';
}


// Event listeners for form changes
document.querySelector("#storage-size").addEventListener("change", (e) => {
    if (e.target.value && e.target.value !== "own") {
        userOrder.services.storage = {
            name: `Uložiště-${e.target.value}`,  // Service name with size (e.g. storage-4TB)
            price: prices[e.target.value]  // Corresponding price for the storage size
        };
    } else if (e.target.value === "own") {
        let s = prompt("Zadejte velikost uložiště v TB");
        userOrder.services.storage = {
            name: `Uložiště-${s}TB`,  // Custom storage size
            price: prices[`Uložiště-${s}TB`] || oneTB*s  // Set custom price (0 if not found)
        };
    }
    updatePrice();  // Update the total price based on selected services
    updateUserOrder();  // Save to localStorage
});

document.querySelector("#managed-services").addEventListener("change", (e) => {
    if (e.target.checked) {
        userOrder.services['Nastavení serverů'] = {
            name: "Nastavení serverů",
            price: prices['Nastavení serverů']
        };
    } else {
        delete userOrder.services['Nastavení serverů'];
    }
    updatePrice();
    updateUserOrder();
});

document.querySelector("#tuning-services").addEventListener("change", (e) => {
    if (e.target.checked) {
        userOrder.services['Ladění serveru'] = {
            name: "Ladění serveru",
            price: prices['Ladění serveru']
        };
    } else {
        delete userOrder.services['Ladění serveru'];
    }
    updatePrice();
    updateUserOrder();
});

// Handle name split into first_name and last_name
document.querySelector("#full-name").addEventListener("change", (e) => {
    if (e.target.value && e.target.value !== "") {
        const nameParts = e.target.value.trim().split(" ");
        if (nameParts.length > 1) {
            userOrder.billing.first_name = nameParts[0];  // First name
            userOrder.billing.last_name = nameParts.slice(1).join(" ");  // Last name 
        } else {
            userOrder.billing.first_name = nameParts[0];  // If there's no space, treat the whole as first name
            userOrder.billing.last_name = "";  // Empty last name
        }
        updateUserOrder();
    }
});

document.querySelector(".contact-form #email").addEventListener("change", (e) => {
    userOrder.billing.email = e.target.value;
    localStorage.setItem("email" , e.target.value);
    updateUserOrder();
});

document.querySelector("#phone").addEventListener("change", (e) => {
    userOrder.billing.phone = e.target.value;
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

// Function to save the user order to localStorage
function updateUserOrder() {
    localStorage.setItem("userOrder", JSON.stringify(userOrder));
}

// When page loads, retrieve data from localStorage if exists and populate the form
document.addEventListener("DOMContentLoaded", () => {
    const savedOrder = localStorage.getItem("userOrder");
    if (savedOrder) {
        userOrder = JSON.parse(savedOrder);

        // Pre-fill the form with saved data
        document.querySelector("#full-name").value = userOrder.billing.first_name + " " + userOrder.billing.last_name || "";
        document.querySelector("#email").value = userOrder.billing.email || "";
        document.querySelector("#phone").value = userOrder.billing.phone || "";
        document.querySelector("#city").value = userOrder.delivery.city || "";
        document.querySelector("#postal-code").value = userOrder.delivery.pascal_code || "";
        document.querySelector("#street-name").value = userOrder.delivery.street || "";

        // Pre-fill services checkboxes
        if (userOrder.services['server-management']) {
            document.querySelector("#managed-services").checked = true;
        }
        if (userOrder.services['server-tuning']) {
            document.querySelector("#tuning-services").checked = true;
        }

        // Pre-fill the storage size
        document.querySelector("#storage-size").value = userOrder.services.storage ? userOrder.services.storage.name.replace("storage-", "") : "";
    }
});

// Show the correct form step based on saved status in localStorage
if (localStorage.getItem("status-server")) {
    // alert();
    let value = localStorage.getItem("status-server");

    document.querySelectorAll(".contact-form").forEach((form, id) => {
        if (id == value - 1) {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });
}

// Submit form and send the data to the server
document.querySelector("#formOrder").addEventListener("submit", (e) => {
    e.preventDefault();
    let email = "";
  

    let userOrder = JSON.parse(localStorage.getItem("userOrder"));

    if(localStorage.getItem("email")) {
        email = localStorage.getItem("email");
    }
 

    // Send userOrder to the server using AJAX
    $.ajax({
        type: "POST",
        url: "./pages/services/server.php",
        data: {
            email:email,
            sendReq: true,
            userOrder: JSON.stringify(userOrder) // Send userOrder as JSON string
        },
        success: function(response) {
            console.log(response);
            localStorage.removeItem("status-server")
            clean("userOrder");;

            //  location.reload();
           

        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + ": " + error);
            clean("userOrder");;
        }
    });
});


