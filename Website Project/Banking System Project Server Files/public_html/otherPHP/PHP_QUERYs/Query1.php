<?php
include 'db_connect.php';

$sql = "SELECT Customers.name, Accounts.account_id, Accounts.balance 
        FROM Customers 
        INNER JOIN Accounts ON Customers.customer_id = Accounts.customer_id 
        INNER JOIN Cards ON Accounts.account_id = Cards.account_id 
        WHERE Cards.card_type = 'Credit'";

$result = $conn->query($sql);

if ($result === false) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>Credit Card Holders</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Cardholder Name</th><th>Account Number</th><th>Balance</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["name"]."</td><td>".$row["account_id"]."</td><td>".$row["balance"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No credit card holders found";
}
$conn->close();
?>