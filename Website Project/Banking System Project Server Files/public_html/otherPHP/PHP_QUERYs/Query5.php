<?php
include 'db_connect.php';

$sql = "SELECT Customers.name AS customer_name, 
               Accounts.account_id AS account_number, 
               Cards.card_limit AS credit_limit, 
               (Cards.card_limit - 1000) AS available_credit -- Example calculation
        FROM Customers 
        LEFT OUTER JOIN Accounts ON Customers.customer_id = Accounts.customer_id 
        LEFT OUTER JOIN Cards ON Accounts.account_id = Cards.account_id
        WHERE Cards.card_type = 'Credit' OR Cards.card_type IS NULL";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Customer Credit Card Information</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Cardholder Name</th><th>Account Number</th><th>Credit Limit</th><th>Available Credit</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["customer_name"]) . "</td>
                <td>" . htmlspecialchars($row["account_number"]) . "</td>
                <td>" . htmlspecialchars($row["credit_limit"] ?? "N/A") . "</td>
                <td>" . htmlspecialchars($row["available_credit"] ?? "N/A") . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No customer credit card information found.";
}
$conn->close();
?>
