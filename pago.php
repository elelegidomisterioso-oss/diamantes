<?php
require_once 'vendor/autoload.php';

// Render leerá tus llaves desde el panel de Environment
$access_token = getenv('MP_ACCESS_TOKEN');

if (!$access_token) {
    die("Error: Configura el MP_ACCESS_TOKEN en el panel de Render.");
}

MercadoPago\SDK::setAccessToken($access_token);

$player_id = $_POST['player_id'] ?? 'N/A';
$monto = $_POST['monto'] ?? 0;
$paquete = $_POST['paquete'] ?? 'Diamantes';

$preference = new MercadoPago\Preference();

// Crear el ítem
$item = new MercadoPago\Item();
$item->title = "Pack $paquete - ID: $player_id";
$item->quantity = 1;
$item->unit_price = (float)$monto;
$item->currency_id = "MXN";

$preference->items = array($item);

// Configurar solo OXXO (Ticket)
$preference->payment_methods = array(
    "included_payment_types" => array(array("id" => "ticket")),
    "installments" => 1
);

$preference->save();

// Regresamos al index con el ID de la compra para mostrar el botón
header("Location: index.php?preference_id=" . $preference->id . "&player_id=" . $player_id);
exit();
?>