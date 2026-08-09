<?php
session_start();

require_once '../config/db.php';

$product_id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN category c ON p.category_id = c.id
    WHERE p.id = ?
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title><?= htmlspecialchars($product['name']); ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.product-image{
    height:500px;
    object-fit:cover;
    width:100%;
    border-radius:15px;
}
</style>

</head>

<body>

<div class="container py-5">

    <div class="row">

        <!-- IMAGE -->
        <div class="col-md-6">

            <img
                src="../uploads/products/<?= htmlspecialchars($product['image']); ?>"
                class="product-image"
            >

        </div>

        <!-- DETAILS -->
        <div class="col-md-6">

            <small class="text-muted">
                <?= htmlspecialchars($product['category_name']); ?>
            </small>

            <h2 class="fw-bold mt-2">
                <?= htmlspecialchars($product['name']); ?>
            </h2>

            <h3 class="text-primary my-3">
                Rs. <?= number_format($product['price'],2); ?>
            </h3>

            <p>
                <?= nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <?php if($product['qty'] > 0): ?>

                <span class="badge bg-success mb-3">
                    In Stock
                </span>

            <?php else: ?>

                <span class="badge bg-danger mb-3">
                    Out Of Stock
                </span>

            <?php endif; ?>

            <div class="d-flex gap-2 mt-4">

                <button
                    class="btn btn-dark add-to-cart-btn"
                    data-product-id="<?= $product['id']; ?>"
                >
                    Add To Cart
                </button>

                <button
                    class="btn btn-outline-danger btn-wishlist"
                    data-product-id="<?= $product['id']; ?>"
                >
                    <i class="far fa-heart"></i>
                </button>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/cart.js"></script>
<script src="../assets/js/wishlist.js"></script>

</body>
</html>