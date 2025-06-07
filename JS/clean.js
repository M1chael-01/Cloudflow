function clean(name) {
    location.href = "?objednavka-sluzba";
    localStorage.removeItem("user-email");
    localStorage.removeItem(name);
    document.querySelector("#step-3").style.display = "none";
    document.querySelector("#step-1").style.display = "block";
   
}