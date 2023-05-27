<?php
include('protect.php');
$id = $_SESSION['id'];
$name = $_SESSION['name'];
$user_pic = $_SESSION['user_pic'];
$user_type = $_SESSION['user_type'];
$turma = $_SESSION['turma'];

include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['comentario']) && !empty($_POST['comentario'])) {
    $videoId = $_POST['video_id'];
    $comentario = $_POST['comentario'];

    // Insere o novo comentário na tabela "comentarios"
    
$stmt = $mysqli->prepare("INSERT INTO comentarios (video_id, usuario_id, comentario, data_publicacao) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $videoId, $id, $comentario);
$stmt->execute();

// Verifica se ocorreu algum erro ao inserir o comentário
if ($stmt->errno) {
    die("Erro ao inserir o comentário: " . $stmt->error);
}

// Fecha o statement
$stmt->close();
   
  } else {
    echo "Por favor, preencha o campo de comentário.";
  }
}

if (isset($_GET['id'])) {
  // Obtém o ID do vídeo da URL
  $videoId = $_GET['id'];

  // Consulta SQL para obter os detalhes do vídeo selecionado
  $sql_video = "SELECT * FROM videos2 WHERE id = $videoId";
  $result_video = $mysqli->query($sql_video);

  // Verifica se o vídeo foi encontrado
  if ($result_video && $result_video->num_rows > 0) {
    $row_video = $result_video->fetch_assoc();
    $videoNome = $row_video['nome'];
    $videoDiretorio = $row_video['diretorio'];

    // Exibe o vídeo selecionado
    echo "<div>";
    echo "<h3>$videoNome</h3>";
    echo "<video width=\"320\" height=\"240\" controls>";
    echo "<source src=\"$videoDiretorio\" type=\"video/mp4\">";
    echo "Seu navegador não suporta o elemento video.";
    echo "</video>";
    echo "</div>";

    // Consulta SQL para obter os comentários do vídeo selecionado
    $sql_comentarios = "SELECT * FROM comentarios WHERE video_id = $videoId";
    $result_comentarios = $mysqli->query($sql_comentarios);

    // Verifica se há comentários encontrados
    if ($result_comentarios && $result_comentarios->num_rows > 0) {
      echo "<h4>Comentários:</h4>";

      // Loop através dos comentários encontrados
      while ($row_comentario = $result_comentarios->fetch_assoc()) {
        $comentario = $row_comentario['comentario'];
        $dataPublicacao = $row_comentario['data_publicacao'];
        $name = $row_comentario['usuario_id'];

        // // // Consulta SQL para obter o nome do usuário
$stmt_usuario = $mysqli->prepare("SELECT usuarios.name FROM usuarios INNER JOIN comentarios ON usuarios.id = comentarios.usuario_id WHERE comentarios.id = ?");
$stmt_usuario->bind_param("i", $comentario_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario && $result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();
    $name = $row_usuario['name'];

    // Exibe o comentário
    echo "<div>";
    echo "<p>Comentário por: $name</p>";
    echo "<p>$comentario</p>";
    echo "<p>Data de Publicação: $dataPublicacao</p>";
    echo "</div>";
}

$stmt_usuario->close();


        }
      } else {
        echo "<p>Nenhum comentário encontrado.</p>";
      }
      
      // Formulário para enviar um novo comentário
      echo "<h4>Enviar Comentário:</h4>";
      echo "<form method=\"POST\">";
      echo "<input type=\"hidden\" name=\"video_id\" value=\"$videoId\">";
      echo "<label for=\"comentario\">Comentário:</label>";
      echo "<textarea name=\"comentario\" id=\"comentario\" rows=\"4\" cols=\"50\"></textarea>";
      echo "<button type=\"submit\">Enviar</button>";
      echo "</form>";
    } else {
        echo "<p>Vídeo não encontrado.</p>";
        }
        } else {
        echo "<p>ID do vídeo não fornecido.</p>";
        }
        
        // Fecha a conexão com o banco de dados
        $mysqli->close();
        ?>      