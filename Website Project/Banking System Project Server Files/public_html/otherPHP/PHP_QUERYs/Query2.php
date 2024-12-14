<?php
include 'db_connect.php';

$sql = "SELECT Accounts.account_type, COUNT(*) AS account_count, 
        AVG(Accounts.balance) AS avg_balance, SUM(Accounts.balance) AS total_balance 
        FROM Accounts 
        GROUP BY Accounts.account_type";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Account Statistics</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Account Type</th><th>Account Count</th><th>Average Balance</th><th>Total Balance</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["account_type"]."</td><td>".$row["account_count"]."</td><td>".$row["avg_balance"]."</td><td>".$row["total_balance"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No account statistics found";
}
$conn->close();
?>