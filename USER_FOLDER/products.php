<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shop Products | Darlings</title>

    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,wght@1,600&display=swap" rel="stylesheet">
</head>

<body>

<header class="shop-header">
    <a class="brand" href="index.php">Darlings</a>

    <nav aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="products.php" aria-current="page">Products</a>
        <a href="about.php">About Us</a>
    </nav>

    <a class="cart-link" href="cart.php">
        Cart <span id="cartCount" aria-live="polite">0</span>
    </a>
</header>

<main class="shop-main">

    <section class="shop-intro">
        <p>THE DARLINGS COLLECTION</p>
        <h1>Find your next favorite</h1>
        <span>Thoughtful styles for every day and every occasion.</span>
    </section>

    <section class="shop-controls" aria-label="Product filters">
        <label class="sr-only" for="searchProducts">Search products</label>

        <input
            id="searchProducts"
            type="search"
            placeholder="Search products..."
            autocomplete="off"
        >

        <label class="sr-only" for="categoryFilter">Filter by category</label>

        <select id="categoryFilter">
            <option value="">All categories</option>
        </select>
    </section>

    <p id="productsMessage" class="products-message" role="status" aria-live="polite">
        Loading products...
    </p>

    <section id="productGrid" class="product-grid" aria-label="Products">
    </section>

</main>

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
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </div>

        <div>
            <h4>Customer Service</h4>
            <ul>
                <li><a href="cart.php">Your Cart</a></li>
                <li><a href="orders.php">My Orders</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 Darlings. All rights reserved.</p>
    </div>
</footer>

<script>
(() => {
    "use strict";

    const grid = document.getElementById("productGrid");
    const message = document.getElementById("productsMessage");
    const category = document.getElementById("categoryFilter");
    const search = document.getElementById("searchProducts");
    const cartCount = document.getElementById("cartCount");

    let products = [];

    // Keep the counter visible instead of making it blank.
    cartCount.textContent = "0";

    function escapeHtml(value) {
        return String(value ?? "").replace(/[&<>"']/g, character => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;"
        })[character]);
    }

    function render() {
        const query = search.value.trim().toLowerCase();
        const selectedCategory = category.value;

        const visibleProducts = products.filter(product => {
            const matchesCategory =
                !selectedCategory ||
                String(product.category ?? "") === selectedCategory;

            const searchableText = [
                product.name,
                product.category,
                product.description
            ].join(" ").toLowerCase();

            return matchesCategory && searchableText.includes(query);
        });

        message.textContent = visibleProducts.length
            ? ""
            : products.length
                ? "No products match your search."
                : "No products are available right now.";

        grid.innerHTML = visibleProducts.map(product => {
            const id = Number(product.id);
            const price = Number(product.price);
            const validPrice = Number.isFinite(price) ? price : 0;

            const sizes = Array.isArray(product.sizes) && product.sizes.length
                ? product.sizes
                : ["One size"];

            const safeId = encodeURIComponent(String(id));
            const image = escapeHtml(
                product.image || "img/fashionimg.jpeg"
            );

            return `
                <article class="product-card">

                    <div class="product-image-wrap">
                        ${product.badge ? `
                            <span class="product-badge">
                                ${escapeHtml(product.badge)}
                            </span>
                        ` : ""}

                        <a href="product_details.php?id=${safeId}"
                           aria-label="View ${escapeHtml(product.name)} details">
                            <img
                                src="${image}"
                                alt="${escapeHtml(product.name)}"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='img/fashionimg.jpeg';"
                            >
                        </a>
                    </div>

                    <div class="product-info">

                        <p class="product-category">
                            ${escapeHtml(product.category)}
                        </p>

                        <h2>
                            <a href="product_details.php?id=${safeId}">
                                ${escapeHtml(product.name)}
                            </a>
                        </h2>

                        <p class="product-description">
                            ${escapeHtml(product.description)}
                        </p>

                        <div class="product-buy">
                            <strong>
                                ₹${validPrice.toLocaleString("en-IN")}
                            </strong>

                            <select
                                aria-label="Choose size for ${escapeHtml(product.name)}"
                                data-size="${id}"
                            >
                                ${sizes.map(size => `
                                    <option value="${escapeHtml(size)}">
                                        ${escapeHtml(size)}
                                    </option>
                                `).join("")}
                            </select>
                        </div>

                        <button
                            type="button"
                            class="add-button"
                            data-add="${id}"
                        >
                            Add to bag
                        </button>

                    </div>
                </article>
            `;
        }).join("");
    }

    // Check customer login before adding an item.
    const authStatus = fetch("api/auth.php", {
        credentials: "same-origin"
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Login check failed");
        }

        return response.json();
    })
    .then(data => Boolean(data.customer))
    .catch(() => false);

    // Add the selected product using the existing PHP handler.
    grid.addEventListener("click", async event => {
        const button = event.target.closest("[data-add]");

        if (!button) {
            return;
        }

        const productId = Number(button.dataset.add);
        const product = products.find(
            item => Number(item.id) === productId
        );

        if (!product) {
            message.textContent = "Product not found.";
            return;
        }

        const sizeSelect = grid.querySelector(
            `[data-size="${CSS.escape(String(productId))}"]`
        );

        if (!sizeSelect) {
            message.textContent = "Please select a size.";
            return;
        }

        const selectedSize = sizeSelect.value;

        button.disabled = true;
        button.textContent = "Adding...";

        try {
            const isLoggedIn = await authStatus;

            if (!isLoggedIn) {
                window.location.href =
                    "darl_loginpage.php?next=cart.php";
                return;
            }

            // Submit a normal POST form so PHP can process the request
            // and perform its own redirect.
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "addtocart.php";

            const fields = {
                product_id: String(productId),
                quantity: "1",
                size: selectedSize
            };

            Object.entries(fields).forEach(([name, value]) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = name;
                input.value = value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();

        } catch (error) {
            console.error("Add to bag error:", error);
            message.textContent =
                "Unable to add this product. Please try again.";

            button.disabled = false;
            button.textContent = "Add to bag";
        }
    });

    // Search and category filter.
    search.addEventListener("input", render);
    category.addEventListener("change", render);

    // Load the products from your API.
    fetch("api/products.php", {
        credentials: "same-origin"
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Products could not be loaded");
        }

        return response.json();
    })
    .then(data => {
        if (!Array.isArray(data)) {
            throw new Error("Invalid product response");
        }

        products = data;

        const categories = [
            ...new Set(
                products
                    .map(product => product.category)
                    .filter(Boolean)
            )
        ].sort();

        categories.forEach(name => {
            const option = document.createElement("option");
            option.value = name;
            option.textContent = name;
            category.appendChild(option);
        });

        render();
    })
    .catch(error => {
        console.error("Product loading error:", error);

        message.textContent =
            "Products could not be loaded. Please check api/products.php.";

        grid.innerHTML = "";
    });

})();
</script>

</body>
</html>