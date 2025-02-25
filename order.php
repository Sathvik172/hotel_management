<?php
include 'db_connect.php';

// Fetch menu items
$menu = $conn->query("SELECT * FROM menu");

// Fetch orders with status
$orders = $conn->query("SELECT orders.*, menu.dish_name, menu.price FROM orders JOIN menu ON orders.dish_id = menu.id");

if (isset($_POST['place_order'])) {
    $table_no = $_POST['table_no'];
    $room_no = $_POST['room_no'];
    $dish_id = $_POST['dish_id'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO orders (table_no, room_no, dish_id, quantity, status) VALUES (?, ?, ?, ?, 'Preparing')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $table_no, $room_no, $dish_id, $quantity);
    $stmt->execute();
}
?>
<h1>Waiter Dashboard</h1>

<h2>Place Order</h2>
<form method="POST">
    Table No: <input type="number" name="table_no" required>
    Room No: <input type="number" name="room_no" required>
    Dish: <select name="dish_id">
        <?php while ($row = $menu->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['dish_name'] . " - ₹" . $row['price']; ?></option>
        <?php } ?>
    </select>
    Quantity: <input type="number" name="quantity" required>
    <button type="submit" name="place_order">Submit</button>
</form>

<h2>Order Status</h2>
<table border="1">
    <tr>
        <th>Table No</th>
        <th>Dish</th>
        <th>Quantity</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $orders->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['table_no']; ?></td>
        <td><?php echo $row['dish_name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <?php if ($row['status'] == 'Completed') { ?>
                <!-- Generate Bill Button -->
                <form action="billing.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="generate_bill">Generate Bill</button>
                </form>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>
