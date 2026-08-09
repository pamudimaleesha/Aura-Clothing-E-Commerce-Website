<?php
session_start();
include './config/db.php';
include './config/helpers.php';

/* ---------------- PRODUCTS ---------------- */
$products = [];
$stmt = $conn->query('SELECT id, name, price, image FROM products ORDER BY id DESC LIMIT 8');

if ($stmt) {
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ---------------- FEEDBACK ---------------- */
$feedbacks = [];

$stmt = $conn->prepare('
    SELECT f.*, u.name AS user_name
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    WHERE f.status = "active"
    ORDER BY f.f_id DESC
    LIMIT 12
');

$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AuraClothing.lk - Fashion Store</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#111827;
    --secondary:#2563eb;
    --accent:#f59e0b;
    --light:#f9fafb;
    --dark:#111827;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:#f8fafc;
}

/* ================= NAVBAR ================= */

.navbar{
    background:#fff;
    padding:15px 0;
}

.navbar-brand{
    font-family:'Fredoka One',cursive;
    font-size:30px;
    color:var(--primary) !important;
}

.nav-link{
    font-weight:500;
    color:#111827 !important;
    margin-left:15px;
}

.nav-link:hover{
    color:var(--secondary) !important;
}

/* ================= HERO ================= */

.hero-section{
    height:90vh;

    background:
    linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url('./public/img/home/fashion-banner.jpg');

    background-size:cover;
    background-position:center;

    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;

    color:#fff;
}

.hero-section h1{
    font-size:65px;
    font-weight:700;
}

.hero-section h2{
    font-size:45px;
    color:#facc15;
}

.hero-section p{
    font-size:18px;
    margin-top:15px;
}

.hero-btn{
    background:var(--secondary);
    color:#fff;
    padding:14px 35px;
    border:none;
    border-radius:30px;
    font-weight:600;
    margin-top:20px;
    transition:.3s;
}

.hero-btn:hover{
    background:#1d4ed8;
}

/* ================= FEATURE ================= */

#feature{
    padding:60px 0;
}

.fe-box{
    background:#fff;
    border-radius:15px;
    text-align:center;
    padding:30px 20px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
    height:100%;
}

.fe-box:hover{
    transform:translateY(-6px);
}

.fe-box i{
    font-size:40px;
    color:var(--secondary);
    margin-bottom:15px;
}

/* ================= CATEGORY ================= */

.category-card{
    background:#fff;
    border-radius:15px;
    padding:30px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    transition:.3s;
}

.category-card:hover{
    transform:translateY(-5px);
}

.category-card i{
    font-size:40px;
    color:var(--secondary);
    margin-bottom:15px;
}

/* ================= PRODUCTS ================= */

.product-card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    transition:.3s;
    height:100%;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.product-card:hover{
    transform:translateY(-8px);
}

.product-img{
    height:260px;
    overflow:hidden;
    position:relative;
}

.product-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.wishlist-btn{
    position:absolute;
    top:12px;
    right:12px;
    width:40px;
    height:40px;
    border:none;
    border-radius:50%;
    background:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.product-card .card-body{
    padding:20px;
}

.product-card h5{
    font-size:18px;
    font-weight:600;
}

.price{
    color:var(--secondary);
    font-weight:700;
    font-size:20px;
}

.shop-btn{
    display:block;
    text-align:center;
    background:var(--primary);
    color:#fff;
    padding:12px;
    border-radius:10px;
    margin-top:10px;
    text-decoration:none;
    transition:.3s;
    font-weight:600;
}

.shop-btn:hover{
    background:var(--secondary);
    color:#fff;
}

/* ================= BANNER ================= */

.banner{
    margin:70px 0;

    background:
    linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
    url('./public/img/home/banner.jpg');

    background-size:cover;
    background-position:center;

    padding:100px 20px;
    text-align:center;
    color:#fff;
}

.banner h2 span{
    color:#facc15;
}

/* ================= SMALL BANNER ================= */

.small-banner{
    position:relative;
    height:300px;
    overflow:hidden;
    border-radius:20px;
}

.small-banner img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.small-banner-content{
    position:absolute;
    top:50%;
    left:30px;
    transform:translateY(-50%);
    color:#fff;
}

/* ================= NEWSLETTER ================= */

.newsletter{
    background:var(--primary);
    color:#fff;
    padding:60px 30px;
    border-radius:20px;
}

.newsletter input{
    height:50px;
    border:none;
    border-radius:10px;
    padding:10px 15px;
}

.newsletter button{
    height:50px;
    border:none;
    background:var(--secondary);
    color:#fff;
    padding:0 25px;
    border-radius:10px;
}

/* ================= FOOTER ================= */

footer{
    background:#111827;
    color:#fff;
    padding:60px 0 20px;
    margin-top:80px;
}

footer a{
    color:#d1d5db;
    text-decoration:none;
    display:block;
    margin-bottom:10px;
}

footer a:hover{
    color:#fff;
}

.social-icons i{
    font-size:20px;
    margin-right:15px;
}
.nav-link.active{
    color: #2563eb !important;
    font-weight: 700;
}
.pro-container{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
}

.pro{
    width:250px;
    background:#fff;
    border-radius:15px;
    padding:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.pro:hover{
    transform:translateY(-5px);
}

.pro img{
    width:100%;
    height:250px;
    object-fit:cover;
    border-radius:12px;
}
.product-card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    transition:.3s;
    height:100%;
    box-shadow:0 5px 15px rgba(0,0,0,.08);

    display:flex;
    flex-direction:column;
}

.product-card .card-body{
    padding:20px;

    display:flex;
    flex-direction:column;
    flex-grow:1;
}

.price{
    color:var(--secondary);
    font-weight:700;
    font-size:20px;

    margin-top:auto;
}



/* ================= MOBILE ================= */

@media(max-width:768px){

.hero-section h1{
    font-size:45px;
}

.hero-section h2{
    font-size:30px;
}

}

</style>

</head>

<body>

<!-- ================= NAVBAR ================= -->
 

<nav class="navbar navbar-expand-lg shadow-sm sticky-top">

<div class="container">
<a class="navbar-brand" href="#">
AuraClothing 👕
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">
<a class="nav-link active" href="#">Home</a>
</li>
<li class="nav-item">
<a class="nav-link" href="./views/shop.php">Shop</a>
</li>

<li class="nav-item">
<a class="nav-link" href="./views/about.php">About</a>
</li>

<li class="nav-item">
<a class="nav-link" href="./views/contact.php">Contact</a>
</li>

<li class="nav-item ms-3">
<a class="nav-link position-relative" href="./views/wishlist.php">

<i class="fa-regular fa-heart"></i>

<?php
if(isset($_SESSION['user_id'])){

$stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id=?");
$stmt->execute([$_SESSION['user_id']]);

$count = (int)$stmt->fetchColumn();

if($count > 0){
echo "<span class='badge bg-danger position-absolute top-0 start-100 translate-middle'>$count</span>";
}
}
?>

</a>
</li>

<li class="nav-item ms-2">
<a class="nav-link position-relative" href="./views/cart.php">

<i class="fa-solid fa-cart-shopping"></i>

<?php
if(isset($_SESSION['user_id'])){

$stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id=?");
$stmt->execute([$_SESSION['user_id']]);

$count = (int)$stmt->fetchColumn();

if($count > 0){
echo "<span class='badge bg-danger position-absolute top-0 start-100 translate-middle'>$count</span>";
}
}
?>

</a>
</li>

</ul>

</div>

</div>

</nav>

<!-- ================= HERO ================= -->

<section class="hero-section">

<div class="container">

<h2>New Fashion Collection</h2>

<h1>Your Style, Delivered</h1>

<p>
Discover modern fashion for Men, Women & Kids with amazing discounts
</p>

<a href="./views/shop.php">
<button class="hero-btn">
Shop Now
</button>
</a>

</div>

</section>

<!-- ================= FEATURES ================= -->

<section id="feature">

<div class="container">

<div class="row g-4">

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-truck-fast"></i>
<h6>Free Delivery</h6>
</div>
</div>

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-credit-card"></i>
<h6>Secure Payment</h6>
</div>
</div>

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-tags"></i>
<h6>Best Prices</h6>
</div>
</div>

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-gift"></i>
<h6>Promotions</h6>
</div>
</div>

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-headset"></i>
<h6>24/7 Support</h6>
</div>
</div>

<div class="col-md-2 col-6">
<div class="fe-box">
<i class="fas fa-shirt"></i>
<h6>New Fashion</h6>
</div>
</div>

</div>

</div>

</section>

<!-- ================= CATEGORY ================= -->

<section class="py-5">

<div class="container">

<h2 class="text-center fw-bold mb-5">
Shop By Category
</h2>

<div class="row g-4">

<div class="col-md-3">
<div class="category-card">
<i class="fas fa-person"></i>
<h5>Men</h5>
</div>
</div>

<div class="col-md-3">
<div class="category-card">
<i class="fas fa-person-dress"></i>
<h5>Women</h5>
</div>
</div>

<div class="col-md-3">
<div class="category-card">
<i class="fas fa-child"></i>
<h5>Kids</h5>
</div>
</div>

<div class="col-md-3">
<div class="category-card">
<i class="fas fa-gem"></i>
<h5>Accessories</h5>
</div>
</div>

</div>

</div>

</section>

<!-- ================= PRODUCTS ================= -->

<section class="py-5 bg-light">

<div class="container">

<h2 class="fw-bold mb-5">
Featured Products
</h2>

<div class="row g-4">

<?php foreach($products as $product): ?>

<div class="col-lg-3 col-md-6">

<div class="card product-card">

<div class="product-img">

<img src="./uploads/products/<?= htmlspecialchars($product['image']); ?>">

<?php if(isset($_SESSION['user_id'])): ?>

<button class="wishlist-btn">
<i class="far fa-heart"></i>
</button>

<?php endif; ?>

</div>

<div class="card-body">

<h5>
<?= htmlspecialchars($product['name']); ?>
</h5>

<p class="price">
Rs. <?= number_format($product['price']); ?>
</p>
<a href="./views/shop.php?product_id=<?= $product['id']; ?>" class="shop-btn">
    Shop Now
</a>


</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

</section>

<!-- ================= BANNER ================= -->

<section class="banner">

<div class="container">

<h4>Fashion Deals</h4>

<h2>
Up to <span>70% OFF</span> On All Fashion Products
</h2>

<button class="hero-btn">
Explore More
</button>

</div>

</section>

<!-- ================= SMALL BANNERS ================= -->

<section class="container py-5">

<div class="row g-4">

<div class="col-md-6">

<div class="small-banner">

<img src="./public/img/home/banner1.jpg">

<div class="small-banner-content">

<h4>Spring Collection</h4>

<h2>Upcoming Fashion</h2>

<button class="hero-btn">
Learn More
</button>

</div>

</div>

</div>

<div class="col-md-6">

<div class="small-banner">

<img src="./public/img/home/banner2.jpg">

<div class="small-banner-content">

<h4>Crazy Deals</h4>

<h2>Buy 1 Get 1 Free</h2>

<button class="hero-btn">
Learn More
</button>

</div>

</div>

</div>

</div>

</section>

<!-- ================= NEWSLETTER ================= -->

<section class="container">

<div class="newsletter">

<div class="row align-items-center">

<div class="col-md-6">

<h2>Subscribe To Newsletter</h2>

<p>
Get updates about latest fashion collections and special offers
</p>

</div>

<div class="col-md-6">

<form class="d-flex gap-2">

<input type="email" class="form-control" placeholder="Enter your email">

<button type="submit">
Subscribe
</button>

</form>

</div>

</div>

</div>

</section>

<!-- ================= FOOTER ================= -->

<footer>

<div class="container">

<div class="row">

<div class="col-md-3">

<h4>AuraClothing 👕</h4>

<p>
Modern fashion store with stylish collections for Men, Women & Kids.
</p>

<div class="social-icons">

<i class="fab fa-facebook-f"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-tiktok"></i>

</div>

</div>

<div class="col-md-3">

<h4>About</h4>

<a href="#">About Us</a>
<a href="#">Delivery Information</a>
<a href="#">Privacy Policy</a>
<a href="#">Terms & Conditions</a>

</div>

<div class="col-md-3">

<h4>My Account</h4>

<a href="#">Sign In</a>
<a href="#">View Cart</a>
<a href="#">Wishlist</a>
<a href="#">Track Order</a>

</div>

<div class="col-md-3">

<h4>Contact</h4>

<p>Email: support@auraclothing.lk</p>
<p>Phone: +94 71 123 4567</p>
<p>Colombo, Sri Lanka</p>

</div>

</div>

<hr class="bg-light">

<p class="text-center mb-0">
© 2026 AuraClothing.lk | All Rights Reserved
</p>

</div>

</footer>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>