<?php
session_start();
include '../config/db.php';

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// check checkout session
if (!isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$data = $_SESSION['checkout_data'];

$total_amount = $data['total_amount'];

// POST data (safe)
$card_number = $_POST['card_number'] ?? '';
$card_name   = $_POST['card_name'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv         = $_POST['cvv'] ?? '';

// validation
if (empty($card_number) || empty($card_name) || empty($expiry_date) || empty($cvv)) {
    die("All fields are required!");
}

try {

    // 1. INSERT ORDER
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_amount, payment_method, status, created_at)
        VALUES (:user_id, :total_amount, 'card', 'paid', NOW())
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':total_amount' => $total_amount
    ]);

    $order_id = $conn->lastInsertId();

    // 2. INSERT PAYMENT (demo purpose only)
    $stmt2 = $conn->prepare("
        INSERT INTO payments (order_id, card_name, card_number, expiry_date, cvv, amount, created_at)
        VALUES (:order_id, :card_name, :card_number, :expiry_date, :cvv, :amount, NOW())
    ");

    $stmt2->execute([
        ':order_id' => $order_id,
        ':card_name' => $card_name,
        ':card_number' => $card_number,
        ':expiry_date' => $expiry_date,
        ':cvv' => $cvv,
        ':amount' => $total_amount
    ]);

    // 3. CLEAR CART / CHECKOUT SESSION
    unset($_SESSION['checkout_data']);

    // 4. REDIRECT TO SUCCESS PAGE
    header("Location: success.php?order_id=" . $order_id);
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>