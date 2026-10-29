# Darlings Fashion

Darlings is a PHP and MySQL fashion e-commerce project. It contains a customer facing storefront and a separate admin dashboard for managing products and orders.

## Features

- Browse products and view product details
- Customer signup, login, and session based account pages
- Shopping cart, checkout, order placement, and order history
- Store information pages, including About, FAQ, shipping and returns, privacy policy, and terms
- Admin dashboard pages for products, categories, customers, and orders
- JSON endpoints for product listing and customer authentication

## Project layout

```text
USER_FOLDER/       Storefront, customer APIs, assets, and a second admin area
ADMIN_FOLDER/      Admin dashboard and its assets
```

The main storefront entry point is `USER_FOLDER/index.php`. The dashboard entry point is `ADMIN_FOLDER/index.php`.

## Requirements

- PHP with the `mysqli` extension
- MySQL or MariaDB
- A local PHP web stack such as WampServer, XAMPP, or an equivalent Apache/PHP setup

The pages load Bootstrap and Font Awesome from CDNs, so those assets need internet access in the browser.

## Local setup

1. Place or clone this project into your web server's document root. In WampServer, that is commonly `C:\\wamp64\\www\\`.
2. Start Apache and MySQL/MariaDB.
3. Create a database named `ecommerce`.
4. Configure the database connection in the relevant `sql/config.php` files. The current defaults use `localhost`, user `root`, an empty password, and database `ecommerce`; update them to match your local database.
5. Import or create the database tables used by the application, then add product data and any required admin account.
6. Open the storefront at `http://localhost/DARLINGS/USER_FOLDER/` and the admin dashboard at `http://localhost/DARLINGS/ADMIN_FOLDER/` (adjust `DARLINGS` to the folder name under your document root).

## Database note

The repository includes `USER_FOLDER/admin/ecommerce.sql`, which defines a legacy `products` table, and `USER_FOLDER/sql/customers.sql`, which defines the customer accounts table. The current storefront/admin code also queries tables such as `products_admin` and `orders`; those tables are not fully defined by the included SQL files. Importing the included dumps alone may therefore not be sufficient to run every page. Ensure the database schema matches the queries in the PHP files before using checkout or dashboard features.

## Important paths

- Storefront: `USER_FOLDER/`
- Storefront product API: `USER_FOLDER/api/products.php`
- Customer auth API: `USER_FOLDER/api/auth.php`
- Admin dashboard: `ADMIN_FOLDER/`
- Admin page authentication check: `ADMIN_FOLDER/PAGES/auth_check.php`
- Database connection settings: `USER_FOLDER/sql/config.php`, `USER_FOLDER/admin/sql/config.php`, and `ADMIN_FOLDER/sql/config.php`

## Security and deployment

The database connection settings currently contain local development defaults. Set proper database credentials before deployment, protect admin access, and serve the site over HTTPS. Do not use a development database or sample credentials for a public deployment.
