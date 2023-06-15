<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userId = $_POST["id"];
  $status = $_POST["status"];

  $stmt = $mysqli->prepare("UPDATE usuarios SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $userId);

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }

  $stmt->close();
  $mysqli->close();
  
}
?>
