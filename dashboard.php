<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle Create and Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $userId = $_POST['user_id'] ?? null;

    if ($userId) {
        // Update
        $sql = "UPDATE users SET username='$username', phone_number='$phone_number', first_name='$first_name', last_name='$last_name', gender='$gender' WHERE id=$userId";
    } else {
        // Create
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password, phone_number, first_name, last_name, gender) VALUES ('$username', '$password', '$phone_number', '$first_name', '$last_name', '$gender')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Record successfully saved";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id=$userId";

    if ($conn->query($sql) === TRUE) {
        echo "Record successfully deleted";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <a href="logout.php" class="logout">Logout</a>
        <h2>User Management</h2>

        <h3>Create / Update User</h3>
        <form method="POST" action="">
            <input type="hidden" name="user_id" id="user_id">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            
            <input type="submit" value="Save">
        </form>

        <h3>Search Users</h3>
        <input type="text" id="search" placeholder="Search by username">

        <h3>Users List</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <!-- User rows will be inserted here by AJAX -->
            </tbody>
        </table>

        <script>
        $(document).ready(function() {
            function loadUsers(query = '') {
                $.ajax({
                    url: "search.php",
                    method: "GET",
                    data: { query: query },
                    success: function(data) {
                        $('#userTable').html(data);
                    }
                });
            }

            $('#search').on('keyup', function() {
                let query = $(this).val();
                loadUsers(query);
            });

            // Load all users initially
            loadUsers();
        });

        function editUser(id, username, phone_number, first_name, last_name, gender) {
            document.getElementById('user_id').value = id;
            document.getElementById('username').value = username;
            document.getElementById('phone_number').value = phone_number;
            document.getElementById('first_name').value = first_name;
            document.getElementById('last_name').value = last_name;
            document.getElementById('gender').value = gender;
            document.getElementById('password').required = false;
        }
        </script>
    </div>
</body>
</html>
