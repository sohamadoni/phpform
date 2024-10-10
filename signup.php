<?php
include 'db/db_connection.php'; // Include your database connection

// Function to validate input data
function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Initialize variables
$name = $email = $phone = $gender = $interests = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = validateInput($_POST["name"]);
    $email = validateInput($_POST["email"]);
    $phone = validateInput($_POST["phone"]);
    $gender = validateInput($_POST["gender"]);
    $interests = isset($_POST["interests"]) ? implode(", ", $_POST["interests"]) : "";

    // Simple validation (add more rules as needed)
    if (empty($name) || empty($email) || empty($phone) || empty($gender)) {
        $error = "All fields are required.";
    } else {
        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, gender, interests) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $gender, $interests);

        if ($stmt->execute()) {
            header("Location: signup.php"); // Redirect to the form page
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <video autoplay muted loop class="background-video">
        <source src="stopm.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <h1>Signup Form</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone No." required>
            
            <label>Gender:</label><br>
            <input type="radio" name="gender" value="Male" required> Male
            <input type="radio" name="gender" value="Female" required> Female<br>

            <label>Interests:</label><br>
            <input type="checkbox" name="interests[]" value="Sports"> Sports
            <input type="checkbox" name="interests[]" value="Tech"> Tech
            <input type="checkbox" name="interests[]" value="Nation"> Nation
            <input type="checkbox" name="interests[]" value="International"> International<br>

            <input type="submit" value="Sign Up">
        </form>

        <h2>Users List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Interests</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['interests']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['id']; ?>">Update</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
