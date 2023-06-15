<?php
include('conexao.php');

if (isset($_POST['id'])) {
    $turmaID = $_POST['id'];

    // Excluir turma da tabela professores_turmas
    $query = "DELETE FROM professores_turmas WHERE turma_id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);

    // Verificar se a exclusão foi bem-sucedida
    if (!$result) {
        echo "Erro ao excluir turma da tabela professores_turmas: " . mysqli_error($mysqli);
        exit;
    }

    // Excluir turma da tabela alunos_turmas
    $query = "DELETE FROM alunos_turmas WHERE turma_id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);

    // Verificar se a exclusão foi bem-sucedida
    if (!$result) {
        echo "Erro ao excluir turma da tabela alunos_turmas: " . mysqli_error($mysqli);
        exit;
    }

    // Excluir turma da tabela turmas
    $query = "DELETE FROM turmas WHERE id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);

    // Verificar se a exclusão foi bem-sucedida
    if (!$result) {
        echo "Erro ao excluir turma da tabela turmas: " . mysqli_error($mysqli);
        exit;
    }

    echo "Turma excluída com sucesso!";
    header("Location: turmas.php");
} else {
    echo "ID da turma não fornecido.";
}
?>
