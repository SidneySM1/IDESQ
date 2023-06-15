php
<?php
// Conexão com o banco de dados
include('conexao.php');


// Obtenha os IDs do aluno e da turma
$aluno_id = $_GET['aluno_id'];
$turma_id = $_GET['turma_id'];

// Insira o aluno na tabela alunos_turmas
$query = "INSERT INTO alunos_turmas (aluno_id, turma_id) VALUES ('$aluno_id', '$turma_id')";
mysqli_query($mysqli, $query);

// Redirecione de volta para a página edit.php
header("Location: edit.php?id=$aluno_id");
?>