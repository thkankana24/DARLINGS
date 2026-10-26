<?php
include 'PAGES/auth_check.php';
require_once 'sql/config.php';

$adminId = (int)($_SESSION['admin_id'] ?? 0);

/* ---------- HELPERS ---------- */
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function flash($section, $type, $text) {
    $_SESSION['settings_flash'] = ['section' => $section, 'type' => $type, 'text' => $text];
}

// Make sure the password column can hold a hashed password (60+ characters)
function ensurePasswordColumn($conn) {
    $res = mysqli_query(
        $conn,
        "SELECT CHARACTER_MAXIMUM_LENGTH AS len
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = 'admin_users'
           AND COLUMN_NAME = 'password'"
    );
    $col = $res ? mysqli_fetch_assoc($res) : null;

    if ($col && $col['len'] !== null && (int)$col['len'] < 255) {
        return (bool)mysqli_query($conn, "ALTER TABLE admin_users MODIFY password VARCHAR(255) NOT NULL");
    }
    return true;
}

/* ---------- CSRF TOKEN ---------- */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

/* ---------- STORE SETTINGS TABLE (created automatically) ---------- */
mysqli_query(
    $conn,
    "CREATE TABLE IF NOT EXISTS store_settings (
        setting_key   VARCHAR(100) NOT NULL PRIMARY KEY,
        setting_value TEXT NOT NULL,
        updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

$defaults = [
    'store_name'          => 'Darlings',
    'store_email'         => '',
    'store_phone'         => '',
    'store_address'       => '',
    'shipping_charge'     => '49',
    'free_shipping_above' => '999',
    'low_stock_alert'     => '5',
    'return_days'         => '7',
];

/* ---------- LOAD CURRENT ADMIN ---------- */
$stmt = mysqli_prepare($conn, "SELECT id, name, email, role, password FROM admin_users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $adminId);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$admin) {
    header('Location: logout.php');
    exit;
}

/* ---------- HANDLE FORMS ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        flash('profile', 'error', 'Your session expired. Please try again.');
        header('Location: settings.php');
        exit;
    }

    /* --- Profile --- */
    if (isset($_POST['save_profile'])) {
        $name  = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));

        if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
            flash('profile', 'error', 'Please enter a name between 2 and 100 characters.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('profile', 'error', 'Please enter a valid email address.');
        } else {
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM admin_users WHERE email = ? AND role = ? AND id <> ?"
            );
            mysqli_stmt_bind_param($stmt, 'ssi', $email, $admin['role'], $adminId);
            mysqli_stmt_execute($stmt);
            $exists = mysqli_stmt_get_result($stmt)->num_rows > 0;
            mysqli_stmt_close($stmt);

            if ($exists) {
                flash('profile', 'error', 'Another account already uses this email.');
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE admin_users SET name = ?, email = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, 'ssi', $name, $email, $adminId);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['admin_name']  = $name;
                    $_SESSION['admin_email'] = $email;
                    flash('profile', 'success', 'Profile updated successfully.');
                } else {
                    flash('profile', 'error', 'Could not update your profile. Please try again.');
                }
                mysqli_stmt_close($stmt);
            }
        }
        header('Location: settings.php');
        exit;
    }

    /* --- Password --- */
    if (isset($_POST['save_password'])) {
        $current = (string)($_POST['current_password'] ?? '');
        $new     = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        $stored  = (string)$admin['password'];

        // Works with both hashed passwords and older plain-text ones
        $currentOk = password_verify($current, $stored) || hash_equals($stored, $current);

        if (!$currentOk) {
            flash('password', 'error', 'Your current password is incorrect.');
        } elseif (strlen($new) < 8) {
            flash('password', 'error', 'The new password must be at least 8 characters long.');
        } elseif (!preg_match('/[A-Za-z]/', $new) || !preg_match('/[0-9]/', $new)) {
            flash('password', 'error', 'Use at least one letter and one number in the new password.');
        } elseif ($new !== $current && $new !== $confirm) {
            flash('password', 'error', 'The new passwords do not match.');
        } elseif ($new === $current) {
            flash('password', 'error', 'The new password must be different from the current one.');
        } elseif (!ensurePasswordColumn($conn)) {
            flash('password', 'error', 'The password column in the database is too short. Run: ALTER TABLE admin_users MODIFY password VARCHAR(255) NOT NULL;');
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE admin_users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $hash, $adminId);

            if (mysqli_stmt_execute($stmt)) {
                session_regenerate_id(true);
                flash('password', 'success', 'Password changed successfully.');
            } else {
                flash('password', 'error', 'Could not change the password. Please try again.');
            }
            mysqli_stmt_close($stmt);
        }
        header('Location: settings.php');
        exit;
    }

    /* --- Store settings --- */
    if (isset($_POST['save_store'])) {
        $values = [
            'store_name'          => trim($_POST['store_name'] ?? ''),
            'store_email'         => strtolower(trim($_POST['store_email'] ?? '')),
            'store_phone'         => trim($_POST['store_phone'] ?? ''),
            'store_address'       => trim($_POST['store_address'] ?? ''),
            'shipping_charge'     => trim($_POST['shipping_charge'] ?? ''),
            'free_shipping_above' => trim($_POST['free_shipping_above'] ?? ''),
            'low_stock_alert'     => trim($_POST['low_stock_alert'] ?? ''),
            'return_days'         => trim($_POST['return_days'] ?? ''),
        ];

        $error = '';
        if ($values['store_name'] === '' || mb_strlen($values['store_name']) > 100) {
            $error = 'Please enter a store name (up to 100 characters).';
        } elseif ($values['store_email'] !== '' && !filter_var($values['store_email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid store email address.';
        } elseif ($values['store_phone'] !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $values['store_phone'])) {
            $error = 'Please enter a valid phone number.';
        } elseif (!is_numeric($values['shipping_charge']) || (float)$values['shipping_charge'] < 0) {
            $error = 'Shipping charge must be 0 or more.';
        } elseif (!is_numeric($values['free_shipping_above']) || (float)$values['free_shipping_above'] < 0) {
            $error = 'Free shipping amount must be 0 or more.';
        } elseif (!ctype_digit($values['low_stock_alert'])) {
            $error = 'Low stock alert must be a whole number.';
        } elseif (!ctype_digit($values['return_days']) || (int)$values['return_days'] > 60) {
            $error = 'Return window must be a whole number from 0 to 60 days.';
        }

        if ($error !== '') {
            flash('store', 'error', $error);
        } else {
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO store_settings (setting_key, setting_value)
                 VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
            );
            $ok = true;
            foreach ($values as $key => $value) {
                mysqli_stmt_bind_param($stmt, 'ss', $key, $value);
                if (!mysqli_stmt_execute($stmt)) {
                    $ok = false;
                }
            }
            mysqli_stmt_close($stmt);

            flash('store', $ok ? 'success' : 'error',
                  $ok ? 'Store settings saved.' : 'Some settings could not be saved. Please try again.');
        }
        header('Location: settings.php');
        exit;
    }
}

/* ---------- LOAD STORE SETTINGS ---------- */
$settings = $defaults;
$res = mysqli_query($conn, "SELECT setting_key, setting_value FROM store_settings");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        if (array_key_exists($row['setting_key'], $settings)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
}

/* ---------- FLASH MESSAGE ---------- */
$flash = $_SESSION['settings_flash'] ?? null;
unset($_SESSION['settings_flash']);
$activeTab = $flash['section'] ?? 'profile';

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>
<link rel="stylesheet" href="css_file/settings.css">

<main class="st-page">

    <div class="st-heading">
        <span class="st-eyebrow">DARLINGS / SETTINGS</span>
        <h1>Settings <span>♡</span></h1>
        <p>Manage your account and how your store works.</p>
    </div>

    <div class="st-layout">

        <!-- TAB LIST -->
        <nav class="st-tabs" aria-label="Settings sections">
            <button type="button" class="st-tab <?= $activeTab === 'profile' ? 'active' : '' ?>" data-tab="profile">
                <i class="fa-solid fa-user"></i> Profile
            </button>
            <button type="button" class="st-tab <?= $activeTab === 'password' ? 'active' : '' ?>" data-tab="password">
                <i class="fa-solid fa-lock"></i> Password
            </button>
            <button type="button" class="st-tab <?= $activeTab === 'store' ? 'active' : '' ?>" data-tab="store">
                <i class="fa-solid fa-store"></i> Store settings
            </button>
            <button type="button" class="st-tab <?= $activeTab === 'account' ? 'active' : '' ?>" data-tab="account">
                <i class="fa-solid fa-shield-halved"></i> Account
            </button>
        </nav>

        <div class="st-panels">

            <!-- ================= PROFILE ================= -->
            <section class="st-panel <?= $activeTab === 'profile' ? 'active' : '' ?>" id="tab-profile">
                <div class="st-card">
                    <h2>Profile</h2>
                    <p class="st-sub">Your name and email are used to sign in and shown in the admin panel.</p>

                    <?php if ($flash && $flash['section'] === 'profile'): ?>
                        <div class="st-message <?= e($flash['type']) ?>"><?= e($flash['text']) ?></div>
                    <?php endif; ?>

                    <form method="POST" novalidate>
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                        <div class="st-grid">
                            <div class="st-field">
                                <label for="name">Full name</label>
                                <input type="text" id="name" name="name" maxlength="100"
                                       value="<?= e($admin['name']) ?>" required>
                            </div>

                            <div class="st-field">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" maxlength="150"
                                       value="<?= e($admin['email']) ?>" required>
                            </div>

                            <div class="st-field">
                                <label for="role">Role</label>
                                <input type="text" id="role" value="<?= e(ucfirst($admin['role'])) ?>" disabled>
                                <small>Your role can't be changed here.</small>
                            </div>
                        </div>

                        <button type="submit" name="save_profile" class="st-btn">Save profile</button>
                    </form>
                </div>
            </section>

            <!-- ================= PASSWORD ================= -->
            <section class="st-panel <?= $activeTab === 'password' ? 'active' : '' ?>" id="tab-password">
                <div class="st-card">
                    <h2>Change password</h2>
                    <p class="st-sub">Use at least 8 characters with a mix of letters and numbers.</p>

                    <?php if ($flash && $flash['section'] === 'password'): ?>
                        <div class="st-message <?= e($flash['type']) ?>"><?= e($flash['text']) ?></div>
                    <?php endif; ?>

                    <form method="POST" novalidate autocomplete="off">
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                        <div class="st-grid single">
                            <div class="st-field">
                                <label for="current_password">Current password</label>
                                <div class="st-password">
                                    <input type="password" id="current_password" name="current_password"
                                           autocomplete="current-password" required>
                                    <button type="button" class="st-eye" data-target="current_password">Show</button>
                                </div>
                            </div>

                            <div class="st-field">
                                <label for="new_password">New password</label>
                                <div class="st-password">
                                    <input type="password" id="new_password" name="new_password"
                                           autocomplete="new-password" required>
                                    <button type="button" class="st-eye" data-target="new_password">Show</button>
                                </div>
                                <div class="st-strength"><span id="strengthBar"></span></div>
                                <small id="strengthText">Enter a new password</small>
                            </div>

                            <div class="st-field">
                                <label for="confirm_password">Confirm new password</label>
                                <div class="st-password">
                                    <input type="password" id="confirm_password" name="confirm_password"
                                           autocomplete="new-password" required>
                                    <button type="button" class="st-eye" data-target="confirm_password">Show</button>
                                </div>
                                <small id="matchText"></small>
                            </div>
                        </div>

                        <button type="submit" name="save_password" class="st-btn">Update password</button>
                    </form>
                </div>
            </section>

            <!-- ================= STORE SETTINGS ================= -->
            <section class="st-panel <?= $activeTab === 'store' ? 'active' : '' ?>" id="tab-store">
                <div class="st-card">
                    <h2>Store settings</h2>
                    <p class="st-sub">Details about your store, delivery charges and stock alerts.</p>

                    <?php if ($flash && $flash['section'] === 'store'): ?>
                        <div class="st-message <?= e($flash['type']) ?>"><?= e($flash['text']) ?></div>
                    <?php endif; ?>

                    <form method="POST" novalidate>
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                        <h3 class="st-group">Store details</h3>
                        <div class="st-grid">
                            <div class="st-field">
                                <label for="store_name">Store name</label>
                                <input type="text" id="store_name" name="store_name" maxlength="100"
                                       value="<?= e($settings['store_name']) ?>" required>
                            </div>

                            <div class="st-field">
                                <label for="store_email">Support email</label>
                                <input type="email" id="store_email" name="store_email" maxlength="150"
                                       value="<?= e($settings['store_email']) ?>" placeholder="support@darlings.com">
                            </div>

                            <div class="st-field">
                                <label for="store_phone">Phone number</label>
                                <input type="text" id="store_phone" name="store_phone" maxlength="20"
                                       value="<?= e($settings['store_phone']) ?>" placeholder="+91 98765 43210">
                            </div>

                            <div class="st-field full">
                                <label for="store_address">Store address</label>
                                <textarea id="store_address" name="store_address" maxlength="255"
                                          placeholder="Shop address shown to customers"><?= e($settings['store_address']) ?></textarea>
                            </div>
                        </div>

                        <h3 class="st-group">Delivery &amp; returns</h3>
                        <div class="st-grid">
                            <div class="st-field">
                                <label for="shipping_charge">Shipping charge (₹)</label>
                                <input type="number" id="shipping_charge" name="shipping_charge"
                                       min="0" step="1" value="<?= e($settings['shipping_charge']) ?>" required>
                            </div>

                            <div class="st-field">
                                <label for="free_shipping_above">Free shipping above (₹)</label>
                                <input type="number" id="free_shipping_above" name="free_shipping_above"
                                       min="0" step="1" value="<?= e($settings['free_shipping_above']) ?>" required>
                                <small>Set to 0 to always offer free shipping.</small>
                            </div>

                            <div class="st-field">
                                <label for="return_days">Return window (days)</label>
                                <input type="number" id="return_days" name="return_days"
                                       min="0" max="60" step="1" value="<?= e($settings['return_days']) ?>" required>
                            </div>
                        </div>

                        <h3 class="st-group">Inventory</h3>
                        <div class="st-grid">
                            <div class="st-field">
                                <label for="low_stock_alert">Low stock alert at</label>
                                <input type="number" id="low_stock_alert" name="low_stock_alert"
                                       min="0" step="1" value="<?= e($settings['low_stock_alert']) ?>" required>
                                <small>Products with this many items or fewer are marked as low stock.</small>
                            </div>
                        </div>

                        <button type="submit" name="save_store" class="st-btn">Save store settings</button>
                    </form>
                </div>
            </section>

            <!-- ================= ACCOUNT ================= -->
            <section class="st-panel <?= $activeTab === 'account' ? 'active' : '' ?>" id="tab-account">
                <div class="st-card">
                    <h2>Account</h2>
                    <p class="st-sub">A quick look at who is signed in.</p>

                    <div class="st-account">
                        <div class="st-avatar">👤</div>
                        <div>
                            <strong><?= e($admin['name']) ?></strong>
                            <span><?= e($admin['email']) ?></span>
                            <em><?= e(ucfirst($admin['role'])) ?></em>
                        </div>
                    </div>

                    <div class="st-links">
                        <a href="product.php">Manage products <span>→</span></a>
                        <a href="orders.php">View orders <span>→</span></a>
                        <a href="customers.php">View customers <span>→</span></a>
                        <a href="category.php">Manage categories <span>→</span></a>
                    </div>

                    <a href="logout.php" class="st-btn st-btn-outline">Log out</a>
                </div>
            </section>

        </div>
    </div>
</main>

<script>
/* ---------- TABS ---------- */
(function () {
    const tabs = document.querySelectorAll('.st-tab');
    const panels = document.querySelectorAll('.st-panel');

    function show(name) {
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
        panels.forEach(p => p.classList.toggle('active', p.id === 'tab-' + name));
    }

    tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.tab)));
})();

/* ---------- SHOW / HIDE PASSWORD ---------- */
document.querySelectorAll('.st-eye').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        const hidden = input.type === 'password';
        input.type = hidden ? 'text' : 'password';
        this.textContent = hidden ? 'Hide' : 'Show';
    });
});

/* ---------- PASSWORD STRENGTH + MATCH ---------- */
(function () {
    const newPass = document.getElementById('new_password');
    const confirm = document.getElementById('confirm_password');
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const match = document.getElementById('matchText');

    newPass.addEventListener('input', function () {
        const v = this.value;
        let score = 0;
        if (v.length >= 8) score++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
        if (/[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;

        const labels = ['Too weak', 'Weak', 'Okay', 'Good', 'Strong'];
        const colors = ['#d9c6cf', '#e0566f', '#f0a030', '#5bb974', '#23804a'];
        const index = v.length === 0 ? 0 : score;

        bar.style.width = (v.length === 0 ? 0 : (index / 4) * 100) + '%';
        bar.style.background = colors[index];
        text.textContent = v.length === 0 ? 'Enter a new password' : labels[index];
        checkMatch();
    });

    function checkMatch() {
        if (confirm.value === '') { match.textContent = ''; return; }
        const same = confirm.value === newPass.value;
        match.textContent = same ? 'Passwords match' : 'Passwords do not match';
        match.style.color = same ? '#23804a' : '#c93c69';
    }

    confirm.addEventListener('input', checkMatch);
})();
</script>

<?php include 'PAGES/footer.php'; ?>