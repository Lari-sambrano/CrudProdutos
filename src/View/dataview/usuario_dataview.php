<?php
include_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use Src\Controller\UsuarioCTRL;

header('Content-Type: application/json');
$ctrl = new UsuarioCTRL();

if (isset($_POST['btn_login'])) {
    $ret = $ctrl->LoginCTRL($_POST['email'], $_POST['senha']);
    echo json_encode(['status' => $ret]);
}

else if (isset($_POST['logout'])) {
    $ctrl->LogoutCTRL();
    echo json_encode(['status' => 1]);
}