<?php
include 'db_connect.php';

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    $bill_query = $conn->query("SELECT orders.id, orders.table_no, orders.room_no, menu.dish_name, menu.price, orders.quantity 
                                FROM orders 
                                JOIN menu ON orders.dish_id = menu.id 
                                WHERE orders.id = $order_id");

    $bill = $bill_query->fetch_assoc();

    if ($bill) {
        $subtotal = $bill['price'] * $bill['quantity'];
        $cgst = $subtotal * 0.09;
        $sgst = $subtotal * 0.09;
        $total = $subtotal + $cgst + $sgst;

        // Insert into billing table only if not already inserted
        $existing_bill = $conn->query("SELECT * FROM billing WHERE order_id = $order_id")->fetch_assoc();
        if (!$existing_bill) {
            $conn->query("INSERT INTO billing (order_id, table_no, room_no, subtotal, cgst, sgst, total, status) 
                          VALUES ({$bill['id']}, {$bill['table_no']}, {$bill['room_no']}, $subtotal, $cgst, $sgst, $total, 'Not Cleared')");
            $message = "Bill Generated Successfully!";
        }
    } else {
        $message = "Invalid Order!";
    }
}

// Mark as cleared and remove the bill from display
if (isset($_POST['clear_bill'])) {
    $clear_order_id = $_POST['clear_bill'];
    $conn->query("DELETE FROM billing WHERE order_id = $clear_order_id");
}

// Fetch all pending bills
$bills = $conn->query("SELECT * FROM billing");
?>

<h2>Billing Details</h2>
<?php if (isset($message)) { echo "<p><strong>$message</strong></p>"; } ?>

<table border="1">
    <tr>
        <th>Order ID</th>
        <th>Table No</th>
        <th>Subtotal (₹)</th>
        <th>CGST (9%)</th>
        <th>SGST (9%)</th>
        <th>Total (₹)</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $bills->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo $row['table_no']; ?></td>
        <td><?php echo number_format($row['subtotal'], 2); ?></td>
        <td><?php echo number_format($row['cgst'], 2); ?></td>
        <td><?php echo number_format($row['sgst'], 2); ?></td>
        <td><?php echo number_format($row['total'], 2); ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <form method="POST">
                <button type="submit" name="clear_bill" value="<?php echo $row['order_id']; ?>">Clear Bill</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
