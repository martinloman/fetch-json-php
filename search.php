<?php

$servername = "localhost"; // kopplar till din lokala databas som körs i xampp
$username = "root";
$password = "";
$db = "myDB"; //använd namnet på din databas

$conn = mysqli_connect($servername, $username, $password, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$username_search = "%" . $_GET["q"] . "%";

$stmt = $conn->prepare("SELECT * FROM users where username LIKE ?");
$stmt->bind_param("s", $username_search);
$stmt->execute();
$result = $stmt->get_result();


$result_arr = [];
if ($result->num_rows > 0) {
  // output data of each row
  while ($row = $result->fetch_assoc()) {
    array_push($result_arr, [
      "id" => $row["id"],
      "name" => $row["username"]
    ]);
  }
}
$conn->close();


// Headern Content-Type sätts för att den som tar emot resultatet ska veta vad det är för innehåll.
header('Content-Type: application/json; charset=utf-8');

// Vi skriver ut arrayen med svar, json-kodad (som ett json-objekt).
echo json_encode($result_arr);
