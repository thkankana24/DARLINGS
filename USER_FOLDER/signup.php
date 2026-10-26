<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Darlings | Create account</title>
  <link rel="stylesheet" href="css/LoginPageD.css">
  <link rel="stylesheet" href="css/SignUpD.css">
  <link rel="stylesheet" href="css/footer.css">
</head>
<body>
  <main class="signup-container">
    <a class="brand-link" href="index.php">Darlings</a>
      <h2>Create account</h2>
    <p class="form-copy">Create an account, then sign in to shop.</p>
    <form id="signupForm">
      <label for="name">Full name</label>
      <input id="name" name="name" type="text" placeholder="Your name" required autocomplete="name">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" placeholder="you@example.com" required autocomplete="email">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" placeholder="At least 6 characters" minlength="6" required autocomplete="new-password">
      <label for="confirmPassword">Confirm password</label>
      <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Repeat your password" minlength="6" required autocomplete="new-password">
      <p class="form-message" id="message" role="alert" hidden></p>
      <button type="submit">Create account</button>
    </form>
    <p>Already have an account? <a href="darl_loginpage.php">Sign in</a></p>
  </main>
  <footer class="darlings-footer"><div class="footer-container"><div><h3>Darlings</h3><p>Your one-stop shop for fashion, shoes, and more.</p></div><div><h4>Quick Links</h4><ul><li><a href="index.php">Home</a></li><li><a href="products.php">Shop</a></li><li><a href="about.php">About Us</a></li></ul></div><div><h4>Customer Service</h4><ul><li><a href="products.php">FAQs</a></li><li><a href="products.php">Shipping &amp; Returns</a></li></ul></div><div><h4>Subscribe</h4><form class="newsletter" onsubmit="return false"><input type="email" placeholder="Enter your email" aria-label="Email address"><button>Subscribe</button></form></div></div><div class="footer-bottom"><p>&copy; 2026 Darlings. All rights reserved.</p><div class="socials"><a href="#">Facebook</a><a href="#">Instagram</a></div></div></footer>
  <script>
    document.getElementById('signupForm').addEventListener('submit', async function (event) {
      event.preventDefault();
      const message = document.getElementById('message');
      if (this.password.value !== this.confirmPassword.value) {
        message.textContent = 'Passwords do not match.';
        message.hidden = false;
        return;
      }
      const response = await fetch('api/auth.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({action:'signup', name:this.name.value, email:this.email.value, password:this.password.value}) });
      const data = await response.json();
      if (!response.ok) { message.textContent = data.message || 'Account creation failed.'; message.hidden = false; return; }
      window.location.href = 'darl_loginpage.php?created=1';
    });
  </script>
</body>
</html>
