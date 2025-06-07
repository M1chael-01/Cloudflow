  // Show the cart modal with animation
  function showMenu() {
    const modal = document.getElementById('cartModal');
    modal.classList.add('show');
    document.querySelector(".home .home-image img").style.display = "none";
}

// Hide the cart modal with animation
function hideMenu() {
    const modal = document.getElementById('cartModal');
    modal.classList.remove('show');
    document.querySelector(".home .home-image img").style.display = "block";
}

// Attach event listener to remove buttons
document.querySelectorAll(".remove-btn").forEach((item) => {
    item.addEventListener("click", function(event) {
        // Prevent form submission
        event.preventDefault();

        // Get the product key to remove from the form
        const productKey = item.closest('form').querySelector('input[name="product_key"]').value;

        // Send AJAX request to remove the product
        fetch('./backend/remove_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_key=${productKey}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the product from the DOM
                item.closest('li').remove();
                location.reload();
               
            } else {
                alert('Error removing product.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

function showNav() {
const ul = document.querySelector("header nav ul");
ul.style.display = "block";

}
