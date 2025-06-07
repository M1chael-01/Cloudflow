<?php
require "./admin/pages/sitebar.php";
?>
<head>
    <title>No Orders Found</title>
    <link rel="stylesheet" href="admin/styles/noOrder.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
<?php
    if(isset($_GET["zadnyUzivatel"])) {
        $GLOBALS["heading"] = "Žádní uživatelé registrováni";
        $GLOBALS["text"] = "V naší aplikaci zatím nejsou žádní registrovaní uživatelé. Zaregistrujte se, abyste mohli začít využívat všechny funkce.";
    }
    elseif(isset($_GET["zadnaObjednavka"])) {
        $GLOBALS["heading"] = "Žádné objednávky";
        $GLOBALS["text"] = "Vypadá to, že zatím nemáte žádné objednávky. Zkuste to prosím později.";
    }
    else{
        $GLOBALS["heading"] = "Vítejte na stránce!";
        $GLOBALS["text"] = "Zde můžete spravovat své objednávky a uživatele. Vyberte si jednu z možností v menu.";
    }
?>
        <div class="container">
        <h1><?php echo $GLOBALS["heading"]; ?></h1>
        <p><?php echo $GLOBALS["text"]; ?></p>
        <a href="?adminLogged"><button>Přejít na domovskou stránku</button></a>
      
    </div>

</body>
</html>
