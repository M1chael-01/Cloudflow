 // Get the product images from the hidden div
 let images = JSON.parse(document.querySelector('.hidden').getAttribute('data-images'));

 // Initialize the index to 0 (start with the first image)
 let index = 0;

 // Get the maximum index (number of images in the array)
 let maxIndex = images.length - 1;

 // Function to go left (previous image)
 function goLeft() {
     if (index > 0) {
         index--; // Decrease index to show previous image
     } else {
         index = maxIndex; // Wrap around to last image
     }
     updateImage();
 }

 // Function to go right (next image)
 function goRight() {
     if (index < maxIndex) {
         index++; // Increase index to show next image
     } else {
         index = 0; // Wrap around to first image
     }
     updateImage();
 }

 // Function to update the displayed image
 function updateImage() {
     let imageElement = document.getElementById('productImage');
     let currentImage = images[index];  // Access image by its index
     imageElement.src = currentImage;  // Update the image source
 }

 // Initialize with the first image
 updateImage();

 // Open image in new tab when clicked
 document.querySelector(".product-image img").addEventListener("click", () => {
     const imageUrl = document.querySelector(".product-image img").src;
     window.open(imageUrl, "_blank");
 });