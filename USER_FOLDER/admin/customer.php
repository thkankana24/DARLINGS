<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

include 'pages/header.php';
include 'pages/sidebar.php';
include 'pages/top-header.php';
?>

<link rel="stylesheet" href="css/style3.css?v=<?php echo filemtime(__DIR__ . '/css/style3.css'); ?>">

<div class="container">
    <div class="page-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Customer Management</h5>
       
    </div>
</div>

<div class="container mt-3 table-body">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sno.</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        include 'sql/config.php';

                        $query = "SELECT id, name, email FROM customers";
                        $table = mysqli_query($conn, $query);

                        if ($table && mysqli_num_rows($table) > 0) {
                            while ($row = mysqli_fetch_assoc($table)) {
                        ?>
                                <tr>
                                    <td class="sno">
                                        <?php echo (int)$row['id']; ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>••••••••</td>

                                    <td>
                                        <a href="sql/customer_delete.php?id=<?php echo (int)$row['id']; ?>"
                                           onclick="return confirm('Delete this customer?');">
                                            <img src="img/delete.png"
                                                 alt="Delete"
                                                 height="20"
                                                 width="20">
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">No customers found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
</div>




<?php include 'pages/footer.php'; ?>