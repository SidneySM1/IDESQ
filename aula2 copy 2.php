<?php

include('protect.php');


$name = $_SESSION['name'];
$user_pic = $_SESSION['user_pic'];
$user_type = $_SESSION['user_type'];
$turma = $_SESSION['turma'];


include('conexao.php');

// Verifica a conexão
if ($mysqli->connect_error) {
  die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Função para excluir um vídeo do banco de dados e da pasta correspondente
function excluirVideo($mysqli, $videoId, $diretorio) {
  // Consulta para obter o nome do arquivo a ser excluído
  $sql = "SELECT diretorio FROM videos2 WHERE id = $videoId";
  $result = $mysqli->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $arquivo = $row['diretorio'];

    // Exclui o vídeo do banco de dados
    $sql_delete = "DELETE FROM videos2 WHERE id = $videoId";
    if ($mysqli->query($sql_delete) === TRUE) {
      // Verifica se o caminho do arquivo contém o diretório
      if (strpos($arquivo, $diretorio) === 0) {
        $caminho_arquivo = $arquivo;
      } else {
        $caminho_arquivo = $diretorio . '/' . $arquivo;
      }

      // Exclui o vídeo da pasta correspondente
      if (file_exists($caminho_arquivo)) {
        unlink($caminho_arquivo);
      }

      echo "Vídeo excluído com sucesso.";
    } else {
      echo "Ocorreu um erro ao excluir o vídeo: " . $mysqli->error;
    }
  } else {
    echo "Vídeo não encontrado.";
  }
}
?>



<html lang="pt-BR">
<head>
  <link rel="icon" href="icon.png">




  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <link rel="stylesheet" href="dashboard.css">

  


</head>

<body class="body" style="margin-bottom: 60px;">
<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <a class="navbar-brand" href="#"><img src="logo.png" alt="Logo" class="navbar-logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <!-- Imagem do perfil do usuário -->
                <a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal">
                    <img src="profile/<?php echo $user_pic; ?>.png" alt="Imagem do perfil do usuário" class="perfil-image rounded-circle profile-modal-image">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-placement="bottom" data-target="#profileModal" title="<?php echo $user_type; ?>">
                    <?php echo $name; ?>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Relatórios
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Relatório 1</a>
                    <a class="dropdown-item" href="#">Relatório 2</a>
                    <a class="dropdown-item" href="#">Relatório 3</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Configurações</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Sair</a>
            </li>
        </ul>
    </div>
</nav>






    <!-- Modal do perfil do usuário -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Perfil do Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Conteúdo do modal -->
                <div class="text-center">
                    <img src="profile/<?php echo $user_pic; ?>.png" alt="Imagem do perfil do usuário" class="profile-modal-image rounded-circle">
                </div>
                <div class="social-media">
                    <!-- Redes sociais do usuário -->
                    <a href="#" class="social-media-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-media-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-media-link"><i class="fab fa-instagram"></i></a>
                    <a href="https://api.whatsapp.com/send?phone=SEUNUMERODOTELEFONE" target="_blank" class="social-media-link">
  <i class="fab fa-whatsapp"></i>
</a>

                </div>
                <p><?php echo $name; ?></p>
                <p>Usuario <?php echo $user_type; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>







    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <a class="navbar-brand" href="#"><img src="logo.png" alt="Logo" class="navbar-logo"></a>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(atual)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file"></span>
                                Relatórios
                            </a>
                        </li>
                        <?php if ($user_type === 'admin' || $user_type === 'gestor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="usuarios.php">
                                    <span data-feather="users"></span>
                                    Usuários
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($user_type === 'admin' || $user_type === 'professor'|| $user_type === 'gestor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="alunos.php">
                                    <span data-feather="users"></span>
                                    Alunos
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($user_type === 'admin' || $user_type === 'gestor'|| $user_type === 'professor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="turmas.php">
                                    <span data-feather="users"></span>
                                    Turmas
                                </a>
                            </li>
                            <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="my-custom-class">
                    <!-- conteúdo do seu <main> aqui -->
                    <div
                    style="position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; display: none;"
                    id="userTypeContainer">
                    <?php echo $user_type; ?>
                </div>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Compartilhar</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <canvas class="my-4 w-100" id="myChart" width="900" height="20"></canvas>

                <h2>Seção de conteúdo</h2><br>

                <div class="container-fluid">
                    















                
                    <?php


                    // Verifica se um arquivo foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verifica se é uma solicitação de exclusão
  if (isset($_POST['delete_id'])) {
    $videoId = $_POST['delete_id'];
    $diretorio = 'aula'; // substitua pelo diretório onde os vídeos estão armazenados

    // Chama a função para excluir o vídeo
    excluirVideo($mysqli, $videoId, $diretorio);
  } else {
    // Verifica se foi enviado um arquivo válido
    // Verifica se foi enviado um arquivo válido
if (isset($_FILES['video'])) {
    $diretorio = 'aula'; // substitua pelo diretório onde os vídeos serão armazenados
  
    // Verifica se houve algum erro no upload
    if ($_FILES['video']['error'] === UPLOAD_ERR_OK) {
      $nome_arquivo = $_FILES['video']['name'];
      $caminho_arquivo = $diretorio . '/' . $nome_arquivo;
  
      // Verifica a extensão do arquivo
      $extensoes_permitidas = array('mp4', 'avi', 'mov', 'wmv');
      $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
      if (in_array($extensao, $extensoes_permitidas)) {
        // Verifica se o vídeo já existe no banco de dados
        $sql_check = "SELECT id FROM videos2 WHERE nome = '$nome_arquivo'";
        $result_check = $mysqli->query($sql_check);
        if ($result_check->num_rows > 0) {
          echo "Já existe um vídeo com o mesmo nome.";
        } else {
          // Move o arquivo para o diretório
          if (move_uploaded_file($_FILES['video']['tmp_name'], $caminho_arquivo)) {
            // Insere o vídeo no banco de dados
            $sql_insert = "INSERT INTO videos2 (nome, diretorio) VALUES ('$nome_arquivo', '$caminho_arquivo')";
            if ($mysqli->query($sql_insert) === TRUE) {
              echo "Vídeo enviado com sucesso.";
            } else {
              echo "Ocorreu um erro ao enviar o vídeo: " . $mysqli->error;
            }
          } else {
            echo "Ocorreu um erro ao mover o arquivo para o diretório.";
          }
        }
      } else {
        echo "Formato de arquivo não suportado. Por favor, envie um arquivo de vídeo válido.";
      }
    } else {
      echo "Erro no upload do arquivo: " . $_FILES['video']['error'];
    }
  }
  
            }
            }
            
            // Consulta para obter todos os vídeos do banco de dados
            $sql_select = "SELECT * FROM videos2";
            $result_select = $mysqli->query($sql_select);
            ?>
            

            <div class="conteiner">
                <br>
              <h1>Vídeos</h1>
              <!-- Formulário para envio de vídeos -->
              <form method="POST" enctype="multipart/form-data">
                <label for="video">Enviar vídeo:</label>
                <input type="file" name="video" id="video" accept="video/*">
                <button type="submit">Enviar</button>
              </form>
              <!-- Tabela com a lista de vídeos -->




              <br>
              <div class="conteiner"> 

              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($result_select->num_rows > 0) {
                    while ($row = $result_select->fetch_assoc()) {
                      $videoId = $row['id'];
                      $videoNome = $row['nome'];
                      $videoDiretorio = $row['diretorio'];
                      echo "<tr>";
                      echo "<td>$videoId</td>";
                      echo "<td>$videoNome</td>";
                      echo "<td>";
                      echo "<a href=\"$videoDiretorio\" target=\"_blank\">Assistir</a> ";
                      echo "<form method=\"POST\" onsubmit=\"return confirm('Tem certeza de que deseja excluir este vídeo?')\">";
                      echo "<input type=\"hidden\" name=\"delete_id\" value=\"$videoId\">";
                      echo "<button type=\"submit\">Excluir</button>";
                      echo "</form>";
                      echo "</td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan=\"3\">Nenhum vídeo encontrado.</td></tr>";
                  }
                  ?>
                </tbody>
                </table>
              <?php
              // Fecha a conexão com o banco de dados
              $mysqli->close();
              ?>                



</div>
</div>











































                    
            
            </div>
    <style>
    .perfil-image {
        max-height: 30px;
        max-width: none;
    }
    #profileModal .modal-body .profile-modal-image {
    max-width: 100%;
    max-height: 200px; /* Defina o tamanho máximo desejado */
    }
    .card:hover {
        cursor: pointer;
    }
    .card {
        
        height: 200px;
    }
</style>


</main>
</div>
</div>
<style>
    .perfil-image {
        max-height: 30px;
        max-width: none;
    }
    #profileModal .modal-body .profile-modal-image {
    max-width: 100%;
    max-height: 200px; /* Defina o tamanho máximo desejado */
}
</style>
<footer class="footer">
    <p>Teste de desenvolvimento, Sidney Mota</p>
</footer>
</div>
</main>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    function redirectToMateriais() {
        window.location.href = "materiais.php";
    }
    function redirectToAtividade() {
        window.location.href = "atividade_create.php";
    }

</script>

</body>
</html>
