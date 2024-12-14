<?php
include 'db_connect.php';

$sql = "SELECT Customers.name, Accounts.account_id, Accounts.balance 
        FROM Customers 
        INNER JOIN Accounts ON Customers.customer_id = Accounts.customer_id 
        WHERE Accounts.balance > (SELECT AVG(balance) FROM Accounts)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Customers with Above Average Balance</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Cardholder Name</th><th>Account Number</th><th>Balance</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["name"]."</td><td>".$row["account_id"]."</td><td>".$row["balance"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No customers found with above average balance";
}
$conn->close();
?>