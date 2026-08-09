<?php 
session_start();
include '../../config/db.php';
  $users = [];
  $stmt = $conn->query('SELECT id, name, email, role FROM users ORDER BY id ASC');
  if ($stmt) {
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } 
  
  $products = [];
  $sql = 'SELECT p.id, p.name, c.category_name, p.price, p.qty, p.image, p.created_at FROM products p LEFT JOIN category c ON p.category_id = c.id ORDER BY p.id DESC';
  $stmt = $conn->query($sql);
  if ($stmt) {
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  $categories = [];
  $stmt = $conn->query('SELECT id, category_name FROM category ORDER BY id ASC');
  if ($stmt) {
      $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  $orders = [];
$stmt = $conn->query("
    SELECT
        id,
        order_id,
        user_id,
        total_amount,
        payment_method,
        status,
        created_at
    FROM orders
    ORDER BY id DESC
");
if ($stmt) {
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AuraClothing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
   <style>
:root{
    --primary:#2563eb;
    --secondary:#1d4ed8;
    --accent:#8b5cf6;
    --success:#10b981;
    --warning:#f59e0b;
    --danger:#ef4444;
    --dark:#0f172a;
    --light:#f8fafc;

    --border-color:#e2e8f0;
    --card-shadow:0 10px 25px rgba(0,0,0,.08);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    color:var(--dark);
}

.admin-wrapper{
    min-height:100vh;
    overflow-x:hidden;
}

.main-content{
    padding:2rem;
}

@media (min-width:768px){
    .main-content{
        margin-left:25%;
        width:75%;
    }
}

@media (min-width:992px){
    .main-content{
        margin-left:16.666667%;
        width:83.333333%;
    }
}

/* HEADER */

.page-header{
    position:relative;
    overflow:hidden;
    padding:2rem;
    border-radius:20px;
    margin-bottom:2rem;
    background:linear-gradient(
        135deg,
        #2563eb,
        #7c3aed
    );
    color:white;
    box-shadow:0 20px 40px rgba(37,99,235,.25);
}

.page-header::before{
    content:'';
    position:absolute;
    width:300px;
    height:300px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
    top:-100px;
    right:-100px;
}

.page-header::after{
    content:'';
    position:absolute;
    width:200px;
    height:200px;
    background:rgba(255,255,255,.05);
    border-radius:50%;
    bottom:-80px;
    left:-80px;
}

.welcome-title{
    font-size:2rem;
    font-weight:700;
    position:relative;
    z-index:2;
}

.welcome-text{
    opacity:.9;
    position:relative;
    z-index:2;
}

.welcome-icon{
    position:absolute;
    right:30px;
    top:50%;
    transform:translateY(-50%);
    font-size:5rem;
    opacity:.15;
}

/* DASHBOARD CARDS */

.stat-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    transition:.4s;
    height:100%;
    color:white;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.stat-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 35px rgba(0,0,0,.15);
}

.stat-card.products{
    background:linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );
}

.stat-card.categories{
    background:linear-gradient(
        135deg,
        #10b981,
        #34d399
    );
}

.stat-card.orders{
    background:linear-gradient(
        135deg,
        #f59e0b,
        #fbbf24
    );
}

.stat-card.users{
    background:linear-gradient(
        135deg,
        #ec4899,
        #f472b6
    );
}

.stat-card .card-body{
    padding:2rem;
}

.stat-icon-wrapper{
    width:75px;
    height:75px;
    margin:auto;
    margin-bottom:1rem;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(255,255,255,.2);
    color:white;
    transition:.4s;
}

.stat-card:hover .stat-icon-wrapper{
    transform:scale(1.1);
}

.stat-count{
    font-size:2.8rem;
    font-weight:700;
    color:white;
    line-height:1;
}

.stat-title{
    margin-top:.5rem;
    color:white;
    letter-spacing:1px;
    font-size:.9rem;
    text-transform:uppercase;
}

/* SECTION TITLE */

.section-title{
    color:#1e293b;
    font-weight:700;
    margin-bottom:1rem;
    padding-left:15px;
    border-left:5px solid #2563eb;
}

/* QUICK LINKS */

.quick-links-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    background:white;
    box-shadow:var(--card-shadow);
}

.quick-links-header{
    padding:1.2rem 1.5rem;
    background:#f8fafc;
    border-bottom:1px solid var(--border-color);
}

.quick-links-header h5{
    margin:0;
    color:#1e293b;
    font-weight:600;
}

.quick-links{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:1rem;
    padding:1.5rem;
}

.quick-link{
    text-decoration:none;
    color:#1e293b;
    background:white;
    padding:1rem;
    border-radius:15px;
    display:flex;
    align-items:center;
    transition:.3s;
    border:1px solid #e5e7eb;
}

.quick-link:hover{
    background:#2563eb;
    color:white;
    transform:translateY(-5px);
}

.quick-link-icon{
    width:45px;
    height:45px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#eff6ff;
    color:#2563eb;
    margin-right:1rem;
    transition:.3s;
}

.quick-link:hover .quick-link-icon{
    background:white;
    color:#2563eb;
}

.quick-link-text{
    font-weight:600;
}

/* GENERAL CARD */

.card{
    border:none;
    border-radius:20px;
    box-shadow:var(--card-shadow);
}

/* ANIMATION */

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.stat-card,
.quick-links-card,
.page-header{
    animation:fadeUp .6s ease;
}

/* MOBILE */

@media(max-width:767px){

    .main-content{
        padding:1rem;
        padding-top:5rem;
    }

    .welcome-icon{
        display:none;
    }

    .welcome-title{
        font-size:1.5rem;
    }

    .quick-links{
        grid-template-columns:1fr;
    }

    .stat-count{
        font-size:2.2rem;
    }
}
</style>
</head>
<body>    <div class="container-fluid px-0 admin-wrapper">
        <div class="row g-0">
            <!-- Sidebar -->
            <?php include_once('./includes/admin_nav.php'); ?>
            
            <!-- Main Content -->
            <main class="col-12 main-content">
                <div class="page-header">
                    <h1 class="welcome-title">Welcome to Auraclothing Admin Dashboard</h1>
                    <p class="welcome-text">Manage your products, categories, orders, and users from this central dashboard.</p>
                    <i class="fas fa-tachometer-alt welcome-icon"></i>
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card products">
                            <div class="card-body text-center">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-box-open fa-2x"></i>
                                </div>
                                <div class="stat-count"><?php echo count($products); ?></div>
                                <h6 class="stat-title">Products</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card categories">
                            <div class="card-body text-center">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-tags fa-2x"></i>
                                </div>
                                <div class="stat-count"><?php echo count($categories); ?></div>
                                <h6 class="stat-title">Categories</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card orders">
                            <div class="card-body text-center">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                                <div class="stat-count"><?php echo count($orders); ?></div>
                                <h6 class="stat-title">Orders</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card users">
                            <div class="card-body text-center">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div class="stat-count"><?php echo count($users); ?></div>
                                <h6 class="stat-title">Users</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links Section -->
                <div class="recent-activity">
                    <h2 class="section-title">Quick Actions</h2>
                    <div class="card quick-links-card">
                        <div class="quick-links-header">
                            <h5><i class="fas fa-bolt me-2"></i> Quick Links</h5>
                        </div>
                        <div class="quick-links">
                            <a href="./product_add.php" class="quick-link">
                                <div class="quick-link-icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="quick-link-text">Add New Product</span>
                            </a>
                            <a href="./category_add.php" class="quick-link">
                                <div class="quick-link-icon">
                                    <i class="fas fa-folder-plus"></i>
                                </div>
                                <span class="quick-link-text">Add New Category</span>
                            </a>
                            <a href="./orders.php" class="quick-link">
                                <div class="quick-link-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <span class="quick-link-text">View Recent Orders</span>
                            </a>
                            <a href="./feedbacks.php" class="quick-link">
                                <div class="quick-link-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <span class="quick-link-text">Check Feedbacks</span>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
</body>
</html>
