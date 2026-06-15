<?php
// Načtení dat
$json = file_get_contents("profile.json");
$data = json_decode($json, true);

// Inicializace hlášek
$message = "";
$messageType = "";

// Pokud neexistuje pole interests, vytvoříme ho
if (!isset($data["interests"])) {
    $data["interests"] = [];
}

// Zpracování formuláře
if (isset($_POST["new_interest"])) {

    $newInterest = trim($_POST["new_interest"]);

    if (empty($newInterest)) {
        $message = "Pole nesmí být prázdné.";
        $messageType = "error";
    } else {

        // Kontrola duplicit (bez ohledu na velikost písmen)
        $lowerInterests = array_map("strtolower", $data["interests"]);

        if (in_array(strtolower($newInterest), $lowerInterests)) {
            $message = "Tento zájem už existuje.";
            $messageType = "error";
        } else {
            // Přidání nového zájmu
            $data["interests"][] = $newInterest;

            // Uložení zpět do JSON
            file_put_contents("profile.json", json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $message = "Zájem byl úspěšně přidán.";
            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 4.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1><?php echo htmlspecialchars($data["name"]); ?></h1>

<h2>Dovednosti</h2>
<ul>
    <?php foreach ($data["skills"] as $skill): ?>
        <li><?php echo htmlspecialchars($skill); ?></li>
    <?php endforeach; ?>
</ul>

<h2>Zájmy</h2>
<ul>
    <?php foreach ($data["interests"] as $interest): ?>
        <li><?php echo htmlspecialchars($interest); ?></li>
    <?php endforeach; ?>
</ul>

<!-- Hláška -->
<?php if (!empty($message)): ?>
    <p class="<?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<!-- Formulář -->
<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat zájem</button>
</form>

</body>
</html>
