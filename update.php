<?php
include 'db/db_connection.php';

// Function to validate input data
function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    $user = $result->fetch_assoc();
}

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = validateInput($_POST["name"]);
    $email = validateInput($_POST["email"]);
    $phone = validateInput($_POST["phone"]);
    $gender = validateInput($_POST["gender"]);
    $interests = isset($_POST["interests"]) ? implode(", ", $_POST["interests"]) : "";

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, gender = ?, interests = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $phone, $gender, $interests, $id);

    if ($stmt->execute()) {
        header("Location: signup.php"); // Redirect to the main page after successful update
        exit();
    } else {
        echo "Error: " . $stmt->error; // Show error message if the query fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Update User</h1>

    <form method="POST" action="">
        <input type="text" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>
        <input type="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
        <input type="text" name="phone" value="<?php echo isset($user['phone']) ? $user['phone'] : ''; ?>" required>
        
        <label>Gender:</label><br>
        <input type="radio" name="gender" value="Male" <?php if(isset($user['gender']) && $user['gender'] == "Male") echo "checked"; ?> required> Male
        <input type="radio" name="gender" value="Female" <?php if(isset($user['gender']) && $user['gender'] == "Female") echo "checked"; ?> required> Female<br>

        <label>Interests:</label><br>
        <?php $interestsArray = isset($user['interests']) ? explode(", ", $user['interests']) : []; ?>
        <input type="checkbox" name="interests[]" value="Sports" <?php if(in_array("Sports", $interestsArray)) echo "checked"; ?>> Sports
        <input type="checkbox" name="interests[]" value="Tech" <?php if(in_array("Tech", $interestsArray)) echo "checked"; ?>> Tech
        <input type="checkbox" name="interests[]" value="Nation" <?php if(in_array("Nation", $interestsArray)) echo "checked"; ?>> Nation
        <input type="checkbox" name="interests[]" value="International" <?php if(in_array("International", $interestsArray)) echo "checked"; ?>> International<br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
