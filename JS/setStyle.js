//this code adds an active class for the second element in the link header
document.querySelectorAll("header nav ul li a").forEach((item,id) =>{
    if(id == 2) item.classList.add("active");
    
})