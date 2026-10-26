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

<header class="shop-header">
    <a class="brand" href="index.php">Darlings</a>

    <nav aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About us</a>
    </nav>

    <a class="cart-link" href="cart.php">Cart</a>
</header>

<main class="detail-main">
    <p>
        <a href="index.php">Home</a> /
        <a href="products.php">Products</a>
    </p>

    <p id="detailMessage">Loading product...</p>
    <article id="productDetail" class="product-detail" hidden></article>
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

<script>
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

const message = document.getElementById("detailMessage");
const detail = document.getElementById("productDetail");

if (!id) {
    message.textContent = "Please select a product first.";
} else {
    fetch("api/products.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Unable to load products.");
            }
            return response.json();
        })
        .then(products => {
            const product = products.find(item => item.id == id);

            if (!product) {
                message.textContent = "Product not found.";
                return;
            }

            document.title = "Darlings | " + product.name;

            const sizes = Array.isArray(product.sizes) &&
                          product.sizes.length
                ? product.sizes
                : ["One size"];

            detail.innerHTML = `
                <img
                    class="detail-image"
                    src="${product.image}"
                    alt="${product.name}"
                    onerror="this.onerror=null;this.src='img/fashionimg.jpeg'"
                >

                <div class="detail-copy">
                    <p class="product-category">${product.category ?? ""}</p>

                    <h1>${product.name}</h1>

                    <p class="detail-description">
                        ${product.description ?? ""}
                    </p>

                    <strong class="detail-price">
                        ₹${Number(product.price).toLocaleString("en-IN")}
                    </strong>

                    <form method="POST" action="addtocart.php">

                        <input
                            type="hidden"
                            name="product_id"
                            value="${Number(product.id)}"
                        >

                        <label for="detailSize">Choose size</label>

                        <select id="detailSize" name="size" required>
                            ${sizes.map(size =>
                                `<option value="${String(size).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;")}">${String(size).replace(/&/g, "&amp;").replace(/</g, "&lt;")}</option>`
                            ).join("")}
                        </select>

                        <label for="detailQuantity">Quantity</label>

                        <input
                            type="number"
                            id="detailQuantity"
                            name="quantity"
                            value="1"
                            min="1"
                            max="99"
                            required
                        >

                        <button type="submit" class="add-to-cart-btn">
                            Add to Bag
                        </button>
                    </form>
                </div>
            `;

            detail.hidden = false;
            message.textContent = "";
        })
        .catch(error => {
            console.error(error);
            message.textContent =
                "Unable to load product details. Please check your product API.";
        });
}
</script>

</body>
</html>
