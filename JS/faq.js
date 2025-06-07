addEventListener("DOMContentLoaded", () => {
    // Select element with class 'faq'
    const text = document.querySelector(".container .faq");

    // Make sure the element exists
    if (text) {
        // We get the text content
        const fullText = text.textContent.trim();
        
        // Find the length of the text and count the middle
        const textLength = fullText.length / 2;

        // Find the index of the last space before the middle of the text
        let splitIndex = fullText.lastIndexOf(" ", textLength);
        
        // If no space is found (which could be the case for short texts), set splitIndex to the middle
        if (splitIndex === -1) {
            splitIndex = textLength;
        }

        // Split the text into two parts
        const firstPart = fullText.substring(0, splitIndex).trim();
        const secondPart = fullText.substring(splitIndex).trim();

        // Create two new paragraphs for parts of the text
        const firstParagraph = document.createElement("p");
        firstParagraph.textContent = firstPart;
        firstParagraph.style.marginBottom = "20px";

        const secondParagraph = document.createElement("p");
        secondParagraph.textContent = secondPart;

        // Delete existing container content
        text.innerHTML = '';

        // Add new paragraphs to the container
        text.appendChild(firstParagraph);
        text.appendChild(secondParagraph);
    }
});
