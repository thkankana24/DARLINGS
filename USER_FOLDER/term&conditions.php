<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lastUpdated  = '13 October 2026';
$jurisdiction = 'Your City, State';   // <- change to the city whose courts will handle disputes
$contactEmail = 'support@darlings.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terms &amp; Conditions | Darlings</title>
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
.tc-hero{text-align:center;padding:70px 7% 50px;background:linear-gradient(180deg,var(--pink-light) 0%,var(--background) 100%)}
.tc-label{display:inline-block;font-size:12px;font-weight:700;letter-spacing:3px;color:var(--pink);margin-bottom:12px}
.tc-hero h1{font-family:"Fraunces",Georgia,serif;font-size:clamp(30px,5vw,46px);font-weight:600;margin-bottom:10px}
.tc-hero p{color:var(--muted);font-size:16px;max-width:600px;margin:0 auto}
.tc-updated{display:inline-block;margin-top:18px;padding:6px 16px;border-radius:50px;background:var(--white);border:1px solid var(--border);font-size:13px;color:var(--muted)}

/* ============ LAYOUT ============ */
.tc-layout{width:100%;max-width:1100px;margin:0 auto;padding:20px 20px 70px;display:grid;grid-template-columns:250px 1fr;gap:36px;align-items:start}

.tc-toc{position:sticky;top:100px;background:var(--white);border:1px solid var(--border);border-radius:18px;padding:22px 20px;box-shadow:var(--shadow);max-height:calc(100vh - 130px);overflow-y:auto}
.tc-toc h3{font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--pink);margin-bottom:12px}
.tc-toc a{display:block;padding:8px 12px;margin:2px 0;border-left:3px solid transparent;border-radius:0 8px 8px 0;font-size:14px;color:var(--muted);transition:.2s}
.tc-toc a:hover{color:var(--pink-dark);background:var(--pink-light)}
.tc-toc a.active{border-left-color:var(--pink);background:var(--pink-light);color:var(--pink-dark);font-weight:600}

.tc-content{display:flex;flex-direction:column;gap:22px}
.tc-section{background:var(--white);border:1px solid var(--border);border-radius:20px;padding:30px 34px;box-shadow:var(--shadow)}
.tc-section h2{font-family:"Fraunces",Georgia,serif;font-size:24px;margin-bottom:14px;display:flex;align-items:center;gap:12px}
.tc-section h2 .num{flex-shrink:0;width:34px;height:34px;display:grid;place-items:center;border-radius:50%;background:var(--pink);color:var(--white);font:700 15px "DM Sans",sans-serif}
.tc-section p{color:var(--muted);font-size:15px;line-height:1.85;margin-bottom:12px}
.tc-section ul{list-style:none;margin:6px 0 12px}
.tc-section li{position:relative;padding-left:22px;margin:9px 0;color:var(--muted);font-size:15px;line-height:1.75}
.tc-section li::before{content:"";position:absolute;left:4px;top:11px;width:8px;height:8px;border-radius:50%;background:var(--pink)}
.tc-section strong{color:var(--ink)}
.tc-section a.link{color:var(--pink-dark);font-weight:600;text-decoration:underline}

.tc-note{margin-top:12px;padding:14px 18px;border-left:4px solid var(--pink);background:var(--pink-light);border-radius:0 12px 12px 0;font-size:14px;color:var(--ink)}

.tc-contact{padding:36px 24px;text-align:center;background:var(--pink-light);border:1px solid var(--border);border-radius:20px}
.tc-contact h2{font-family:"Fraunces",Georgia,serif;font-size:26px;margin-bottom:8px}
.tc-contact p{color:var(--muted);margin-bottom:20px}
.tc-btn{display:inline-block;padding:13px 34px;background:var(--pink);color:var(--white);border-radius:50px;font-size:14px;font-weight:600;transition:.25s}
.tc-btn:hover{background:var(--pink-dark);transform:translateY(-2px)}

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
    .tc-layout{grid-template-columns:1fr}
    .tc-toc{position:static;max-height:none}
    .darlings-footer .footer-container{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
    .shop-header{padding:16px 5%}
    .shop-header nav{display:none}
    .tc-section{padding:24px 20px}
}
@media(max-width:600px){
    .tc-hero{padding:50px 20px 36px}
    .darlings-footer{padding:45px 20px 20px}
    .darlings-footer .footer-container{grid-template-columns:1fr;gap:30px}
    .darlings-footer .footer-bottom{flex-direction:column;gap:15px;text-align:center}
}
@media print{
    .shop-header,.tc-toc,.darlings-footer,.tc-contact{display:none}
    .tc-layout{display:block}
    .tc-section{box-shadow:none;border:0;padding:10px 0}
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
<section class="tc-hero">
    <span class="tc-label">PLEASE READ CAREFULLY</span>
    <h1>Terms &amp; Conditions</h1>
    <p>These terms explain the rules for using the Darlings website and buying from our store.</p>
    <span class="tc-updated">Last updated: <?= htmlspecialchars($lastUpdated) ?></span>
</section>

<div class="tc-layout">

    <!-- Table of contents -->
    <aside class="tc-toc" aria-label="Contents">
        <h3>Contents</h3>
        <a href="#acceptance">1. Acceptance of terms</a>
        <a href="#account">2. Your account</a>
        <a href="#products">3. Products &amp; availability</a>
        <a href="#pricing">4. Pricing</a>
        <a href="#orders">5. Placing &amp; cancelling orders</a>
        <a href="#payment">6. Payment</a>
        <a href="#shipping">7. Shipping, returns &amp; refunds</a>
        <a href="#conduct">8. Acceptable use</a>
        <a href="#ip">9. Intellectual property</a>
        <a href="#liability">10. Limitation of liability</a>
        <a href="#privacy">11. Privacy</a>
        <a href="#law">12. Governing law</a>
        <a href="#changes">13. Changes to terms</a>
        <a href="#contact">14. Contact us</a>
    </aside>

    <!-- Content -->
    <main class="tc-content">

        <section class="tc-section" id="acceptance">
            <h2><span class="num">1</span> Acceptance of Terms</h2>
            <p>By visiting, registering on or buying from the <strong>Darlings</strong> website, you agree to follow these Terms &amp; Conditions. If you do not agree with any part of them, please do not use the website.</p>
            <p>You must be at least 18 years old, or use the website under the supervision of a parent or guardian, to place an order.</p>
        </section>

        <section class="tc-section" id="account">
            <h2><span class="num">2</span> Your Account</h2>
            <ul>
                <li>You need an account to use the cart and place orders. Please give accurate and complete details when you sign up.</li>
                <li>You are responsible for keeping your password confidential and for everything that happens under your account.</li>
                <li>Tell us straight away if you think someone has used your account without your permission.</li>
                <li>We may suspend or close an account that gives false information or breaks these terms.</li>
            </ul>
        </section>

        <section class="tc-section" id="products">
            <h2><span class="num">3</span> Products &amp; Availability</h2>
            <p>We try to show our products, colours and sizes as accurately as possible. Colours may look slightly different on your screen, and sizes can vary a little between styles.</p>
            <p>All products are subject to availability. If an item is out of stock it cannot be added to your cart, and if an item becomes unavailable after you order, we will inform you and refund any payment you made.</p>
        </section>

        <section class="tc-section" id="pricing">
            <h2><span class="num">4</span> Pricing</h2>
            <ul>
                <li>All prices are shown in Indian Rupees (₹) and include applicable taxes unless stated otherwise.</li>
                <li>Discounts shown on a product apply only while the offer is active.</li>
                <li>Your order total is calculated from the current price in our system when you place the order, along with any shipping charges shown at checkout.</li>
                <li>If a price is shown incorrectly because of a technical or typing error, we may cancel the order and refund you in full.</li>
            </ul>
        </section>

        <section class="tc-section" id="orders">
            <h2><span class="num">5</span> Placing &amp; Cancelling Orders</h2>
            <p>When you place an order, you will receive an order number beginning with <strong>DAR</strong>. This is an offer to buy, and the order is accepted once we confirm and process it.</p>
            <ul>
                <li>You can follow your order status under <a class="link" href="orders.php">My Orders</a>. Common statuses are Pending, Delivered and Cancelled.</li>
                <li>You may ask to cancel an order while it is still <strong>Pending</strong>. Once it has been shipped, it can no longer be cancelled, but it can be returned after delivery under our return policy.</li>
                <li>We may cancel an order if an item is unavailable, payment fails, the address cannot be served, or we suspect fraud. Any amount paid will be refunded.</li>
            </ul>
        </section>

        <section class="tc-section" id="payment">
            <h2><span class="num">6</span> Payment</h2>
            <p>We offer Cash on Delivery, UPI / QR code and Credit or Debit card at checkout.</p>
            <ul>
                <li>For Cash on Delivery, please pay the full amount to the delivery partner when your order arrives.</li>
                <li>Online payments must be made using a card or account that you are authorised to use.</li>
                <li>Your payment status stays <strong>Pending</strong> until the payment is confirmed.</li>
            </ul>
            <div class="tc-note">Repeated refusal to accept Cash on Delivery orders may lead us to disable that payment option on your account.</div>
        </section>

        <section class="tc-section" id="shipping">
            <h2><span class="num">7</span> Shipping, Returns &amp; Refunds</h2>
            <p>Delivery times, shipping charges, the return window, exchange rules and refund timelines are explained on our <a class="link" href="shipping%26returns.php">Shipping &amp; Returns</a> page, which forms part of these terms.</p>
            <p>Please check that your name, phone number, address and 6-digit pincode are correct at checkout. We are not responsible for delays or failed deliveries caused by incorrect details.</p>
        </section>

        <section class="tc-section" id="conduct">
            <h2><span class="num">8</span> Acceptable Use</h2>
            <p>When using the website, you agree not to:</p>
            <ul>
                <li>Use the website for any unlawful or fraudulent purpose.</li>
                <li>Try to gain unauthorised access to our systems, admin area, other accounts or data.</li>
                <li>Upload viruses, scrape the site in bulk, or interfere with how it works.</li>
                <li>Place orders using false names, addresses or payment details.</li>
                <li>Copy or resell our products, images or content without permission.</li>
            </ul>
        </section>

        <section class="tc-section" id="ip">
            <h2><span class="num">9</span> Intellectual Property</h2>
            <p>The Darlings name, logo, product photos, designs, text and layout belong to Darlings or its licensors and are protected by copyright and trademark laws. You may view and print pages for your personal use, but you may not copy, modify, distribute or use our content commercially without our written permission.</p>
        </section>

        <section class="tc-section" id="liability">
            <h2><span class="num">10</span> Limitation of Liability</h2>
            <p>We work hard to keep the website running smoothly, but we provide it on an "as is" and "as available" basis. We do not promise that it will always be uninterrupted or free of errors.</p>
            <p>To the extent permitted by law, Darlings is not liable for indirect or consequential losses arising from the use of the website, or for delays caused by events outside our control, such as courier delays, strikes, natural events or technical failures. Our total liability for any order will not exceed the amount you paid for that order.</p>
            <p>Nothing in these terms limits your rights under applicable consumer protection laws.</p>
        </section>

        <section class="tc-section" id="privacy">
            <h2><span class="num">11</span> Privacy</h2>
            <p>How we collect, use and protect your personal information is described in our <a class="link" href="privacy-policy.php">Privacy Policy</a>. By using the website, you also agree to that policy.</p>
        </section>

        <section class="tc-section" id="law">
            <h2><span class="num">12</span> Governing Law</h2>
            <p>These terms are governed by the laws of India. Any dispute that cannot be resolved by talking to us first will be subject to the exclusive jurisdiction of the courts at <strong><?= htmlspecialchars($jurisdiction) ?></strong>.</p>
        </section>

        <section class="tc-section" id="changes">
            <h2><span class="num">13</span> Changes to These Terms</h2>
            <p>We may update these terms from time to time. The "Last updated" date at the top shows when they were last changed. Orders are governed by the terms in force at the time you place them, and continuing to use the website after an update means you accept the new terms.</p>
        </section>

        <section class="tc-contact" id="contact">
            <h2>Questions about these terms?</h2>
            <p>We are happy to explain anything that is unclear.</p>
            <a href="mailto:<?= htmlspecialchars($contactEmail) ?>" class="tc-btn"><?= htmlspecialchars($contactEmail) ?></a>
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
                <li><a href="privacy_policy.php">Privacy Policy</a></li>
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
    const links = [...document.querySelectorAll('.tc-toc a')];
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