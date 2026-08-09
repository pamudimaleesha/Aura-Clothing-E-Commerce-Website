<?php
session_start();
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - AuraClothing.lk</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">

  <style>

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

    /* Navbar */

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

    .username {
      font-weight: 600;
      color: var(--primary) !important;
    }

    /* About Hero */

    .about-hero {
      background-color: var(--light);
      padding: 5rem 0;
    }

    .about-image {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .about-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .about-image:hover img {
      transform: scale(1.03);
    }

    .about-hero h2 {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: var(--primary);
    }

    .about-hero p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #4A5568;
      margin-bottom: 2rem;
    }

    .feature-list li {
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
    }

    .feature-list li i {
      color: var(--primary);
      margin-right: 1rem;
      font-size: 1.2rem;
    }

    /* Feature Cards */

    .feature-card {
      background-color: white;
      border-radius: 10px;
      padding: 2rem;
      height: 100%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    /* Values */

    .values-section {
      background-color: var(--light);
      padding: 5rem 0;
    }

    .value-card {
      background-color: white;
      border-radius: 10px;
      padding: 2rem;
      text-align: center;
      height: 100%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }

    .value-card:hover {
      transform: translateY(-5px);
    }

    .value-icon {
      width: 70px;
      height: 70px;
      background-color: var(--light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: var(--primary);
      font-size: 1.8rem;
    }

    /* Team Section */

    .team-section {
      padding: 5rem 0;
      background-color: white;
    }

    .team-card {
      text-align: center;
      padding: 1.5rem;
      border-radius: 10px;
      background-color: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }

    .team-card:hover {
      transform: translateY(-5px);
    }

    .team-img-container {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      overflow: hidden;
      margin: 0 auto 1.5rem;
      border: 5px solid var(--light);
    }

    .team-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Footer */

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

    @media (max-width: 767px) {

      .about-hero {
        padding: 3rem 0;
      }

      .about-image {
        margin-top: 2rem;
      }

      .values-section {
        padding: 3rem 0;
      }

    }
    /* ===== Slider ===== */
.slider {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: auto;
    height: 400px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
}

.slide.active {
    opacity: 1;
}

  </style>
</head>

<body>

<!-- Navbar -->
<?php include_once('../includes/nav.php'); ?>

<!-- About Hero Section -->

<section class="about-hero py-5">

  <div class="container">

    <div class="row align-items-center g-4">

      <div class="col-lg-6 mb-4 mb-lg-0">

        <h2 class="text-primary display-4 fw-bold">
          About AuraClothing.lk
        </h2>

        <p class="mb-4">
          Welcome to AuraClothing.lk, your trusted online fashion destination in Sri Lanka.
          We are passionate about bringing stylish, trendy, and affordable fashion to everyone
          through a seamless online shopping experience.
          <br><br>

          AuraClothing.lk was created with the vision of helping people express their confidence
          and personality through fashion. From casual wear to premium outfits and accessories,
          we provide high-quality collections for Men, Women, and Kids all in one place.
          <br><br>

          We carefully select our products from trusted fashion suppliers to ensure premium
          quality, comfort, and modern style. Our dedicated team works hard to provide
          excellent customer service, secure online shopping, and fast delivery across Sri Lanka.
        </p>

        <ul class="feature-list list-unstyled mb-4">

          <li>
            <i class="fa-solid fa-shirt"></i>
            Latest fashion trends for Men, Women & Kids
          </li>

          <li>
            <i class="fa-solid fa-truck-fast"></i>
            Fast and reliable islandwide delivery
          </li>

          <li>
            <i class="fa-solid fa-tags"></i>
            Affordable prices with exciting discounts
          </li>

          <li>
            <i class="fa-solid fa-medal"></i>
            Premium quality clothing and accessories
          </li>

          <li>
            <i class="fa-solid fa-lock"></i>
            Secure online shopping experience
          </li>

        </ul>

        <a href="./shop.php" class="btn btn-primary btn-lg px-4">
          Shop Now
        </a>

      </div>

      <div class="col-lg-6">

        <div class="about-image">
          <img src="../public/img/about/about_cover.jpg"
               alt="AuraClothing.lk"
               class="img-fluid">
        </div>

      </div>

    </div>

  </div>

</section>

<!-- Download App Slider -->
<section id="about-slider" class="section-p1 my-5">

    <div class="text-center mb-4">
        <h2 class="text-primary fw-bold">Download Our App</h2>
    </div>

    <div class="slider">
        <img src="../uploads/products/downloadapp.jpg" class="slide active">
        <img src="../uploads/products/download1.jpg" class="slide">
        <img src="../uploads/products/downloadApp2.jpg" class="slide">
        <img src="../uploads/products/download3.jpg" class="slide">
    </div>

</section>

<!-- Features Section -->

<section class="py-5 bg-white">

  <div class="container">

    <h2 class="text-center text-primary mb-5 fw-bold">
      Why Choose AuraClothing.lk?
    </h2>

    <div class="row g-4">

      <div class="col-md-4">

        <div class="feature-card">

          <div class="text-center mb-4">
            <i class="fas fa-shirt text-primary fa-3x"></i>
          </div>

          <h4 class="text-center mb-3">
            Trendy Fashion
          </h4>

          <p class="text-muted">
            Discover the latest fashion collections and stylish outfits carefully selected to match modern trends and comfort.
          </p>

        </div>

      </div>

      <div class="col-md-4">

        <div class="feature-card">

          <div class="text-center mb-4">
            <i class="fas fa-truck-fast text-primary fa-3x"></i>
          </div>

          <h4 class="text-center mb-3">
            Fast Delivery
          </h4>

          <p class="text-muted">
            We provide fast and reliable delivery across Sri Lanka so your favorite fashion items arrive safely and on time.
          </p>

        </div>

      </div>

      <div class="col-md-4">

        <div class="feature-card">

          <div class="text-center mb-4">
            <i class="fas fa-tags text-primary fa-3x"></i>
          </div>

          <h4 class="text-center mb-3">
            Affordable Prices
          </h4>

          <p class="text-muted">
            Enjoy premium quality fashion at affordable prices with exciting discounts and seasonal offers.
          </p>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- Values Section -->

<section class="values-section">

  <div class="container">

    <h2 class="text-center text-primary mb-5 fw-bold">
      Our Core Values
    </h2>

    <div class="row g-4">

      <div class="col-md-4">

        <div class="value-card">

          <div class="value-icon">
            <i class="fas fa-star"></i>
          </div>

          <h4 class="mb-3">
            Quality
          </h4>

          <p class="text-muted">
            We are committed to providing high-quality fashion products that combine comfort, durability, and style.
          </p>

        </div>

      </div>

      <div class="col-md-4">

        <div class="value-card">

          <div class="value-icon">
            <i class="fas fa-users"></i>
          </div>

          <h4 class="mb-3">
            Customer Satisfaction
          </h4>

          <p class="text-muted">
            Our customers are our priority. We continuously improve our services to ensure a smooth shopping experience.
          </p>

        </div>

      </div>

      <div class="col-md-4">

        <div class="value-card">

          <div class="value-icon">
            <i class="fas fa-shield-alt"></i>
          </div>

          <h4 class="mb-3">
            Trust
          </h4>

          <p class="text-muted">
            We value honesty, secure transactions, and transparency to build long-term trust with every customer.
          </p>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- Team Section -->

<section class="team-section">

  <div class="container">

    <h2 class="text-center text-primary mb-5 fw-bold">
      Meet Our Team
    </h2>

    <div class="row g-4">

      <div class="col-lg-3 col-md-6">

        <div class="team-card">

          <div class="team-img-container">
            <img src="../public/img/about/team-1.jpg"
                 class="team-img"
                 alt="Team Member">
          </div>

          <h4>Pamudi Maleesha</h4>

          <p class="text-muted">
            Founder & CEO
          </p>

          <p class="small">
            Leads AuraClothing.lk with a vision to make stylish and affordable fashion accessible to everyone.
          </p>

        </div>

      </div>

      <div class="col-lg-3 col-md-6">

        <div class="team-card">

          <div class="team-img-container">
            <img src="../public/img/about/team-2.jpg"
                 class="team-img"
                 alt="Team Member">
          </div>

          <h4>Kavindu Perera</h4>

          <p class="text-muted">
            Operations Manager
          </p>

          <p class="small">
            Handles product management, supplier coordination, and daily operations.
          </p>

        </div>

      </div>

      <div class="col-lg-3 col-md-6">

        <div class="team-card">

          <div class="team-img-container">
            <img src="../public/img/about/team-3.jpg"
                 class="team-img"
                 alt="Team Member">
          </div>

          <h4>Nethmi Fernando</h4>

          <p class="text-muted">
            Fashion Coordinator
          </p>

          <p class="small">
            Selects modern fashion trends and manages the latest clothing collections.
          </p>

        </div>

      </div>

      <div class="col-lg-3 col-md-6">

        <div class="team-card">

          <div class="team-img-container">
            <img src="../public/img/about/team-4.jpg"
                 class="team-img"
                 alt="Team Member">
          </div>

          <h4>Dinuka Silva</h4>

          <p class="text-muted">
            Technology Lead
          </p>

          <p class="small">
            Develops and maintains the AuraClothing website for a smooth shopping experience.
          </p>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- Footer -->

<?php include_once('../includes/footer.php'); ?>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
<script>
const slides = document.querySelectorAll('#about-slider .slide');
let currentIndex = 0;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

setInterval(() => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}, 3000);
</script>
</body>
</html>