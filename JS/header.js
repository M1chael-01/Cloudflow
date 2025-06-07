// Get the current page query parameter
const currentPage = window.location.search;
const urlParams = new URLSearchParams(currentPage);
const targetNavLink = document.querySelectorAll("header nav a")[3];

let removedId = -1; // Initialize to -1 to handle cases where "Úvod" is not found

// Iterate through all navigation links
document.querySelectorAll("header nav a").forEach((item, id) => {
    // Get the href of the link and check if it matches the current query parameter
    const linkParam = item.getAttribute('href').substring(1);

    // Debugging: Check each link and query parameter
    console.log(`Checking link: ${linkParam} against URL: ${currentPage}`);

    // Check if it's the "Úvod" link
    if (item.textContent.trim() === "Úvod") {
        removedId = id; // Store the index of the "Úvod" link
        item.parentElement.remove(); // Remove the entire <li> containing "Úvod"
    }
    

    // Add "active" class to the current matching link
    if (currentPage.includes(linkParam)) {
        item.classList.add("active");
    } else {
        item.classList.remove("active");
    }
});

// Special handling for specific query parameters
if (urlParams.has("bezpecnost") || urlParams.has("infrastuktura") || urlParams.has("kurzy")) {
    // Assuming the 3rd nav link should be highlighted for these parameters
    if (targetNavLink) {
        targetNavLink.classList.add("active");
    }
}



if (urlParams.has("produkt")) {
    document.querySelectorAll("header nav a")[5].classList.add("active");
}

function showUl() {
    // Get the nav menu and the menu icon
    let navMenu = document.querySelector('header nav ul');
    let icon = document.querySelector("#menu i"); // Ensure the correct icon is targeted
    
    // Toggle the 'show-menu' class to display the menu
    navMenu.classList.toggle('show-menu');

    // Check if the icon currently has the "ri-menu-line" class
    if (icon.classList.contains("ri-menu-line")) {
        // If it does, replace it with "ri-close-line"
        icon.classList.replace("ri-menu-line", "ri-close-line");
    } else {
        // If it's not, change it back to "ri-menu-line"
        icon.classList.replace("ri-close-line", "ri-menu-line");
    }
}



// this file set active class for nav links based on the current page