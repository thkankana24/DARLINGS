<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lastUpdated = '13 October 2026';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy | Darlings</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

<style>
/* ============ BASE ============ */
:root{
    --pink:#e75480;
    --pink-dark:#c93c69;
    --pink-light:#fff0f5;
    --pink-soft:#fce4ec;
    --white:#fff;
    --ink:#30232a;
    --muted:#81747b;
    --border:#f0dfe6;
    --background:#fffafb;
    --shadow:0 8px 30px rgba(133,60,86,.08);
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:100px}
body{font-family:"DM Sans",Arial,sans-serif;background:var(--background);color:var(--ink);line-height:1.6;display:flex;flex-direction:column;min-height:100vh}
a{text-decoration:none;color:inherit}

/* ============ HEADER ============ */
.shop-header{position:sticky;top:0;z-index:100;display:flex;align-items:center;justify-content:space-between;gap:24px;padding:20px 7%;background:rgba(255,255,255,.96);border-bottom:1px solid var(--border);backdrop-filter:blur(12px)}
.brand{font-family:"Fraunces",serif;font-size:32px;font-weight:600;font-style:italic;color:var(--pink);letter-spacing:-1px}
.shop-header nav{display:flex;align-items:center;gap:35px}
.shop-header nav a{font-size:14px;font-weight:600;transition:color .25s}
.shop-header nav a:hover{color:var(--pink)}
.cart-link{padding:10px 24px;border:1px solid var(--pink);border-radius:50px;color:var(--pink-dark);font-size:14px;font-weight:600;transition:.25s}
.cart-link:hover{background:var(--pink);color:var(--white)}

/* ============ HERO ============ */
.pp-hero{text-align:center;padding:70px 7% 50px;background:linear-gradient(180deg,var(--pink-light) 0%,var(--background) 100%)}
.pp-label{display:inline-block;font-size:12px;font-weight:700;letter-spacing:3px;color:var(--pink);margin-bottom:12px}
.pp-hero h1{font-family:"Fraunces",Georgia,serif;font-size:clamp(30px,5vw,46px);font-weight:600;margin-bottom:10px}
.pp-hero p{color:var(--muted);font-size:16px;max-width:580px;margin:0 auto}
.pp-updated{display:inline-block;margin-top:18px;padding:6px 16px;border-radius:50px;background:var(--white);border:1px solid var(--border);font-size:13px;color:var(--muted)}

/* ============ LAYOUT ============ */
.pp-layout{width:100%;max-width:1100px;margin:0 auto;padding:20px 20px 70px;display:grid;grid-template-columns:240px 1fr;gap:36px;align-items:start}

/* Table of contents */
.pp-toc{position:sticky;top:100px;background:var(--white);border:1px solid var(--border);border-radius:18px;padding:22px 20px;box-shadow:var(--shadow)}
.pp-toc h3{font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--pink);margin-bottom:12px}
.pp-toc a{display:block;padding:8px 12px;margin:2px 0;border-left:3px solid transparent;border-radius:0 8px 8px 0;font-size:14px;color:var(--muted);transition:.2s}
.pp-toc a:hover{color:var(--pink-dark);background:var(--pink-light)}
.pp-toc a.active{border-left-color:var(--pink);background:var(--pink-light);color:var(--pink-dark);font-weight:600}

/* Sections */
.pp-content{display:flex;flex-direction:column;gap:22px}
.pp-section{background:var(--white);border:1px solid var(--border);border-radius:20px;padding:30px 34px;box-shadow:var(--shadow)}
.pp-section h2{font-family:"Fraunces",Georgia,serif;font-size:24px;margin-bottom:14px;display:flex;align-items:center;gap:12px}
.pp-section h2 .num{flex-shrink:0;width:34px;height:34px;display:grid;place-items:center;border-radius:50%;background:var(--pink);color:var(--white);font:700 15px "DM Sans",sans-serif}
.pp-section h3{font-size:16px;margin:18px 0 8px}
.pp-section p{color:var(--muted);font-size:15px;line-height:1.85;margin-bottom:12px}
.pp-section ul{list-style:none;margin:6px 0 12px}
.pp-section li{position:relative;padding-left:22px;margin:9px 0;color:var(--muted);font-size:15px;line-height:1.75}
.pp-section li::before{content:"";position:absolute;left:4px;top:11px;width:8px;height:8px;border-radius:50%;background:var(--pink)}
.pp-section strong{color:var(--ink)}
.pp-section a.link{color:var(--pink-dark);font-weight:600;text-decoration:underline}

.pp-table-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:14px;margin:8px 0 12px}
.pp-table{width:100%;border-collapse:collapse;font-size:14px;min-width:480px}
.pp-table th{background:var(--pink-soft);text-align:left;padding:13px 18px;font-weight:700}
.pp-table td{padding:13px 18px;border-top:1px solid var(--border);color:var(--muted);vertical-align:top}
.pp-table td:first-child{color:var(--ink);font-weight:600;white-space:nowrap}

.pp-note{margin-top:12px;padding:14px 18px;border-left:4px solid var(--pink);background:var(--pink-light);border-radius:0 12px 12px 0;font-size:14px;color:var(--ink)}

.pp-contact{padding:36px 24px;text-align:center;background:var(--pink-light);border:1px solid var(--border);border-radius:20px}
.pp-contact h2{font-family:"Fraunces",Georgia,serif;font-size:26px;margin-bottom:8px}
.pp-contact p{color:var(--muted);margin-bottom:20px}
.pp-btn{display:inline-block;padding:13px 34px;background:var(--pink);color:var(--white);border-radius:50px;font-size:14px;font-weight:600;transition:.25s}
.pp-btn:hover{background:var(--pink-dark);transform:translateY(-2px)}

/* ============ FOOTER ============ */
.darlings-footer{margin-top:auto;background:linear-gradient(135deg,#903250 0%,#3d5a4e 55%,#31483f 100%);color:#f7f2f2;padding:65px 35px 20px}
.darlings-footer .footer-container{max-width:1200px;margin:auto;display:grid;grid-template-columns:1.5fr 1fr 1.2fr 1.4fr;gap:45px;margin-bottom:45px}
.darlings-footer h3{font-family:Georgia,serif;font-size:30px;color:#fff;margin:0 0 15px}
.darlings-footer h4{color:#fff;font-size:15px;text-transform:uppercase;letter-spacing:1px;margin:0 0 20px}
.darlings-footer p{color:rgba(255,255,255,.72);font-size:14px;line-height:1.8;max-width:280px}
.darlings-footer ul{list-style:none}
.darlings-footer li{margin:11px 0}
.darlings-footer li a,.darlings-footer .socials a{color:rgba(255,255,255,.72);font-size:14px;transition:.3s}
.darlings-footer li a:hover{color:#f3b7c6;padding-left:5px}
.darlings-footer .newsletter{display:flex;background:rgba(255,255,255,.1);padding:5px;border-radius:50px;border:1px solid rgba(255,255,255,.12)}
.darlings-footer .newsletter input{min-width:0;flex:1;padding:12px 15px;background:transparent;border:0;outline:0;color:#fff;font-size:13px}
.darlings-footer .newsletter input::placeholder{color:rgba(255,255,255,.55)}
.darlings-footer .newsletter button{padding:11px 18px;background:#e79bad;border:0;border-radius:50px;color:#fff;font-size:12px;font-weight:600;cursor:pointer}
.darlings-footer .footer-bottom{max-width:1200px;margin:auto;padding-top:22px;border-top:1px solid rgba(255,255,255,.15);display:flex;justify-content:space-between;align-items:center;font-size:13px}
.darlings-footer .footer-bottom p{max-width:none}
.darlings-footer .socials{display:flex;gap:20px}
.darlings-footer .socials a:hover{color:#f3b7c6}

/* ============ RESPONSIVE ============ */
@media(max-width:991px){
    .pp-layout{grid-template-columns:1fr}
    .pp-toc{position:static}
    .darlings-footer .footer-container{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
    .shop-header{padding:16px 5%}
    .shop-header nav{display:none}
    .pp-section{padding:24px 20px}
}
@media(max-width:600px){
    .pp-hero{padding:50px 20px 36px}
    .darlings-footer{padding:45px 20px 20px}
    .darlings-footer .footer-container{grid-template-columns:1fr;gap:30px}
    .darlings-footer .footer-bottom{flex-direction:column;gap:15px;text-align:center}
}
@media print{
    .shop-header,.pp-toc,.darlings-footer,.pp-contact{display:none}
    .pp-layout{display:block}
    .pp-section{box-shadow:none;border:0;padding:10px 0}
}
</style>
</head>
<body>

<!-- ============ HEADER ============ -->
<header class="shop-header">
    <a class="brand" href="index.php">Darlings</a>
    <nav aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About us</a>
    </nav>
    <a class="cart-link" href="cart.php">Cart</a>
</header>

<!-- ============ HERO ============ -->
<section class="pp-hero">
    <span class="pp-label">YOUR PRIVACY MATTERS</span>
    <h1>Privacy Policy</h1>
    <p>This policy explains what information Darlings collects, why we collect it, and how we keep it safe.</p>
    <span class="pp-updated">Last updated: <?= htmlspecialchars($lastUpdated) ?></span>
</section>

<div class="pp-layout">

    <!-- Table of contents -->
    <aside class="pp-toc" aria-label="Contents">
        <h3>Contents</h3>
        <a href="#overview">1. Overview</a>
        <a href="#collect">2. Information we collect</a>
        <a href="#use">3. How we use it</a>
        <a href="#share">4. Sharing your information</a>
        <a href="#cookies">5. Cookies &amp; sessions</a>
        <a href="#security">6. Data security</a>
        <a href="#retention">7. Data retention</a>
        <a href="#rights">8. Your rights</a>
        <a href="#children">9. Children's privacy</a>
        <a href="#changes">10. Policy changes</a>
        <a href="#contact">11. Contact us</a>
    </aside>

    <!-- Content -->
    <main class="pp-content">

        <section class="pp-section" id="overview">
            <h2><span class="num">1</span> Overview</h2>
            <p>Welcome to <strong>Darlings</strong>. We respect your privacy and are committed to protecting the personal information you share with us when you browse our website, create an account or place an order.</p>
            <p>By using our website, you agree to the practices described in this policy. If you do not agree, please do not use the website.</p>
        </section>

        <section class="pp-section" id="collect">
            <h2><span class="num">2</span> Information We Collect</h2>
            <p>We only collect the information we need to run our store and deliver your orders.</p>
            <div class="pp-table-wrap">
                <table class="pp-table">
                    <thead><tr><th>When</th><th>What we collect</th></tr></thead>
                    <tbody>
                        <tr><td>You create an account</td><td>Your name, email address and password. Your password is stored in encrypted (hashed) form, so we cannot read it.</td></tr>
                        <tr><td>You place an order</td><td>Name, email, phone number, delivery address, city, pincode, the items you ordered, order total and the payment method you chose.</td></tr>
                        <tr><td>You shop</td><td>Items, sizes and quantities in your cart, linked to your browsing session.</td></tr>
                        <tr><td>You subscribe</td><td>Your email address for our newsletter.</td></tr>
                        <tr><td>You contact us</td><td>Your email address and the contents of your message.</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="pp-note">We do not store your full card number, CVV or UPI PIN on our servers. Online payments, when enabled, are handled by a secure third-party payment provider.</div>
        </section>

        <section class="pp-section" id="use">
            <h2><span class="num">3</span> How We Use Your Information</h2>
            <ul>
                <li><strong>To process orders:</strong> confirm, pack, ship and deliver your purchases.</li>
                <li><strong>To manage your account:</strong> let you log in, view your orders and keep your cart.</li>
                <li><strong>To communicate with you:</strong> send order updates and reply to your questions.</li>
                <li><strong>To improve our store:</strong> understand which products and pages are popular so we can make shopping better.</li>
                <li><strong>To send offers:</strong> share news and promotions if you have subscribed. You can unsubscribe at any time.</li>
                <li><strong>To stay safe:</strong> prevent fraud, misuse and unauthorised access.</li>
            </ul>
            <p>We do <strong>not</strong> sell your personal information to anyone.</p>
        </section>

        <section class="pp-section" id="share">
            <h2><span class="num">4</span> Sharing Your Information</h2>
            <p>We share your information only when it is needed to serve you:</p>
            <ul>
                <li><strong>Courier and delivery partners:</strong> your name, phone number and address, so your order can reach you.</li>
                <li><strong>Payment providers:</strong> the details required to complete an online payment securely.</li>
                <li><strong>Service providers:</strong> trusted tools that help us run the website, such as hosting, fonts and email services.</li>
                <li><strong>Legal authorities:</strong> when required by law, court order or to protect our rights and users.</li>
            </ul>
            <p>These partners may only use your information to perform their services for us.</p>
        </section>

        <section class="pp-section" id="cookies">
            <h2><span class="num">5</span> Cookies &amp; Sessions</h2>
            <p>Our website uses a session cookie to remember you while you shop. It keeps you logged in and keeps your cart items together as you move between pages. Without it, the cart and checkout would not work.</p>
            <p>Some pages also load fonts, icons and scripts from external services (such as Google Fonts, Bootstrap and Font Awesome), which may receive your IP address and browser details when the page loads.</p>
            <p>You can delete or block cookies in your browser settings, but parts of the website, such as the cart and checkout, may stop working.</p>
        </section>

        <section class="pp-section" id="security">
            <h2><span class="num">6</span> Data Security</h2>
            <p>We take reasonable steps to protect your information, including:</p>
            <ul>
                <li>Storing account passwords in hashed form only.</li>
                <li>Restricting access to customer and order data to authorised store staff.</li>
                <li>Using secure, access-controlled admin tools to manage orders.</li>
            </ul>
            <p>No website or online service can be guaranteed to be 100% secure. Please choose a strong, unique password and never share it with anyone.</p>
        </section>

        <section class="pp-section" id="retention">
            <h2><span class="num">7</span> Data Retention</h2>
            <p>We keep your account and order information for as long as your account is active and as long as needed to deliver orders, handle returns, resolve disputes and meet legal, tax and accounting requirements. When information is no longer needed, we delete it or anonymise it.</p>
        </section>

        <section class="pp-section" id="rights">
            <h2><span class="num">8</span> Your Rights</h2>
            <p>You are in control of your personal information. You can ask us to:</p>
            <ul>
                <li>Show you the information we hold about you.</li>
                <li>Correct anything that is wrong or out of date.</li>
                <li>Delete your account and personal data, where we are not required to keep it by law.</li>
                <li>Stop sending you marketing emails.</li>
            </ul>
            <p>To make a request, <a class="link" href="#contact">contact us</a> using the email address registered with your account. We may need to verify your identity before acting on a request.</p>
        </section>

        <section class="pp-section" id="children">
            <h2><span class="num">9</span> Children's Privacy</h2>
            <p>Our website is intended for adults. We do not knowingly collect personal information from children under 18 without the consent of a parent or guardian. If you believe a child has given us their information, please contact us and we will remove it.</p>
        </section>

        <section class="pp-section" id="changes">
            <h2><span class="num">10</span> Changes to This Policy</h2>
            <p>We may update this policy from time to time. When we do, we will change the "Last updated" date at the top of this page. We encourage you to review it regularly. Continuing to use the website after changes means you accept the updated policy.</p>
        </section>

        <section class="pp-contact" id="contact">
            <h2>Questions about your privacy?</h2>
            <p>Write to us and we will be happy to help.</p>
            <a href="mailto:support@darlings.com" class="pp-btn">support@darlings.com</a>
        </section>
    </main>
</div>

<!-- ============ FOOTER ============ -->
<footer class="darlings-footer">
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
                <li><a href="shipping&returns.php">Shipping &amp; Returns</a></li>
                <li><a href="privacypolicy.php">Privacy Policy</a></li>
                <li><a href="term&conditions.php">Terms &amp; Conditions</a></li>
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

<!-- ============ SCRIPT ============ -->
<script>
(() => {
    // Highlight the current section in the contents list while scrolling
    const links = [...document.querySelectorAll('.pp-toc a')];
    const sections = links.map(a => document.querySelector(a.getAttribute('href'))).filter(Boolean);

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                links.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + entry.target.id));
            }
        });
    }, { rootMargin: '-110px 0px -65% 0px' });

    sections.forEach(s => observer.observe(s));
})();
</script>
</body>
</html>