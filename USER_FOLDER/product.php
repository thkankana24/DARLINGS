<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlings | Product Details</title>
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/product-detail.css">
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

<!-- Product Details -->
<main class="detail-main">
    <p>
        <a href="index.php">Home</a> /
        <a href="products.php">Products</a>
    </p>

    <p id="detailMessage">Loading product...</p>
    <article id="productDetail" class="product-detail" hidden></article>
</main>

<!-- Footer -->
<footer class="darlings-footer">
    <div class="footer-container">
        <div>
            <h3>Darlings</h3>
            <p>Your one-stop shop for fashion, shoes, and more.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Shop</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </div>
        <div>
            <h4>Customer Service</h4>
            <ul>
                <li><a href="cart.php">Your cart</a></li>
                <li><a href="orders.php">My orders</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Darlings. All rights reserved.</p>
    </div>
</footer>

<!-- JavaScript -->
<script>
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");

    const message = document.getElementById("detailMessage");
    const detail = document.getElementById("productDetail");

    // Load products from API
    fetch("api/products.php")
        .then(response => response.json())
        .then(products => {
            const product = products.find(item => item.id == id);

            if (!product) {
                message.textContent = "Product not found.";
                return;
            }

            document.title = "Darlings | " + product.name;
            const sizes = product.sizes || ["One size"];

            detail.innerHTML = `
                <img class="detail-image"
                     src="${product.image}"
                     alt="${product.name}"
                     onerror="this.onerror=null; this.src='img/fashionimg.jpeg'">

                <div class="detail-copy">
                    <p class="product-category">${product.category}</p>
                    <h1>${product.name}</h1>
                    <p class="detail-description">${product.description}</p>
                    <strong class="detail-price">₹${Number(product.price).toLocaleString("en-IN")}</strong>

                    <label for="detailSize">Choose size</label>
                    <select id="detailSize">
                        ${sizes.map(size => `<option value="${size}">${size}</option>`).join("")}
                    </select>

                    <!-- Add to Cart form -->
                    <form method="POST" action="addtocart.php">
                        <input type="hidden" name="id" value="${product.id}">
                        <input type="hidden" name="name" value="${product.name}">
                        <input type="hidden" name="price" value="${product.price}">
                        <input type="hidden" name="image" value="${product.image}">
                        <input type="hidden" name="category" value="${product.category}">
                        <input type="hidden" id="sizeField" name="size" value="${sizes[0]}">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>

                    <p id="addMessage" class="add-message"></p>
                </div>
            `;

            detail.hidden = false;
            message.textContent = "";

            // Sync dropdown with hidden size field
            const sizeSelect = document.getElementById("detailSize");
            const sizeField = document.getElementById("sizeField");
            sizeSelect.addEventListener("change", () => {
                sizeField.value = sizeSelect.value;
            });
        })
        .catch(() => {
            message.textContent = "Unable to load product details. Please try again.";
        });
        
</script>

</body>
</html>
