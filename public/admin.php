<?php
unset($SESSION);
session_start();
require_once '../model/database.php';

if (($_SERVER['REQUEST_METHOD'] === 'POST') && !empty($_POST)) {
    if (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token']) || ($_SESSION['csrf_token'] !== $_POST['csrf_token'])) {
        show_message("JETON CSRF NON VALIDE !", false);
    } else {
        $pwd = $_POST['pwd'];
        $pwdconfirm = $_POST['pwdconfirm'];
        if (!isset($pwd) || !isset($pwdconfirm)) {
            show_message("Mot de passe ou confirmation non renseigné(s).", true);
        } else {
            filter_form($pwd);
            filter_form($pwdconfirm);
            if($pwd===$pwdconfirm) {
                DataBaseTM24h::SetPwdTM24h($pwd);
                show_message("Mot de passe modifié avec succès.", false);
            } else {
                show_message("Mot de passe et confirmation différents, veuillez modifier SVP.", true);
            }
        }
    }
}

// To generate a new CSRF token.
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="fr, en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration activation TM24H</title>
    <link href="style.css" rel="stylesheet" type="text/css"/>
</head>
    <body>
        <h1>Administration activation TM24H</h1><br>
        <div><button class="button" onclick="resetBDTM24h();">Effacer base de données</button></div>
        <script>
            function resetBDTM24h() {
                var rep = confirm("Voulez-vous vraiment effacer tout le contenu de la base de donné ?\nCette opération est irréversible.");
                if(rep) {
                    <?php DataBaseTM24h::resetBDTM24h();?>;
                    alert("Données effacées avec succès.");
                }
            }
        </script><br>
        <div><form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class="form-activation" target="_top">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
            <label for="pwd" class="labelform">Mode passe à modifier : </label>
            <input type="text" name="pwd" id="pwd" class="input-form-admin" minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="Minimum 8 caractères incluant : 1 majuscule + 1 minuscule + 1 chiffre + 1 caractère special (#?!@$%^&*-)." autocomplete="off" required><br>
            <label for="pwdconfirm" class="labelform">Confirmation : </label>
            <input type="text" name="pwdconfirm" id="pwdconfirm" class="input-form-admin" minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="Minimum 8 caractères incluant : 1 majuscule + 1 minuscule + 1 chiffre + 1 caractère special (#?!@$%^&*-)." autocomplete="off" required>
            <input type="submit" class="button" value="Modifier"/>
        </form></div>
    </body>
</html>