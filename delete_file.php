<?php
// Verifica se o arquivo foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtém o nome do arquivo a ser excluído
  $file_name = $_POST['file_name'];

  // Diretório dos materiais
  $diretorio = 'materiais/';

  // Caminho completo do arquivo
  $file_path = $diretorio . $file_name;

  // Verifica se o arquivo existe
  if (file_exists($file_path)) {
    // Tenta excluir o arquivo
    if (unlink($file_path)) {
      echo "Arquivo excluído com sucesso.";
    } else {
      echo "Ocorreu um erro ao excluir o arquivo.";
    }
  } else {
    echo "O arquivo não existe.";
  }
} else {
  echo "Acesso não autorizado.";
}



// Define a mensagem de exclusão na variável de sessão
session_start();
$_SESSION['exclusao_sucesso'] = 'Arquivo excluído com sucesso!';

// Redireciona de volta para a página materiais.php
header('Location: materiais.php');
exit();



?>
