<?php
include('conexao.php');
include('protect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores enviados pelo formulário
    $nome = $_POST["nome"];
    $curso = $_POST["curso"];
    $professores = $_POST["professores"];

    // Obter o novo ID da turma
    $query = "SELECT MAX(id) as max_id FROM turmas";
    $result = mysqli_query($mysqli, $query);
    $row = mysqli_fetch_assoc($result);
    $novaTurmaID = $row['max_id'] + 1;

    // Inserir a turma na tabela "turmas"
    $query = "INSERT INTO turmas (id, nome, curso_id) VALUES ('$novaTurmaID', '$nome', '$curso')";
    mysqli_query($mysqli, $query);

    // Se houver professores selecionados, inserir na tabela "professores_turmas"
    if (!empty($professores)) {
        foreach ($professores as $professorID) {
            $query = "INSERT INTO professores_turmas (professor_id, turma_id) VALUES ('$professorID', '$novaTurmaID')";
            mysqli_query($mysqli, $query);
        }
    }

    // Redirecionar de volta para a página de turmas
    header("Location: turmas.php");
    exit();
}
?>
