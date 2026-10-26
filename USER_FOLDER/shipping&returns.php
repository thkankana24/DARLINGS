<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shipping &amp; Returns | Darlings</title>
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
html{scroll-behavior:smooth;scroll-padding-top:90px}
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
.sr-hero{text-align:center;padding:70px 7% 50px;background:linear-gradient(180deg,var(--pink-light) 0%,var(--background) 100%)}
.sr-label{display:inline-block;font-size:12px;font-weight:700;letter-spacing:3px;color:var(--pink);margin-bottom:12px}
.sr-hero h1{font-family:"Fraunces",Georgia,serif;font-size:clamp(30px,5vw,46px);font-weight:600;margin-bottom:10px}
.sr-hero p{color:var(--muted);font-size:16px;max-width:560px;margin:0 auto}

/* ============ QUICK INFO CARDS ============ */
.sr-wrap{width:100%;max-width:1000px;margin:0 auto;padding:0 20px}
.sr-highlights{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:-10px}
.sr-card{background:var(--white);border:1px solid var(--border);border-radius:18px;padding:24px 18px;text-align:center;box-shadow:var(--shadow);transition:transform .25s}
.sr-card:hover{transform:translateY(-4px)}
.sr-card .ico{width:48px;height:48px;margin:0 auto 12px;display:grid;place-items:center;border-radius:50%;background:var(--pink-soft);font-size:22px}
.sr-card strong{display:block;font-size:20px;color:var(--pink-dark)}
.sr-card span{font-size:13px;color:var(--muted)}

/* ============ TAB SWITCH ============ */
.sr-tabs{display:flex;justify-content:center;gap:10px;margin:44px 0 30px}
.sr-tab{padding:11px 30px;border:1px solid var(--border);border-radius:50px;background:var(--white);color:var(--ink);font:600 14px "DM Sans",sans-serif;cursor:pointer;transition:.25s}
.sr-tab:hover{border-color:var(--pink);color:var(--pink-dark)}
.sr-tab.active{background:var(--pink);border-color:var(--pink);color:var(--white)}
.sr-panel{display:none}
.sr-panel.active{display:block;animation:fade .35s ease}
@keyframes fade{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}

/* ============ SECTIONS ============ */
.sr-section{background:var(--white);border:1px solid var(--border);border-radius:20px;padding:30px 32px;margin-bottom:22px;box-shadow:var(--shadow)}
.sr-section h2{font-family:"Fraunces",Georgia,serif;font-size:24px;margin-bottom:6px}
.sr-section .sub{color:var(--muted);font-size:14px;margin-bottom:18px}
.sr-section p{color:var(--muted);font-size:15px;line-height:1.8;margin-bottom:10px}
.sr-section a.link{color:var(--pink-dark);font-weight:600;text-decoration:underline}

/* Tables */
.sr-table-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:14px}
.sr-table{width:100%;border-collapse:collapse;font-size:14px;min-width:460px}
.sr-table th{background:var(--pink-soft);color:var(--ink);text-align:left;padding:13px 18px;font-weight:700}
.sr-table td{padding:13px 18px;border-top:1px solid var(--border);color:var(--muted)}
.sr-table tr:hover td{background:var(--pink-light)}

/* Steps */
.sr-steps{list-style:none;counter-reset:step;display:grid;gap:16px;margin-top:6px}
.sr-steps li{counter-increment:step;position:relative;padding-left:56px;min-height:40px}
.sr-steps li::before{content:counter(step);position:absolute;left:0;top:0;width:38px;height:38px;display:grid;place-items:center;border-radius:50%;background:var(--pink);color:var(--white);font-weight:700}
.sr-steps strong{display:block;color:var(--ink);font-size:15px}
.sr-steps span{color:var(--muted);font-size:14px}

/* Eligible / Not eligible */
.sr-two{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.sr-box{border-radius:14px;padding:20px 22px;border:1px solid var(--border)}
.sr-box.yes{background:#f4fbf6;border-color:#cfe9d6}
.sr-box.no{background:var(--pink-light);border-color:#f6cfdb}
.sr-box h3{font-size:16px;margin-bottom:10px}
.sr-box ul{list-style:none}
.sr-box li{position:relative;padding-left:24px;margin:8px 0;font-size:14px;color:var(--muted)}
.sr-box.yes li::before{content:"✓";position:absolute;left:0;color:#2e9e57;font-weight:700}
.sr-box.no li::before{content:"✕";position:absolute;left:0;color:var(--pink-dark);font-weight:700}

/* Note */
.sr-note{margin-top:14px;padding:14px 18px;border-left:4px solid var(--pink);background:var(--pink-light);border-radius:0 12px 12px 0;font-size:14px;color:var(--ink)}

/* Contact box */
.sr-contact{margin:30px 0 70px;padding:40px 24px;text-align:center;background:var(--pink-light);border:1px solid var(--border);border-radius:20px}
.sr-contact h2{font-family:"Fraunces",Georgia,serif;font-size:26px;margin-bottom:8px}
.sr-contact p{color:var(--muted);margin-bottom:20px}
.sr-btn{display:inline-block;padding:13px 34px;background:var(--pink);color:var(--white);border-radius:50px;font-size:14px;font-weight:600;transition:.25s}
.sr-btn:hover{background:var(--pink-dark);transform:translateY(-2px)}

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
    .sr-highlights{grid-template-columns:1fr 1fr}
    .darlings-footer .footer-container{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
    .shop-header{padding:16px 5%}
    .shop-header nav{display:none}
    .sr-two{grid-template-columns:1fr}
    .sr-section{padding:24px 20px}
}
@media(max-width:600px){
    .sr-hero{padding:50px 20px 36px}
    .darlings-footer{padding:45px 20px 20px}
    .darlings-footer .footer-container{grid-template-columns:1fr;gap:30px}
    .darlings-footer .footer-bottom{flex-direction:column;gap:15px;text-align:center}
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
<section class="sr-hero">
    <span class="sr-label">CUSTOMER SERVICE</span>
    <h1>Shipping &amp; Returns</h1>
    <p>Everything you need to know about delivery, returns, exchanges and refunds at Darlings.</p>
</section>

<main class="sr-wrap">

    <!-- Quick info -->
    <div class="sr-highlights">
        <div class="sr-card"><div class="ico">🚚</div><strong>4–7 Days</strong><span>Standard delivery</span></div>
        <div class="sr-card"><div class="ico">🎁</div><strong>₹999+</strong><span>Free shipping above</span></div>
        <div class="sr-card"><div class="ico">↩️</div><strong>7 Days</strong><span>Easy returns</span></div>
        <div class="sr-card"><div class="ico">💸</div><strong>5–7 Days</strong><span>Refund processing</span></div>
    </div>

    <!-- Tabs -->
    <div class="sr-tabs" role="tablist">
        <button class="sr-tab active" data-target="shipping" role="tab">Shipping</button>
        <button class="sr-tab" data-target="returns" role="tab">Returns &amp; Refunds</button>
    </div>

    <!-- ================= SHIPPING ================= -->
    <div class="sr-panel active" id="shipping">

        <section class="sr-section">
            <h2>Delivery Time &amp; Charges</h2>
            <p class="sub">Delivery time depends on your city and pincode. Orders are processed within 1–2 working days.</p>
            <div class="sr-table-wrap">
                <table class="sr-table">
                    <thead><tr><th>Delivery Zone</th><th>Estimated Time</th><th>Shipping Charge</th></tr></thead>
                    <tbody>
                        <tr><td>Metro cities (Kolkata, Delhi, Mumbai, Chennai, Bengaluru)</td><td>3–5 working days</td><td>₹49 (Free above ₹999)</td></tr>
                        <tr><td>Other major cities</td><td>4–6 working days</td><td>₹59 (Free above ₹999)</td></tr>
                        <tr><td>Rest of India</td><td>5–8 working days</td><td>₹79 (Free above ₹999)</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="sr-note">Delivery times are estimates and may be affected by festivals, weather or courier delays.</div>
        </section>

        <section class="sr-section">
            <h2>How Your Order Reaches You</h2>
            <p class="sub">From checkout to your doorstep in four simple steps.</p>
            <ol class="sr-steps">
                <li><strong>Order placed</strong><span>You receive an order number starting with DAR once checkout is complete.</span></li>
                <li><strong>Order processed</strong><span>We check, pack and prepare your items within 1–2 working days.</span></li>
                <li><strong>Shipped</strong><span>Your parcel is handed over to our courier partner.</span></li>
                <li><strong>Delivered</strong><span>Your order arrives at the address you entered at checkout.</span></li>
            </ol>
        </section>

        <section class="sr-section">
            <h2>Tracking Your Order</h2>
            <p>Log in and open <a class="link" href="orders.php">My Orders</a> to see the current status of every order you have placed.</p>
            <p>Please make sure your phone number, address and 6-digit pincode are correct at checkout so the courier can reach you without delay.</p>
        </section>

        <section class="sr-section">
            <h2>Cash on Delivery</h2>
            <p>Cash on Delivery is available on eligible pincodes. Please keep the exact amount ready when the delivery partner arrives. UPI and card payments can also be selected at checkout.</p>
        </section>
    </div>

    <!-- ================= RETURNS ================= -->
    <div class="sr-panel" id="returns">

        <section class="sr-section">
            <h2>Return Policy</h2>
            <p class="sub">You can request a return within 7 days of delivery.</p>
            <div class="sr-two">
                <div class="sr-box yes">
                    <h3>Eligible for return</h3>
                    <ul>
                        <li>Unused and unwashed items</li>
                        <li>Original tags and packaging intact</li>
                        <li>Wrong item delivered</li>
                        <li>Damaged or defective on arrival</li>
                    </ul>
                </div>
                <div class="sr-box no">
                    <h3>Not eligible for return</h3>
                    <ul>
                        <li>Items worn, washed or altered</li>
                        <li>Missing tags or original packaging</li>
                        <li>Innerwear and accessories (hygiene reasons)</li>
                        <li>Items marked Final Sale</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="sr-section">
            <h2>How to Return an Item</h2>
            <p class="sub">Follow these steps to start a return.</p>
            <ol class="sr-steps">
                <li><strong>Contact us within 7 days</strong><span>Email us your order number and the reason for the return.</span></li>
                <li><strong>Get approval</strong><span>Our team reviews your request and confirms the pickup or return address.</span></li>
                <li><strong>Pack the item</strong><span>Place it in its original packaging with all tags attached.</span></li>
                <li><strong>We inspect &amp; refund</strong><span>Once the item reaches us and passes inspection, your refund is processed.</span></li>
            </ol>
        </section>

        <section class="sr-section">
            <h2>Refunds</h2>
            <p class="sub">Refunds are sent after the returned item has been received and checked.</p>
            <div class="sr-table-wrap">
                <table class="sr-table">
                    <thead><tr><th>Payment Method</th><th>Refund To</th><th>Time</th></tr></thead>
                    <tbody>
                        <tr><td>Credit / Debit Card</td><td>Original card</td><td>5–7 working days</td></tr>
                        <tr><td>UPI / QR Code</td><td>Original UPI account</td><td>3–5 working days</td></tr>
                        <tr><td>Cash on Delivery</td><td>Bank account or UPI ID you share with us</td><td>5–7 working days</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="sr-note">Shipping charges are non-refundable unless the item was wrong or damaged.</div>
        </section>

        <section class="sr-section">
            <h2>Exchanges</h2>
            <p>Need a different size or colour? Contact us within 7 days of delivery and we will arrange an exchange, subject to stock availability. If the new item is unavailable, we will issue a refund instead.</p>
        </section>

        <section class="sr-section">
            <h2>Wrong, Damaged or Missing Items</h2>
            <p>If your parcel arrives damaged or you received the wrong item, email us within 48 hours of delivery with your order number and clear photos. We will send a replacement or refund at no extra cost to you.</p>
        </section>
    </div>

    <!-- Contact -->
    <section class="sr-contact">
        <h2>Need help with an order?</h2>
        <p>Our team is happy to help with shipping, returns and refunds.</p>
        <a href="mailto:support@darlings.com" class="sr-btn">Email Us</a>
    </section>
</main>

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
                <li><a href="#">Contact</a></li>
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
    const tabs = document.querySelectorAll('.sr-tab');
    const panels = document.querySelectorAll('.sr-panel');

    function show(id) {
        tabs.forEach(t => t.classList.toggle('active', t.dataset.target === id));
        panels.forEach(p => p.classList.toggle('active', p.id === id));
    }

    tabs.forEach(tab => tab.addEventListener('click', () => {
        show(tab.dataset.target);
        history.replaceState(null, '', '#' + tab.dataset.target);
    }));

    // Open the right tab from the URL (e.g. shipping%26returns.php#returns)
    const hash = location.hash.replace('#', '');
    if (hash === 'returns' || hash === 'shipping') show(hash);
})();
</script>
</body>
</html>