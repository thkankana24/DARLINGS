<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Darlings Fashion</title>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/products.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.1.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid header-menu">
<div class="row align-items-center">
<div class="col-md-4 col-lg-4 col-sm-12 logo-section">
<a href="#"><img src="img/logo.jpeg" alt="Logo"></a>
</div>
<div class="col-md-4 col-lg-4 col-sm-12 menus">
<nav class="navbar navbar-expand-lg">
<div class="container-fluid">
<a class="navbar-brand" href="#"><img src="img/logo.jpeg" alt="Logo"></a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
<div class="navbar-nav">
<a class="nav-link" aria-current="page" href="category.php"></a>
<a class="nav-link" href="about.php">About us</a>
<a class="nav-link" href="products.php">Products</a>
</div>
</div>
</div>
</nav>
</div>
<div class="col-md-4 col-lg-4 col-sm-12 top-right">
<a href="#"><i class="fa-solid fa-0"></i></a>
<a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
<a href="darl_loginpage.php">Log In</a>
<a href="signup.php">Sign Up</a>
<a href="userpage.php"><i class="fa-solid fa-circle-user"></i></a>
</div>
</div>
</div>
<section>
<div class="container-fluid slider">
<div class="row">
<div class="col-12 m-0 p-0">
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-inner">
<div class="carousel-item active">
<img src="img/banner.jpeg" class="d-block w-100" alt="Banner">
</div>
<div class="carousel-item">
<img src="img/BANNER2.jpeg" class="d-block w-100" alt="Fashion">
</div>
<div class="carousel-item">
<img src="img/IMG3.jpeg" class="d-block w-100" alt="Fashion">
</div>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
<span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
<span class="visually-hidden">Next</span>
</button>
</div>
</div>
</div>
</div>
</section>
<section class="products py-5">
<div class="container home-products">
<div class="home-products-heading"><p>THE DARLINGS COLLECTION</p><h2>Shop our collection</h2><span>Select a product to see its details.</span></div>
<p id="homeProductsMessage" class="products-message" role="status">Loading products…</p>
<div id="homeProductGrid" class="product-grid" aria-label="Products"></div>
</div>
</section>
<footer class="footer">
<div class="footer-container">
<div class="footer-section">
<h3>Darlings</h3>
<p>Your one-stop shop for fashion, shoes, and more.</p>
</div>
<div class="footer-section">
<h4>Quick Links</h4>
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="products.php">Shop</a></li>
<li><a href="about.php">About Us</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div class="footer-section">
<h4>Customer Service</h4>
<ul>
<li><a href="FAQ.php">FAQs</a></li>
<li><a href="shipping&returns.php">Shipping & Returns</a></li>
<li><a href="privacy_policy.php">Privacy Policy</a></li>
<li><a href="term&conditions.php">Terms & Conditions</a></li>
</ul>
</div>
<div class="footer-section">
<h4>Subscribe</h4>
<form class="newsletter">
<input type="email" placeholder="Enter your email" required>
<button type="submit">Subscribe</button>
</form>
</div>
</div>
<div class="footer-bottom">
<p>&copy; 2026 Darlings. All rights reserved.</p>
<div class="socials">
<a href="#">Facebook</a>
<a href="#">Instagram</a>
<a href="#">Twitter</a>
</div>
</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  const grid = document.getElementById('homeProductGrid');
  const message = document.getElementById('homeProductsMessage');
  const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
  fetch('api/products.php').then(response => { if (!response.ok) throw new Error(); return response.json(); }).then(products => {
    if (!Array.isArray(products) || !products.length) { message.textContent = 'No products are available right now.'; return; }
    message.textContent = '';
    grid.innerHTML = products.map(product => `<a class="product-card home-product-card" href="product.php?id=${Number(product.id)}"><div class="product-image-wrap"><img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" loading="lazy" onerror="this.src='img/fashionimg.jpeg'"></div><div class="product-info"><p class="product-category">${escapeHtml(product.category)}</p><h2>${escapeHtml(product.name)}</h2><p class="product-description">${escapeHtml(product.description)}</p><div class="home-card-price">₹${Number(product.price).toLocaleString('en-IN')} <span>View details →</span></div></div></a>`).join('');
  }).catch(() => { message.textContent = 'Products could not be loaded. Please try again later.'; });
})();
</script>
</body>
</html>
