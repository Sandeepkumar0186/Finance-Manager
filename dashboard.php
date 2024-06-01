<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_expense"])) {
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $date = $_POST["date"];

    $sql = "INSERT INTO expenses (user_id, amount, description, date) VALUES ('$user_id', '$amount', '$description', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "Expense added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM expenses WHERE user_id='$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Dashboard</h2>
    <form method="POST" action="dashboard.php">
        <input type="submit" name="logout" value="Logout">
    </form>
    <h3>Add Expense</h3>
    <form method="POST" action="dashboard.php">
        Amount: <input type="text" name="amount" required><br>
        Description: <input type="text" name="description" required><br>
        Date: <input type="date" name="date" required><br>
        <input type="submit" name="add_expense" value="Add Expense">
    </form>
    <h3>Your Expenses</h3>
    <table border="1">
        <tr>
            <th>Amount</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["amount"]. "</td><td>" . $row["description"]. "</td><td>" . $row["date"]. "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No expenses found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
