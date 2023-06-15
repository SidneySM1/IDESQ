<?php
include('conexao.php');
include('protect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores enviados pelo formulário
    $nome = $_POST["nome"];
    $curso = $_POST["curso"];
    $professores = $_POST["professores"];

    // Obter o ID da turma
    $turmaID = $_POST["id"];

    // Excluir as ligações existentes na tabela "professores_turmas" para a turma
    $query = "DELETE FROM professores_turmas WHERE turma_id = '$turmaID'";
    mysqli_query($mysqli, $query);

    // Atualizar as informações da turma na tabela "turmas"
    $query = "UPDATE turmas SET nome = '$nome', curso_id = '$curso' WHERE id = '$turmaID'";
    mysqli_query($mysqli, $query);

    // Se houver professores selecionados, adicionar as novas ligações na tabela "professores_turmas"
    if (!empty($professores)) {
        foreach ($professores as $professorID) {
            $query = "INSERT INTO professores_turmas (professor_id, turma_id) VALUES ('$professorID', '$turmaID')";
            mysqli_query($mysqli, $query);
        }
    }

    // Redirecionar de volta para a página de turmas
    header("Location: turmas.php");
    exit();
}
?>
