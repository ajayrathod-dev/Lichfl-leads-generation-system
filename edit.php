<?php
session_start();
include("connection.php");

// Sanitize GET input
$mob = sanitize_input($_GET['mob']);

// Prepare and execute query to get user details
$sql = "SELECT * FROM user WHERE mobile_no=?";
$stmt = $link->prepare($sql);
$stmt->bind_param("s", $mob);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($usr = $result->fetch_assoc()) {
        $name = $usr['name'];
        $description = $usr['description'];
    }
}

// Process form submission
if (isset($_POST["login"])) {
    // Sanitize POST input
    $nm = sanitize_input($_POST['uname']);
    $desc = sanitize_input($_POST['password']);

    // Prepare and execute query to update user details
    $sqlUpdate = "UPDATE user SET description=?, name=? WHERE mobile_no=?";
    $stmtUpdate = $link->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sss", $desc, $nm, $mob);

    if ($stmtUpdate->execute()) {
        header("Location: api.php?mobile=" . urlencode($mob));
        exit;
    } else {
        echo "Error updating record: " . $link->error;
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Login Container Styling */
        .login-container {
            width: 90%;
            max-width: 400px;
            background: #fff;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            text-align: left;
            color: #4a90e2;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 20px;
        }

        /* Button Styling */
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        .btn:hover {
            background: #555;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 style="color:#4a90e2;"><?php echo htmlspecialchars($mob); ?></h1>
        <form method="post">
            <div class="form-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="uname" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Description</label>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($description); ?>" required>
            </div>
            <button type="submit" name="login" class="btn">Update</button>
        </form>
    </div>
</body>
</html>
