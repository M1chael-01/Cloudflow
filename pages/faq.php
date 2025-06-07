<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>
<?php
// load a json files
$faqData = file_get_contents('./data/faq_data.json');
$faqArray = json_decode($faqData, true);

// get list of faqs
$faqs = $faqArray["faqs"];

$key = filter_input(INPUT_GET, "faq");

// If the key exists in the faqs list, retrieve the contents. Otherwise, use the default value.
$question = null;
foreach ($faqs as $faq) {
    if ($faq['id'] === $key) {
        $question = $faq;
        break;
    }
}

if (!$question) {
    // If the key does not exist, set the default value for $question
    $question = [
        "question" => "Otázka nenalezena",
        "answer" => "Omlouváme se, ale zadaná otázka neexistuje,zkontrolujte si URL adresu.",
        "icon" => "",
        "faq" => ""  // default value for faq
    ];
}

?>
<body>
    <section id="faq-detail" class="t">
        <div class="container">
            <h2><?php echo htmlspecialchars($question['question']); ?></h2>
            <div>
                <p class="faq"><?php echo (htmlspecialchars(isset($question['faq']) && $question['faq'] ? $question['faq'] : $question['answer'])); ?></p>
            </div>
            <a href="?uvod">
            <button onclick="window.location.href = '?uvod#faq'">Zpět na FAQ</button>
            </a>
            <a href="#footer">
               <a href="?kontakty"> <button>Mám jiný dotaz</button></a>
            </a>
        </div>
    </section>
    <?php  require "./pages/footer.php";  ?>
    
  <script src="./JS/faq.js"></script>