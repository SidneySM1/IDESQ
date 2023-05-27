
<?php
include('protect.php');
include('conexao.php');

// Verifica se o arquivo foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtém o nome do arquivo a ser excluído
  $file_name = $_POST['file_name'];

  // Diretório dos materiais
  $diretorio = 'aula/';

  // Caminho completo do arquivo
  $file_path = $diretorio . $file_name;

  // Verifica se o arquivo existe
  if (file_exists($file_path)) {
    // Tenta excluir o arquivo
    if (unlink($file_path)) {
      // Arquivo excluído com sucesso

      // Conexão com o banco de dados


      // Verifica a conexão
      if ($mysqli->connect_error) {
        die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
      }

      // Prepara a consulta para excluir a aula do banco de dados
      $sql = "DELETE FROM videos WHERE nome_arquivo = '$file_name'";

      // Executa a consulta
      if ($mysqli->query($sql) === TRUE) {
        // Aula excluída do banco de dados com sucesso

        // Define a mensagem de exclusão na variável de sessão
        session_start();
        $_SESSION['exclusao_sucesso'] = 'Arquivo excluído com sucesso!';

        // Fecha a conexão com o banco de dados
        $mysqli->close();

        // Redireciona de volta para a página materiais.php
        header('Location: aula.php');
        exit();
      } else {
        echo "Ocorreu um erro ao excluir a aula do banco de dados: " . $mysqli->error;
      }
    } else {
      echo "Ocorreu um erro ao excluir o arquivo.";
    }
  } else {
    echo "O arquivo não existe.";
  }
} else {
  echo "Acesso não autorizado.";
}
?>
