<?php
include 'db.php';

$query = $_GET['query'] ?? '';

$sql = "SELECT * FROM users WHERE username LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['phone_number']}</td>
            <td>{$row['first_name']}</td>
            <td>{$row['last_name']}</td>
            <td>{$row['gender']}</td>
            <td>
                <a href=\"javascript:editUser('{$row['id']}', '{$row['username']}', '{$row['phone_number']}', '{$row['first_name']}', '{$row['last_name']}', '{$row['gender']}\")\">Edit</a>
                <a href=\"dashboard.php?delete={$row['id']}\">Delete</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No users found</td></tr>";
}

$conn->close();
?>
