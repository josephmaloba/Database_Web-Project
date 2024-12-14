<?php
session_start();

// Check if the user is logged in and is a customer
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.html");
    exit();
}

// Retrieve email from the session
$email = $_SESSION['email'];

// Database credentials
$servername = "css1.seattleu.edu";
$username = "ll_jmaloba";
$password = "5zWmaCpPmINRzc+x";
$dbname = "ll_jmaloba";

try {
    // Establish connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch customer data
    $stmt = $conn->prepare("SELECT customer_id, name, email, role FROM Customers WHERE email = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($customerID, $name, $email, $role);
    $stmt->fetch();
    $stmt->close();

    // Check if a user was found
    if (empty($customerID)) {
        echo "No user found with the email " . htmlspecialchars($email) . ".";
        exit();
    }

    // Fetch account data
    $stmt = $conn->prepare("SELECT account_id, account_type, balance FROM Accounts WHERE customer_id = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($accountID, $accountType, $balance);

    $accounts = [];
    while ($stmt->fetch()) {
        $accounts[] = [
            'account_id' => $accountID,
            'account_type' => $accountType,
            'balance' => $balance
        ];
    }
    $stmt->close();

    // Fetch card data
    $stmt = $conn->prepare("SELECT card_id, card_type, account_id, card_limit, credit_card_number, security_code FROM Cards WHERE customer_id = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($cardID, $cardType, $linkedAccountID, $cardLimit, $cardNumber, $securityCode);

    $cards = [];
    while ($stmt->fetch()) {
        // Mask card number (show only last 4 digits)
        $maskedCardNumber = str_repeat("*", strlen($cardNumber) - 4) . substr($cardNumber, -4);

        // Hash the security code
        $hashedSecurityCode = password_hash($securityCode, PASSWORD_DEFAULT);

        $cards[] = [
            'card_id' => $cardID,
            'card_type' => $cardType,
            'account_id' => $linkedAccountID,
            'card_limit' => $cardLimit,
            'masked_card_number' => $maskedCardNumber,
            'hashed_security_code' => $hashedSecurityCode
        ];
    }
    $stmt->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
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

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 1rem auto;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #004d99;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

    <h2>Your Information:</h2>
    <table>
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($customerID); ?></td>
            <td><?php echo htmlspecialchars($name); ?></td>
            <td><?php echo htmlspecialchars($email); ?></td>
            <td><?php echo htmlspecialchars($role); ?></td>
        </tr>
    </table>

    <h2>Your Accounts:</h2>
    <table>
        <tr>
            <th>Account ID</th>
            <th>Account Type</th>
            <th>Balance</th>
        </tr>
        <?php foreach ($accounts as $account): ?>
        <tr>
            <td><?php echo htmlspecialchars($account['account_id']); ?></td>
            <td><?php echo htmlspecialchars($account['account_type']); ?></td>
            <td><?php echo htmlspecialchars($account['balance']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Your Cards:</h2>
    <table>
        <tr>
            <th>Card ID</th>
            <th>Card Type</th>
            <th>Linked Account ID</th>
            <th>Card Limit</th>
            <th>Masked Card Number</th>
            <th>Hashed Security Code</th>
        </tr>
        <?php foreach ($cards as $card): ?>
        <tr>
            <td><?php echo htmlspecialchars($card['card_id']); ?></td>
            <td><?php echo htmlspecialchars($card['card_type']); ?></td>
            <td><?php echo htmlspecialchars($card['account_id']); ?></td>
            <td><?php echo htmlspecialchars($card['card_limit']); ?></td>
            <td><?php echo htmlspecialchars($card['masked_card_number']); ?></td>
            <td><?php echo htmlspecialchars($card['hashed_security_code']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
