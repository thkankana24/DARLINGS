<?php require_once __DIR__ . '/sql/customer_session.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Darlings | Account</title>
  <link rel="stylesheet" href="css/user.css">
  <link rel="stylesheet" href="css/footer.css">
</head>
<body>
  <header><a href="products.php">&larr; Shop</a><h1>ACCOUNT</h1><a href="orders.php">Orders</a></header>
  <main class="account-wrap">
    <section class="user-info"><div class="avatar" id="initial">D</div><div><h2 id="customerName">Darlings customer</h2><p id="customerEmail">Sign in to keep your account details handy.</p></div></section>
    <section class="buttons"><a href="orders.php">My orders</a><a href="products.php">Continue shopping</a></section>
    <section class="invite"><h3>INVITE FRIENDS &amp; EARN</h3><p>Share Darlings with someone who loves great style.</p></section>
    <section class="payments"><h3>MY PAYMENTS</h3><ul><li>Darlings Pay Later</li><li>Bank &amp; UPI Details</li><li>Payment &amp; Refund</li></ul></section>
    <section class="activity"><h3>MY ACTIVITY</h3><ul><li>Wishlisted Products <span id="wishlistCount">0 saved</span></li><li>Saved delivery details</li><li>Help Centre</li></ul></section>
    <section class="others"><h3>OTHERS</h3><ul><li>Settings</li><li>Legal and Policies</li><li><button id="logoutBtn">Log out</button></li></ul></section>
  </main>
  <footer class="darlings-footer"><div class="footer-container"><div><h3>Darlings</h3><p>Your one-stop shop for fashion, shoes, and more.</p></div><div><h4>Quick Links</h4><ul><li><a href="index.php">Home</a></li><li><a href="products.php">Shop</a></li><li><a href="about.php">About Us</a></li></ul></div><div><h4>Customer Service</h4><ul><li><a href="products.php">FAQs</a></li><li><a href="orders.php">My Orders</a></li></ul></div><div><h4>Subscribe</h4><form class="newsletter" onsubmit="return false"><input type="email" placeholder="Enter your email" aria-label="Email address"><button>Subscribe</button></form></div></div><div class="footer-bottom"><p>&copy; 2026 Darlings. All rights reserved.</p><div class="socials"><a href="#">Facebook</a><a href="#">Instagram</a></div></div></footer>
  <script>
    (async () => {
    const customerResponse = await fetch('api/auth.php');
    const { customer } = await customerResponse.json();
    if (customer) {
      document.getElementById('customerName').textContent = customer.name;
      document.getElementById('customerEmail').textContent = customer.email;
      document.getElementById('initial').textContent = customer.name.charAt(0).toUpperCase();
    }
    const wishes = JSON.parse(localStorage.getItem('darlings_wishlist') || '[]');
    document.getElementById('wishlistCount').textContent = `${wishes.length} saved`;
    document.getElementById('logoutBtn').addEventListener('click', async () => { await fetch('api/auth.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'logout'})}); window.location.href = 'index.php'; });
    })();
  </script>
</body>
</html>
