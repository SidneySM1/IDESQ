<?php
include('conexao.php');

// Verifique se o arquivo foi enviado
if(isset($_POST["submit"])) {
  // Obtenha o valor do ID do usuário
  $current_user_id = $_POST['id'];

  // Verifique se o arquivo é uma imagem
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    // Verifique se o tamanho do arquivo está dentro do limite permitido
    if ($_FILES["fileToUpload"]["size"] <= 5242880) {
      // Conecte-se ao banco de dados e execute a consulta para obter o maior valor de user_pic
      //$conn = new mysqli($servername, $username, $password, $dbname);
      $sql = "SELECT MAX(user_pic) as max_user_pic FROM usuarios";
      $result = $mysqli->query($sql);
      $row = $result->fetch_assoc();
      $new_user_pic = $row["max_user_pic"] + 1;

      // Salve a nova imagem no servidor com o nome do novo valor de user_pic
      $target_dir = "Profile/";
      $target_file = $target_dir . $new_user_pic . ".png";
      if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Execute a consulta para atualizar o valor de user_pic para o usuário atual
        $sql = "UPDATE usuarios SET user_pic=$new_user_pic WHERE id=$current_user_id";
        if($mysqli->query($sql)) {
          // Redirecione de volta para a página edit.php com o parâmetro id
          header("Location: edit.php?id=$current_user_id");
          exit();
        } else {
          echo "Erro ao atualizar o valor de user_pic: " . $mysqli->error;
        }
      } else {
        echo "Erro ao salvar a nova imagem.";
      }
    } else {
      echo "Desculpe, seu arquivo é muito grande.";
    }
  } else {
    echo "Desculpe, apenas arquivos de imagem são permitidos.";
  }
}
?>
