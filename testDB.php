 <?php
$servername = "127.0.0.1";
$username = "reservasfgh";
$password = "cpsy3fLB";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
echo "<br />";
echo dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/reservasfgh/vendor/autoload.php';
?> 