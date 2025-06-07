const consentOverlay = document.getElementById('cookie-consent-overlay');

setTimeout(() => {
    if (!localStorage.getItem('cookieConsent')) {
        consentOverlay.style.display = 'flex'; // Show the overlay if not consented
    }
}, 200);

function cookieAll() {
    let d1 = { 
        cookieData: new Date().toString(), 
        userAccepted: true, 
        all: true 
    };
    cookie(encodeData(d1));
}
    
async function cookie(info) {
    // Collect device information
    let screenResolution = window.screen.width + "x" + window.screen.height;
    let platform = navigator.platform;
    let userAgent = navigator.userAgent;
    let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone; 
    let country = navigator.language || "Unknown"; 
    let deviceType = /Mobi|Android/i.test(navigator.userAgent) ? "mobile" : "desktop";

    // Get IP address asynchronously
    const userIP = await getIP();


    // Send data to PHP using AJAX
    $.ajax({
        type: "POST",
        url: "./pages/cookie.php",  // Adjust the path as needed
        data: {
            send: true,
            cookie: true,
            userAgent: userAgent,
            screenResolution: screenResolution,
            platform: platform,
            info: info,
            timezone: timezone,
            country: country,
            userIP: userIP,
            deviceType:deviceType
        },
        success: function(response) {
            console.log(response);
            consentOverlay.style.display = 'none';
        },
        error: function(xhr, status, error) {
            console.error("Error in sending data:", error);
        }
    });
}

// Convert cookie data into base64 encoded string
function encodeData(cookieData) {
    let jsonString = JSON.stringify(cookieData);
    // Convert to a UTF-8 byte array and then base64 encode it
    let byteArray = new TextEncoder().encode(jsonString);
    let encodedString = btoa(String.fromCharCode(...byteArray));

    return encodedString;
}

async function getIP() {
    try {
        const response = await fetch('https://api.ipify.org?format=json');
        const data = await response.json();
        return data.ip;
    } catch (error) {
        console.error('Error fetching IP address:', error);
        return "Unknown"; // Return a default value in case of error
    }
}

