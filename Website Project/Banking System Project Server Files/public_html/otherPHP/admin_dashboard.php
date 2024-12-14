<?php
session_start();
if ($_SESSION['role'] !== 'root') {
    header("Location: login.html");
    exit();
}  

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
<style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        h1, h2 {
            margin: 0.5rem 0;
        }

        header {
            background: #004d99;
            color: white;
            padding: 1rem 0;
            text-align: center;
            width: 100%;
        }

        nav {
            background: #003366;
            display: flex;
            justify-content: center;
            padding: 0.5rem 0;
            width: 100%;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Main Container */
        .container {
            background: white;
            padding: 2rem;
            max-width: 800px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Login Form */
        form {
            margin: 1rem 0;
        }

        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input:focus,
        form textarea:focus {
            outline: none;
            border-color: #004d99;
        }

        button {
            background: #004d99;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background: #003366;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 0.5rem 0;
        }

        ul li a {
            color: #004d99;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background: #004d99;
            color: white;
            width: 100%;
        }
    </style>

</head>
<body>
    <h1>Welcome, Admin!</h1>

<h2>Relations</h2>
        <ul>
            <li><a href="html_tables/Customers.html">Customer</a></li>
            <li><a href="html_tables/Accounts.html">Accounts</a></li>
            <li><a href="html_tables/Checkings.html">Checkings</a></li>
            <li><a href="html_tables/Savings.html">Savings</a></li>
            <li><a href="html_tables/CardsTable.html">Cards Table</a></li>
            <li><a href="html_tables/TransactionsTables.html">Transactions Table</a></li>
        </ul>
        <hr>
        <h2>Queries</h2>
        <ul>
            <li><a href="PHP_QUERYs/Query1.php">Query 1: Cardholder Details</a></li>
            <li><a href="PHP_QUERYs/Query2.php">Query 2: Account Statistics</a></li>
            <li><a href="PHP_QUERYs/Query3.php">Query 3: High Balances</a></li>
            <li><a href="PHP_QUERYs/Query4.php">Query 4: Transaction Summary</a></li>
            <li><a href="PHP_QUERYs/Query5.php">Query 5: Credit Utilization</a></li>
        </ul>
        <hr>
        <h2>Ad-Hoc Query</h2>
        </i><br><br>
<form method="GET">
      <table>
        <tbody><tr>
          <td align="right">
            <strong>Please enter your query here<br></strong>
          </td>
          <td>
            <input size="30" name="query" type="text" data-last-active-input="">
          </td>
        </tr>
        <tr>
          <td align="right">
            <input value="Clear" type="reset">
          </td>
          <td>
            <input value="Search" type="submit">
          </td>
        </tr>
      </tbody></table>
    </form>
	<?php 

include 'db_connectAlt.php';

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
    $allowedTables = ['Customers', 'Accounts', 'CheckingAccounts', 'SavingsAccounts', 'Cards', 'TransactionsTable'];
    
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
	
</i></li></ul><i>
<p></p>
</i></font><i><i>
<hr noshade="noshade" size="2">
<p></p>        <p><b>Note:</b> Please ensure SQL inputs are validated to prevent SQL injection attacks.</p>

</body>
</html>
