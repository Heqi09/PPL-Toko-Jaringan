<?php
require_once __DIR__ . '/function.php';

$json = file_get_contents('php://input');
$notification = json_decode($json, true);

if ($notification) {
    $transaction_status = $notification['transaction_status'] ?? '';
    $order_id           = $notification['order_id'] ?? '';
    $fraud_status       = $notification['fraud_status'] ?? '';

    if ($transaction_status == 'capture' && $fraud_status == 'accept') {
        update_order_status($order_id, 'dibayar');
    } else if ($transaction_status == 'settlement') {
        update_order_status($order_id, 'dibayar');
    } else if (in_array($transaction_status, ['cancel', 'deny', 'expire'])) {
        update_order_status($order_id, 'batal');
    } else if ($transaction_status == 'pending') {
        update_order_status($order_id, 'pending');
    }

    echo "OK";
}
?>