<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();

    /**
     * créer un otken de session et le place dans un input hidden
     *
     * @param integer $time
     * @return void
     */
function setCSRF(int $time = 0): void{
    if($time<0)
    $_SESSION["tokenExpire"] = time() +60*$time;
    $_SESSION["token"] = bin2hex(random_bytes(50));
    echo '<input type="hidden" name="token" value="'.$_SESSION["token"].'">';
}


/**
 * On vérifie si le token est toujours valable
 *
 * @return boolean
 */
function isCSRFValid(): bool {
    if (!isset($_SESSION['tokenExpire']) || $_SESSION['tokenExpire'] > time()) {
        if (isset($_SESSION['token'], $_POST['token']) && $_SESSION['token'] == $_POST['token']) {
            return true;
        }
    }
    if (isset($_SERVER['SERVER_PROTOCOL'])) {
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
        return false;
    }
}

/**
 * va permettre de mettre au normes les entrées des utilisateurs
 *
 * @param string $data
 * @return string
 */
function cleanData(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
?>