<?php
include 'db_connect.php';

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $conn->query("UPDATE orders SET status='Completed' WHERE id=$order_id");
}

// Fetch orders
$orders = $conn->query("SELECT orders.*, menu.dish_name FROM orders JOIN menu ON orders.dish_id = menu.id");
?>

<h2>Kitchen Orders</h2>
<ul>
<?php while ($row = $orders->fetch_assoc()) { ?>
    <li><?php echo "Table " . $row['table_no'] . " - " . $row['dish_name'] . " (" . $row['quantity'] . ") - " . $row['status']; ?>
        <?php if ($row['status'] == 'Preparing') { ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="update_status">Mark as Completed</button>
            </form>
        <?php } ?>
    </li>
<?php } ?>
</ul>
