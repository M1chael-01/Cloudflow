<?php  require "./pages/routing.php";?>
<div class="forgetten-container">
    <h2>Resetujte si heslo <i class="ri-refresh-line"></i></h2>
    <form id="send-form">
        <div class="form-group">
            <label for="username">Token:</label>
            <input type="text" id="token" placeholder="Zadejte token, který vám byl přidělen" required>
            <button type="submit">Odeslat</button>
        </div>

        <div class="terms-group" style="display: flex;">
            <input type="checkbox" id="terms" required>
            <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
        </div>
        
        <div class="form-links">
            <a href="?admin-prihlaseni" class="reset-password-link">Jít zpět</a>
        </div>
    </form>
</div>
<?php require "./pages/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/admin/forgotten.js"></script>