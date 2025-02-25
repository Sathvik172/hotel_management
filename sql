CREATE DATABASE hotel_management;
USE hotel_management;

-- Table for storing menu items
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dish_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- Table for storing customer orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_no INT NOT NULL,
    room_no INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL,
    status ENUM('Preparing', 'Completed') DEFAULT 'Preparing',
    order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dish_id) REFERENCES menu(id) ON DELETE CASCADE
);

-- Table for storing billing details
CREATE TABLE billing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    table_no INT NOT NULL,
    room_no INT NOT NULL,
    subtotal DECIMAL(10,2),
    cgst DECIMAL(10,2),
    sgst DECIMAL(10,2),
    total DECIMAL(10,2),
    status ENUM('Not Cleared', 'Cleared') DEFAULT 'Not Cleared',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
