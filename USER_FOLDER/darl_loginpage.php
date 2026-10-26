<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Darlings | Sign in</title>
  <link rel="stylesheet" href="css/LoginPageD.css">
  <link rel="stylesheet" href="css/footer.css">
</head>
<body>
  <main class="login-container">
    <a class="brand-link" href="index.php">Darlings</a>
    <h2>Welcome back</h2>
    <p class="form-copy">Sign in to view your saved details and orders.</p>
    <form id="loginForm">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" placeholder="you@example.com" required autocomplete="email">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" placeholder="Your password" required autocomplete="current-password">
      <p class="form-message" id="message" role="alert" hidden></p>
      <button type="submit">Sign in</button>
    </form>
    <p>New to Darlings? <a href="signup.php">Create an account</a></p>
    <p class="back-link"><a href="products.php">Continue shopping</a></p>
  </main>
  <footer class="darlings-footer"><div class="footer-container"><div><h3>Darlings</h3><p>Your one-stop shop for fashion, shoes, and more.</p></div><div><h4>Quick Links</h4><ul><li><a href="index.php">Home</a></li><li><a href="products.php">Shop</a></li><li><a href="about.php">About Us</a></li></ul></div><div><h4>Customer Service</h4><ul><li><a href="products.php">FAQs</a></li><li><a href="products.php">Shipping &amp; Returns</a></li></ul></div><div><h4>Subscribe</h4><form class="newsletter" onsubmit="return false"><input type="email" placeholder="Enter your email" aria-label="Email address"><button>Subscribe</button></form></div></div><div class="footer-bottom"><p>&copy; 2026 Darlings. All rights reserved.</p><div class="socials"><a href="#">Facebook</a><a href="#">Instagram</a></div></div></footer>
  <script>
    document.getElementById('loginForm').addEventListener('submit', async function (event) {
      event.preventDefault();
      const message = document.getElementById('message');
      message.hidden = true;
      const response = await fetch('api/auth.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({action:'login', email:this.email.value, password:this.password.value}) });
      const data = await response.json();
      if (!response.ok) {
        message.textContent = data.message || 'Sign in failed.';
        message.hidden = false;
        return;
      }
      const next = new URLSearchParams(window.location.search).get('next');
      window.location.href = next && /^[a-z0-9_-]+\.php$/i.test(next) ? next : 'userpage.php';
    });
    if (new URLSearchParams(window.location.search).has('created')) {
      const message = document.getElementById('message');
      message.textContent = 'Your account is ready. Sign in to continue.';
      message.style.color = '#287a45';
      message.hidden = false;
    }
  </script>
</body>
</html>
