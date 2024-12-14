<?php
// Database credentials
$servername = "css1.seattleu.edu";
$username = " ll_jmaloba";
$password = "5zWmaCpPmINRzc+x";
$dbname = "ll_jmaloba";

try {
    // Establish connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Database connection established successfully.<br>";

    // Check request method
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo "Request method is POST.<br>";
        echo "Raw POST data: ";
        var_dump($_POST);

        // Validate and sanitize input
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $passwordInput = $_POST['password']; // Assume password is raw for hashing

        echo "Sanitized email: [" . htmlspecialchars($email) . "]<br>";
        
        if (empty($email) || empty($passwordInput)) {
            echo "Email or password is missing.<br>";
            exit();
        }

        // Prepare and bind statement to fetch user
        $stmt = $conn->prepare("SELECT hashed_password, role FROM Customers WHERE email = ?");
        if (!$stmt) {
            echo "Failed to prepare statement: " . $conn->error . "<br>";
            exit();
        }
        echo "Prepared statement successfully.<br>";

        $stmt->bind_param("s", $email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Statement executed successfully.<br>";

            // Bind result variables
            $stmt->bind_result($hashedPassword, $role);

            // Fetch results
            if ($stmt->fetch()) {
                echo "Found user with email: " . htmlspecialchars($email) . "<br>";
                echo "User role: " . htmlspecialchars($role) . "<br>";
               

                // Hash the input password using SHA-256
                $hashedInputPassword = hash('sha256', $passwordInput);
                echo "Hashed input password: " . htmlspecialchars($hashedInputPassword) . "<br>";

                // Verify password
                if ($hashedInputPassword === $hashedPassword) {
                    echo "Password verified successfully.<br>";

                    // Start session and store user details
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    if ($role === 'root') {
                        echo "Redirecting to admin dashboard.<br>";
                        header("Location: admin_dashboard.php");
                    } else {
                        echo "Redirecting to customer dashboard.<br>";
                        header("Location: customer_dashboard.php");
                    }
                    exit();
                } else {
                    echo "Invalid email or password.<br>";
                }
            } else {
                echo "No user found with the provided email.<br>";
            }
        } else {
            echo "Statement execution failed: " . $stmt->error . "<br>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Invalid request method.<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
} finally {
    // Close the connection
    $conn->close();
    echo "Database connection closed.<br>";
}
?>
