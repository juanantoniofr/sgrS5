 <?php
$servername = "localhost";
$username = "reservasfgh";
$password = "cpsy3fLB";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?> 