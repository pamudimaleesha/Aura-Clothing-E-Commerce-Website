<?php
session_start();
include '../config/db.php';

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

// validate order id
if (!$order_id) {
    header("Location: /Auraclothing/index.php");
    exit;
}

try {

    $conn->beginTransaction();

    // 1. Verify order belongs to user
    $stmt = $conn->prepare("
        SELECT id, status 
        FROM orders 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("Invalid order");
    }

    // 2. Update order status to PAID (only if card payment)
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 'paid'
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);

    // 3. Clear cart
    $stmt = $conn->prepare("
        DELETE FROM cart 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);

    $conn->commit();

} catch (Exception $e) {
    $conn->rollBack();
    die("Something went wrong. Please try again.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-5 text-center">

        <h1 class="text-success">🎉 Order Placed Successfully!</h1>

        <p class="mt-3">
            Your payment has been completed successfully.
        </p>

        <h4 class="mt-3">
            Order ID: <span class="text-primary">#<?php echo htmlspecialchars($order_id); ?></span>
        </h4>

        <p class="mt-3">
            Thank you for shopping with Auraclothing ❤️
        </p>

        <a href="/Auraclothing/index.php" class="btn btn-primary mt-4">
            Continue Shopping
        </a>

    </div>

</div>

</body>
</html>