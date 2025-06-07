<?php 
require "./admin/pages/sitebar.php";

// Load the FAQ data from the JSON file
$faqFile = './data/faq_data.json'; // Path to your FAQ JSON file

// Read and decode the JSON data
$faqs = json_decode(file_get_contents($faqFile), true);

// Function to save the updated FAQ data to the JSON file
function saveFaqs($faqs) {
    global $faqFile;
    file_put_contents($faqFile, json_encode($faqs, JSON_PRETTY_PRINT));
}

// Ensure currentFaqId is defined and within the range of available FAQs
$currentFaqId = isset($_GET['faq_id']) ? (int)$_GET['faq_id'] : 0;
$totalFaqs = count($faqs['faqs']);

// If $currentFaqId is greater than the total number of FAQs, reset it to 0
if ($currentFaqId >= $totalFaqs) {
    $currentFaqId = 0;
}

// Get the current FAQ to edit
$currentFaq = $faqs['faqs'][$currentFaqId];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated FAQ data from the form
    foreach ($_POST['faq'] as $id => $faqData) {
        $faqs['faqs'][$id]['question'] = $faqData['question'];
        $faqs['faqs'][$id]['answer'] = $faqData['answer'];
        $faqs['faqs'][$id]['icon'] = $faqData['icon'];
        $faqs['faqs'][$id]['faq'] = $faqData['faq'];
    }
    
    // Save the updated FAQ data to the JSON file
    saveFaqs($faqs);

    // Redirect to reload the page
    header("Location: ?faq-admin&faq_id=" . $currentFaqId);    
    exit;
}

?>
<head>
    <title>FAQ</title>
    <link rel="stylesheet" href="./admin/styles/faq.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Úprava FAQ  - FAQ #<?php echo $currentFaqId + 1; ?></h2>

    <form method="POST" action="?faq-admin&faq_id=<?=$currentFaqId?>" class="faq-form">
        <div class="faq-entry">
            <div class="faq-header">
                <div class="d-flex align-items-center">
                    <h4>FAQ #<?php echo $currentFaqId + 1; ?></h4>
                </div>
                <div>
                    <!-- Next FAQ Button -->
                    <?php if ($currentFaqId < $totalFaqs - 1): ?>
                        <a href="?faq-admin&faq_id=<?php echo $currentFaqId + 1; ?>" class="btn btn-next btn-secondary">Další FAQ</a>
                    <?php else: ?>
                        <a href="?faq-admin&?faq_id=0" class="btn btn-next btn-secondary">Jít zpět</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="form-group flex-fill">
                    <label for="question<?php echo $currentFaqId; ?>">Otázka</label>
                    <input type="text" name="faq[<?php echo $currentFaqId; ?>][question]" id="question<?php echo $currentFaqId; ?>" class="form-control" value="<?php echo htmlspecialchars($currentFaq['question']); ?>" required>
                </div>

                <div class="form-group flex-fill">
                    <label for="icon<?php echo $currentFaqId; ?>">Ikona (e.g., ri-store-line)</label>
                    <input type="text" name="faq[<?php echo $currentFaqId; ?>][icon]" id="icon<?php echo $currentFaqId; ?>" class="form-control" value="<?php echo htmlspecialchars($currentFaq['icon']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="answer<?php echo $currentFaqId; ?>">Odpověd</label>
                <textarea name="faq[<?php echo $currentFaqId; ?>][answer]" id="answer<?php echo $currentFaqId; ?>" class="form-control" rows="4" required><?php echo htmlspecialchars($currentFaq['answer']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="faq<?php echo $currentFaqId; ?>">Detailní odpověd</label>
                <textarea name="faq[<?php echo $currentFaqId; ?>][faq]" id="faq<?php echo $currentFaqId; ?>" class="form-control" rows="4" required><?php echo htmlspecialchars($currentFaq['faq']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Uložit změny</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
