<?php
include('conexao.php');
// Verificar se o formulário foi submetido


if (isset($_POST['nome']) && isset($_POST['matricula']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['turma_id'])) {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $turmaID = $_POST['turma_id'];

    // Verificar se a matrícula já existe no banco de dados
    $query = "SELECT id FROM usuarios WHERE matricula = '$matricula'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
        // A matrícula já existe, vincular o aluno à turma se ainda não estiver vinculado
        $alunoID = mysqli_fetch_assoc($result)['id'];

        $query = "SELECT * FROM alunos_turmas WHERE aluno_id = '$alunoID' AND turma_id = '$turmaID'";
        $result = mysqli_query($mysqli, $query);
    
        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO alunos_turmas (aluno_id, turma_id) VALUES ('$alunoID', '$turmaID')";
            mysqli_query($mysqli, $query);
        }
    } else {
        // A matrícula não existe, inserir o novo aluno e vinculá-lo à turma
        $query = "INSERT INTO usuarios (name, matricula, username, password) VALUES ('$nome', '$matricula', '$username', '$password')";
        mysqli_query($mysqli, $query);
    
        $alunoID = mysqli_insert_id($mysqli);
    
        $query = "INSERT INTO alunos_turmas (aluno_id, turma_id) VALUES ('$alunoID', '$turmaID')";
        mysqli_query($mysqli, $query);
    }
    
    header("Location: turmaView.php?id=$turmaID"); // Redirecionar de volta para a página turmaView.php
    exit();
}
?>    