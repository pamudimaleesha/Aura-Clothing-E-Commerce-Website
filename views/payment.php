<?php
session_start();

if (!isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php");
    exit;
}

$data = $_SESSION['checkout_data'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Card Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3>Card Payment</h3>

        <p>Total Amount:
            <strong>Rs. <?php echo number_format($data['total_amount'],2); ?></strong>
        </p>

        <form action="/Auraclothing/views/process_payment.php" method="POST">

            <div class="mb-3">
                <label>Card Number</label>
                <input type="text" name="card_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Card Holder Name</label>
                <input type="text" name="card_name" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>Expiry Date</label>
                    <input type="month" name="expiry_date" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>CVV</label>
                    <input type="password" name="cvv" class="form-control" maxlength="3" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">
                Pay Now
            </button>

        </form>
    </div>
</div>

</body>
</html>