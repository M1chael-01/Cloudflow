# README - Nastavení webového projektu

Tento návod vám pomůže nastavit a spustit webový projekt na vaší lokální stanici pomocí XAMPP a importovat databázové SQL skripty.

## Požadavky

- **XAMPP** (obsahuje Apache, MySQL, PHP)
- SQL skripty se nacházejí ve složce `importDB`

## Kroky

### Krok 1: Spusťte XAMPP server

1. **Otevřete XAMPP**: Spusťte aplikaci XAMPP.
2. **Spusťte Apache a MySQL**:
   - Klikněte na **Start** vedle Apache (pro webový server) a MySQL (pro databázový server).
   - Ujistěte se, že obě služby běží (v zeleném políčku by mělo být napsáno "Running").

### Krok 2: Přístup do databáze

1. Otevřete webový prohlížeč a přejděte na adresu:  
   `http://localhost/phpmyadmin/index.php`

### Krok 3: Vytvoření nových databází a import dat

1. Otevřete v prohlížeči adresu:  
   `http://localhost/phpmyadmin/index.php?route=/server/sql`
2. Vložte SQL kódy ze složky `importDB` do textového pole. **Každý SQL skript vkládejte jednotlivě** (nikoli všechny najednou).
3. Klikněte na tlačítko **Proveďte** nebo v anglické verzi na **Go**.

### Krok 4: Konfigurace webového projektu

1. Přejděte do adresáře, kde máte umístěn webový projekt.
2. Zkontrolujte, zda máte všechny potřebné soubory. To zjistíte tak, že otevřete složky a ověříte, že nejsou prázdné.

### Krok 5: Otestování webového projektu

1. Otevřete webový prohlížeč a přejděte na adresu:  
   `http://localhost/{nazev-projektu}`
2. Webová stránka by měla být nyní funkční. Pokud je vše správně nastaveno, uvidíte stránku webového projektu.

### Poznámka k aplikaci pro administrátora

1. Pokud chcete mít funkční administrátorskou aplikaci, je potřeba si stáhnout dodatečnou aplikaci navrženou pro Windows, která se nachází ve složce **maturitní práce**.
2. Tuto aplikaci si stáhněte a pokud máte spuštěnou nějakou aplikaci pro komunikaci s MySQL (nejčastěji XAMPP), ujistěte se, že je zapnutá.
3. Aplikace se postará o potřebné procesy pro správu administrátorského rozhraní.

---

&copy; **Tvrdík Michael 2025**, Všechna práva vyhrazena
