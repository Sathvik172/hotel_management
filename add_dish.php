<?php
include 'db_connect.php';

// Adding a dish
if (isset($_POST['add_dish'])) {
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];

    $sql = "INSERT INTO menu (dish_name, price) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $dish_name, $price);
    $stmt->execute();
}

// Deleting a dish
if (isset($_POST['delete_dish'])) {
    $dish_id = $_POST['dish_id'];
    $sql = "DELETE FROM menu WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dish_id);
    $stmt->execute();
}

// Fetch menu items
$result = $conn->query("SELECT * FROM menu");
?>
<h1>head cook dashboard</h1>
<h2>Manage Menu</h2>
<form method="POST">
    Dish Name: <input type="text" name="dish_name" required>
    Price: <input type="number" step="0.01" name="price" required>
    <button type="submit" name="add_dish">Add Dish</button>
</form>

<h3>Current Menu</h3>
<ul>
<?php while ($row = $result->fetch_assoc()) { ?>
    <li><?php echo $row['dish_name'] . " - ₹" . $row['price']; ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="dish_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="delete_dish">Delete</button>
        </form>
    </li>
<?php } ?>
</ul>
