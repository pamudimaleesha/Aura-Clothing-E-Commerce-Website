<?php
session_start();
include '../config/db.php';

$users_id = $_SESSION['user_id'] ?? null;

if (!$users_id) {
    header('Location: ./signin.php');
    exit;
}

/* =========================
   GET USER
========================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$users_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   SUBMIT FEEDBACK
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $message = trim($_POST['message']);

    if (!empty($message)) {

        $stmt = $conn->prepare("
            INSERT INTO feedback (user_id, message, status)
            VALUES (?, ?, 'inactive')
        ");

        if ($stmt->execute([$users_id, $message])) {
            echo "<script>
                alert('Feedback submitted successfully!');
                window.location.href = 'contact.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Failed to submit feedback');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Auraclothing.lk</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
     #page-header{
    background:#0a3c99;
    color:#fff;
    text-align:center;
    padding:60px 20px;
}
     :root {
      --primary: #0a3c99;
      --secondary: #005ce7;
      --light: #F0FFF4;
      --accent: #282be0;
      --dark: #1A202C;
    }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        
        .navbar-brand {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            color: var(--primary);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .bg-primary {
            background-color: var(--primary) !important;
        }
        
        .text-primary {
            color: var(--primary) !important;
        }
        
        /* Navigation styles */
        .navbar-nav .nav-link {
            color: var(--dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary);
        }
        
        .nav-link.active {
            font-weight: 600;
            color: var(--primary) !important;
        }
        
        /* Username styles */
        .username {
            font-weight: 600;
            color: var(--primary) !important;
        }
        
        /* Footer styles */
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer-logo {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            color: white;
        }
        
        .footer-link {
            color: #a0aec0;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
        }
        
        .footer-link:hover {
            color: white;
        }
        
        .footer-social {
            color: white;
            margin: 0 10px;
            font-size: 1.2rem;
        }
        
        .footer-social:hover {
            color: var(--accent);
        }
        
        /* Contact page specific styles */
        .contact-hero-section {
            background-color: var(--light);
            padding: 3rem 0;
        }
        
        .contact-title {
            color: var(--primary);
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        .contact-desc {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #4A5568;
        }
        
        .contact-details li i {
            color: var(--primary);
            width: 20px;
            text-align: center;
        }
        
        .contact-link {
            color: #4A5568;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .contact-link:hover {
            color: var(--primary);
        }
        
        .contact-form-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .contact-form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .contact-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .contact-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 133, 90, 0.1);
        }
        
        .contact-map-section {
            padding: 3rem 0;
        }
        
        .map-embed-wrapper {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .map-embed-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 767px) {
            .contact-hero-section,
            .contact-map-section {
                padding: 2rem 0;
            }
            
            .contact-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<!-- Navbar (copied from index.html, do not change) -->
 <?php include_once('../includes/nav.php'); ?>
<!-- PAGE HEADER -->
<section id="page-header">
    <h2>#Let's Talk</h2>
    <p>Leave a message, we would love to hear from you</p>
</section>
<section class="contact-hero-section py-5">
    <div class="container">
        <div class="row g-5 align-items-start">
             <!-- CONTACT DETAILS -->
        <div class="row mt-5">

            <div class="col-lg-12 text-center">

                <h2>We Love To Hear From You!</h2>

                <p class="contact-desc">
                    We'd love to hear from you! Whether you have a question about our products,
                    need help with an order, or just want to share your experience.
                </p>

                <ul class="contact-details list-unstyled">

                    <li>
                        <i class="fa-solid fa-phone"></i>
                        0715343747
                    </li>

                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        info@AuraClothing.lk
                    </li>

                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        Colombo 05, Sri Lanka
                    </li>

                </ul>

            </div>

        </div>

    </div>

            <!-- MAP + FORM ROW -->
    <div class="row g-4 align-items-stretch">

       <!-- LEFT SIDE - MAP -->

            <div class="col-lg-6">

                <div class="map-embed-wrapper rounded-4 overflow-hidden shadow">

                    <iframe

                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63316.29376355144!2d79.8250176!3d6.9270786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2595b6b6b6b6b%3A0x7b7b7b7b7b7b7b7b!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2slk!4v1680000000000!5m2!1sen!2slk"

                        width="100%"

                        height="500"

                        style="border:0;"

                        allowfullscreen=""

                        loading="lazy">

                    </iframe>

                </div>

            </div>

      <!-- FORM (RIGHT) -->
      <div class="col-lg-6">
        <div class="contact-form-card p-4 shadow-lg rounded-4 h-100">

          <form method="POST" action="">
            <h3 class="text-center mb-4">Show Some Love ❤️</h3>

            <input type="text"
              class="form-control contact-input mb-3"
              value="<?php echo htmlspecialchars($user['name']); ?>"
              readonly>

            <input type="email"
              class="form-control contact-input mb-3"
              value="<?php echo htmlspecialchars($user['email']); ?>"
              readonly>

            <textarea
              name="message"
              id="message"
              class="form-control contact-input mb-3"
              rows="6"
              placeholder="Your Message"
              required></textarea>

            <button class="btn btn-primary w-100" type="submit">
              Submit Feedback
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
</section>



<!-- Footer (copied from index.html, do not change) -->
 <?php include_once('../includes/footer.php'); ?>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../public/js/main.js"></script>
</body>
<?php
if(isset($_GET['success'])){
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon:"success",
        title:"Success",
        text:"Feedback submitted successfully."
    });
    </script>';
}
?>
</html>
