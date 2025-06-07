// Getting current parameters from URL
const currentPage = window.location.search;
const urlParams = new URLSearchParams(currentPage);

// Get all links in navigation
const navLinks = document.querySelectorAll("header nav a");

// Browse all links in the navigation
navLinks.forEach((item) => {
    
    // Getting the href value (without leading character ?)
    const linkParam = item.getAttribute('href').substring(1);

    // Checks if the current URL contains this parameter
    if (currentPage.includes(linkParam)) {
        // If so, adds the class "active" to this link
        item.classList.add("active");
    } else {
        // If not, removes the "active" class
        item.classList.remove("active");
    }
    
});

// Special conditions for certain parameters in the URL
if (urlParams.has("bezpecnost") || urlParams.has("infrastuktura") || urlParams.has("kurzy")) {
    // If the URL contains parameters security, infrastructure or courses, highlight the 3rd link
    navLinks[3].classList.add("active");
}

// If the URL contains the parameters "login", "registration" or "forgottenpassword"
if (urlParams.has("prihlaseni") || urlParams.has("registrace") || urlParams.has("zapomenuteHeslo")) {
    // Highlight the login link (index 4)
    navLinks[4].classList.add("active");
}

// If the URL contains the "product" parameter
if (urlParams.has("produkt")) {
    // Highlight link to products (index 5)
    navLinks[5].classList.add("active");
}
