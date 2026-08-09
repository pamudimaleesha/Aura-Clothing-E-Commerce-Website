<?php
$current = basename($_SERVER['PHP_SELF']);

// Default counts
$wishlistCount = 0;
$cartCount = 0;

if (isset($_SESSION['user_id'])) {

    // Wishlist count
    $stmt = $conn->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $wishlistCount = (int)$stmt->fetchColumn();

    // Cart count
    $stmt = $conn->prepare('SELECT COUNT(*) FROM cart WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $cartCount = (int)$stmt->fetchColumn();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 sticky-top">
    <div class="container">

        <a class="navbar-brand" href="../index.php">
            Aura Clothing <i class="fa-solid fa-basket-shopping ms-1"></i>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- MAIN MENU -->
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link <?= $current == 'index.php' ? 'active' : '' ?>"
                       href="../index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current == 'about.php' ? 'active' : '' ?>"
                       href="./about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current == 'shop.php' ? 'active' : '' ?>"
                       href="./shop.php">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current == 'contact.php' ? 'active' : '' ?>"
                       href="./contact.php">Contact</a>
                </li>

            </ul>

            <!-- ICONS -->
            <ul class="navbar-nav ms-3">

                <!-- Wishlist -->
                <li class="nav-item">
                    <a class="nav-link position-relative <?= $current == 'wishlist.php' ? 'active' : '' ?>"
                       href="./wishlist.php">

                        <i class="fa-regular fa-heart"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger wishlist-count"
                              style="<?= $wishlistCount > 0 ? '' : 'display:none;' ?>">
                            <?= $wishlistCount ?>
                        </span>

                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link position-relative <?= $current == 'cart.php' ? 'active' : '' ?>"
                       href="./cart.php">

                        <i class="fa-solid fa-cart-shopping"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count"
                              style="<?= $cartCount > 0 ? '' : 'display:none;' ?>">
                            <?= $cartCount ?>
                        </span>

                    </a>
                </li>

                <!-- USER -->
                <?php if (isset($_SESSION['name'])): ?>

                    <?php if ($_SESSION['user_type'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin/dashboard.php">
                                <i class="fa-solid fa-user-shield"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./user/profile.php">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <span class="nav-link text-dark">
                            <?= htmlspecialchars($_SESSION['name']); ?>
                        </span>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="./signin.php">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>