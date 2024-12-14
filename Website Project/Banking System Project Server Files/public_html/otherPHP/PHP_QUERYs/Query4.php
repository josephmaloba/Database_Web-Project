<?php
include 'db_connect.php';

$sql = "SELECT Cards.card_type, COUNT(*) AS transaction_count, 
        SUM(Transactions.amount) AS total_amount 
        FROM Cards
        INNER JOIN Transactions ON Cards.card_id = Transactions.card_id
        GROUP BY Cards.card_type 
        HAVING COUNT(*) > 2 AND SUM(Transactions.amount) > 200";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Transaction Statistics by Card Type</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Card Type</th><th>Transaction Count</th><th>Total Amount</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["card_type"]."</td><td>".$row["transaction_count"]."</td><td>".$row["total_amount"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No transaction statistics found matching the criteria";
}
$conn->close();
?>