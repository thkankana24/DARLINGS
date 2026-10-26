<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <style>
        /* ===== FAQ PAGE (uses :root variables from products.css) ===== */

.faq-hero {
    text-align: center;
    padding: 70px 7% 50px;
    background: linear-gradient(180deg, var(--pink-light) 0%, var(--background) 100%);
}

.faq-label {
    display: inline-block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 3px;
    color: var(--pink);
    margin-bottom: 12px;
}

.faq-hero h1 {
    font-family: "Fraunces", Georgia, serif;
    font-size: clamp(30px, 5vw, 46px);
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 10px;
}

.faq-hero p {
    color: var(--muted);
    font-size: 16px;
    margin-bottom: 28px;
}

.faq-search {
    max-width: 520px;
    margin: 0 auto;
}

.faq-search input {
    width: 100%;
    padding: 15px 22px;
    border: 1px solid var(--border);
    border-radius: 50px;
    background: var(--white);
    color: var(--ink);
    font-size: 15px;
    outline: none;
    box-shadow: var(--shadow);
    transition: border-color .25s, box-shadow .25s;
}

.faq-search input:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 4px rgba(231, 84, 128, .12);
}

/* Layout */
.faq-main {
    max-width: 820px;
    margin: 0 auto;
    padding: 20px 20px 80px;
}

/* Category tabs */
.faq-tabs {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 32px;
}

.faq-tab {
    padding: 9px 22px;
    border: 1px solid var(--border);
    border-radius: 50px;
    background: var(--white);
    color: var(--ink);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .25s;
}

.faq-tab:hover {
    border-color: var(--pink);
    color: var(--pink-dark);
}

.faq-tab.active {
    background: var(--pink);
    border-color: var(--pink);
    color: var(--white);
}

/* Accordion */
.faq-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.faq-item {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: border-color .25s;
}

.faq-item[hidden] {
    display: none;
}

.faq-item.open {
    border-color: var(--pink);
}

.faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    background: none;
    border: 0;
    color: var(--ink);
    font-size: 16px;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
}

.faq-question:hover {
    color: var(--pink-dark);
}

.faq-question:focus-visible {
    outline: 2px solid var(--pink);
    outline-offset: -2px;
}

.faq-icon {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--pink-soft);
    color: var(--pink-dark);
    font-size: 20px;
    line-height: 1;
    transition: transform .3s, background .3s, color .3s;
}

.faq-item.open .faq-icon {
    transform: rotate(45deg);
    background: var(--pink);
    color: var(--white);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease;
}

.faq-answer p {
    padding: 0 24px 22px;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.8;
}

.faq-answer a {
    color: var(--pink-dark);
    font-weight: 600;
    text-decoration: underline;
}

/* Empty state */
.faq-empty {
    text-align: center;
    margin-top: 30px;
    color: var(--muted);
}

/* Contact box */
.faq-contact {
    margin-top: 60px;
    padding: 40px 24px;
    text-align: center;
    background: var(--pink-light);
    border: 1px solid var(--border);
    border-radius: 20px;
}

.faq-contact h2 {
    font-family: "Fraunces", Georgia, serif;
    font-size: 26px;
    margin-bottom: 8px;
}

.faq-contact p {
    color: var(--muted);
    margin-bottom: 20px;
}

.faq-btn {
    display: inline-block;
    padding: 13px 34px;
    background: var(--pink);
    color: var(--white);
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    transition: background .25s, transform .25s;
}

.faq-btn:hover {
    background: var(--pink-dark);
    transform: translateY(-2px);
}

@media (max-width: 600px) {
    .faq-hero { padding: 50px 20px 36px; }
    .faq-question { padding: 17px 18px; font-size: 15px; }
    .faq-answer p { padding: 0 18px 18px; }
}
</style>
        }
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs | Darlings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,wght@1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/faq.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<!-- Header -->
<header class="shop-header">
    <a class="brand" href="index.php">Darlings</a>
    <nav aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About us</a>
    </nav>
    <a class="cart-link" href="cart.php">Cart</a>
</header>

<!-- Hero -->
<section class="faq-hero">
    <span class="faq-label">HELP CENTRE</span>
    <h1>Frequently Asked Questions</h1>
    <p>Quick answers about orders, delivery, returns and payments.</p>
    <div class="faq-search">
        <input type="search" id="faqSearch" placeholder="Search your question..." aria-label="Search FAQs">
    </div>
</section>

<!-- FAQ -->
<main class="faq-main">
    <div class="faq-tabs" role="tablist" aria-label="FAQ categories">
        <button class="faq-tab active" data-cat="all">All</button>
        <button class="faq-tab" data-cat="orders">Orders</button>
        <button class="faq-tab" data-cat="shipping">Shipping</button>
        <button class="faq-tab" data-cat="returns">Returns</button>
        <button class="faq-tab" data-cat="payments">Payments</button>
        <button class="faq-tab" data-cat="account">Account</button>
    </div>

    <div class="faq-list" id="faqList">

        <!-- ORDERS -->
        <div class="faq-item" data-cat="orders">
            <button class="faq-question" aria-expanded="false">
                How do I place an order?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Browse our products, choose your size and quantity, and click Add to Cart. Open your cart, review the items, then go to Checkout and enter your delivery details. You need to be logged in to complete your order.</p></div>
        </div>

        <div class="faq-item" data-cat="orders">
            <button class="faq-question" aria-expanded="false">
                Can I change or cancel my order after placing it?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>You can request a change or cancellation while your order status is still Pending. Once it has been shipped, it can no longer be cancelled, but you can return it after delivery as per our return policy.</p></div>
        </div>

        <div class="faq-item" data-cat="orders">
            <button class="faq-question" aria-expanded="false">
                How can I track my order?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Log in and open <a href="orders.php">My Orders</a>. Each order shows its order number (starting with DAR) and its current status.</p></div>
        </div>

        <div class="faq-item" data-cat="orders">
            <button class="faq-question" aria-expanded="false">
                Why can't I add a product to my cart?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>The item may be out of stock, or the selected size or quantity may not be available. Try a different size or check back soon.</p></div>
        </div>

        <!-- SHIPPING -->
        <div class="faq-item" data-cat="shipping">
            <button class="faq-question" aria-expanded="false">
                How long does delivery take?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Orders are usually delivered within 4 to 7 working days, depending on your city and pincode.</p></div>
        </div>

        <div class="faq-item" data-cat="shipping">
            <button class="faq-question" aria-expanded="false">
                What are the shipping charges?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Shipping charges, if any, are shown at checkout before you place your order.</p></div>
        </div>

        <div class="faq-item" data-cat="shipping">
            <button class="faq-question" aria-expanded="false">
                Do you deliver to my area?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Select your state and city at checkout and enter your 6-digit pincode. If your location is available in the list, we can deliver there.</p></div>
        </div>

        <!-- RETURNS -->
        <div class="faq-item" data-cat="returns">
            <button class="faq-question" aria-expanded="false">
                What is your return policy?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>You can request a return within 7 days of delivery if the item is unused, unwashed and has its original tags and packaging.</p></div>
        </div>

        <div class="faq-item" data-cat="returns">
            <button class="faq-question" aria-expanded="false">
                How do I get a refund?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Once we receive and check the returned item, your refund is processed to the original payment method within 5 to 7 working days. For Cash on Delivery orders, we will contact you for bank or UPI details.</p></div>
        </div>

        <div class="faq-item" data-cat="returns">
            <button class="faq-question" aria-expanded="false">
                I received a wrong or damaged item. What should I do?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Contact us within 48 hours of delivery with your order number and a photo of the item. We will arrange a replacement or refund.</p></div>
        </div>

        <!-- PAYMENTS -->
        <div class="faq-item" data-cat="payments">
            <button class="faq-question" aria-expanded="false">
                Which payment methods do you accept?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>We accept Cash on Delivery, UPI / QR code, and Credit or Debit cards.</p></div>
        </div>

        <div class="faq-item" data-cat="payments">
            <button class="faq-question" aria-expanded="false">
                Is Cash on Delivery available?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Yes. Choose Cash on Delivery at checkout and pay when your order arrives.</p></div>
        </div>

        <div class="faq-item" data-cat="payments">
            <button class="faq-question" aria-expanded="false">
                Why is my payment status Pending?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Payment is marked Pending until it is confirmed. For Cash on Delivery, it is updated once the order is delivered and paid.</p></div>
        </div>

        <!-- ACCOUNT -->
        <div class="faq-item" data-cat="account">
            <button class="faq-question" aria-expanded="false">
                Do I need an account to shop?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>You can browse without an account, but you need to <a href="signup.php">sign up</a> or <a href="darl_loginpage.php">log in</a> to use the cart and place an order.</p></div>
        </div>

        <div class="faq-item" data-cat="account">
            <button class="faq-question" aria-expanded="false">
                I forgot my password. What should I do?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Please contact our support team with your registered email address and we will help you regain access to your account.</p></div>
        </div>

        <div class="faq-item" data-cat="account">
            <button class="faq-question" aria-expanded="false">
                Is my personal information safe?
                <span class="faq-icon" aria-hidden="true">+</span>
            </button>
            <div class="faq-answer"><p>Yes. Your password is stored in encrypted form and your details are used only to process your orders.</p></div>
        </div>

    </div>

    <p class="faq-empty" id="faqEmpty" hidden>No questions match your search. Try different words.</p>

    <!-- Contact box -->
    <section class="faq-contact">
        <h2>Still have a question?</h2>
        <p>Our team is happy to help you.</p>
        <a href="mailto:support@darlings.com" class="faq-btn">Email Us</a>
    </section>
</main>

<!-- Footer -->
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

<script>
(() => {
    const items = [...document.querySelectorAll('.faq-item')];
    const tabs = [...document.querySelectorAll('.faq-tab')];
    const search = document.getElementById('faqSearch');
    const empty = document.getElementById('faqEmpty');
    let category = 'all';

    // Open / close one answer at a time
    items.forEach(item => {
        const btn = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            items.forEach(other => {
                other.classList.remove('open');
                other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                other.querySelector('.faq-answer').style.maxHeight = null;
            });
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });

    // Filter by category + search text
    function applyFilter() {
        const term = search.value.trim().toLowerCase();
        let visible = 0;
        items.forEach(item => {
            const matchCat = category === 'all' || item.dataset.cat === category;
            const matchText = item.textContent.toLowerCase().includes(term);
            const show = matchCat && matchText;
            item.hidden = !show;
            if (show) visible++;
        });
        empty.hidden = visible !== 0;
    }

    tabs.forEach(tab => tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        category = tab.dataset.cat;
        applyFilter();
    }));

    search.addEventListener('input', applyFilter);
})();
</script>
</body>
</html>