<?php

include 'db_connect.php';

function executeQuery($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        return "Error executing query: " . $conn->error;
    }
    
    $output = "<table border='1'><tr>";
    while ($fieldinfo = $result->fetch_field()) {
        $output .= "<th>{$fieldinfo->name}</th>";
    }
    $output .= "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        foreach ($row as $value) {
            $output .= "<td>" . htmlspecialchars($value) . "</td>";
        }
        $output .= "</tr>";
    }
    $output .= "</table>";
    
    return $output;
}

if (isset($_GET['query'])) {
    $userQuery = $_GET['query'];
    $allowedTables = ['Customers', 'Accounts', 'Checkings', 'Savings', 'CardsTable', 'CreditCardTable', 'DebitsCardTable', 'TransactionsTable', 'Token'];
    
    if (preg_match('/^SELECT\s+\*\s+FROM\s+(\w+)$/i', $userQuery, $matches)) {
        $tableName = $matches[1];
        if (in_array($tableName, $allowedTables)) {
            echo executeQuery($conn, $userQuery);
        } else {
            echo "Invalid table name";
        }
    } else {
        echo "Invalid query format. Please use 'SELECT * FROM tablename'";
    }
}

$conn->close();
?>

<form method="GET">
    <input type="text" name="query" placeholder="Enter SQL query (e.g., SELECT * FROM Customers)">
    <input type="submit" value="Search">
</form>