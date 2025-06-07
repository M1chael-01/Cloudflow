<style>
    #cta button{width: 190px;padding: 6.8px 7px 8px;position: relative;left: -10px;}
    #cta input{padding: 8px;width: 550px;font-size: 17px;}
    @media(max-width:650px)  {
    #cta input{
        width: 95%;
    }
}
</style>
<div id="cta">
    <h2>Máte nějaké dotazy?</h2>
    <p>Rádi vám pomůžeme! Zanechte nám svůj email a my se vám co nejdříve ozveme.</p>
    <form >
        <input type="email" name="email" id="email" placeholder="Zadejte svůj email" required><button type="submit">Odeslat</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.querySelector("form").addEventListener("submit" , (e) =>{
        e.preventDefault();

        let email =document.querySelector("form input").value;
        if(!email) return;

        $.ajax({
            type: "POST",
            url: "./backend/sendQuery.php",
            data: {query:true,email:email},
            success: function(data){
                if (data.includes("successfully")) {
                    location.href = "?zadostTrue";
              //  alert("Vaše žádost byla úspěšně odeslána.");
            } else {
                alert("Failed to send query. Please try again.");
            }
            }
            ,
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        })
    })
</script>