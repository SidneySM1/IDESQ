<?php
include('conexao.php');

// Verifica se a ação e o ID da turma foram fornecidos
if (isset($_GET['action']) && isset($_GET['turma_id'])) {
    // Obtém a ação e o ID da turma
    $action = $_GET['action'];
    $turmaID = $_GET['turma_id'];

    // Verifica se a ação é válida
    if ($action == 'ativar' || $action == 'inativar') {
        // Conecte-se ao banco de dados (substitua os valores com as suas configurações)
        



        // Define o status com base na ação
        $status = ($action == 'ativar') ? 1 : 2;

        // Prepara a consulta SQL para atualizar o status dos alunos
        $sql = "UPDATE usuarios SET status = $status WHERE id IN (
                    SELECT aluno_id FROM alunos_turmas WHERE turma_id = $turmaID
                )";

        // Executa a consulta de atualização
        if ($mysqli->query($sql) === TRUE) {
            // Redireciona de volta para a página da turma
            header("Location: turmaView.php?id=$turmaID");
            exit();
        } else {
            echo "Erro ao atualizar o status dos alunos: " . $mysqli->error;
        }

        // Fecha a conexão com o banco de dados
        $mysqli->close();
    } else {
        echo "Ação inválida.";
    }
} else {
    echo "Ação ou ID da turma não fornecidos.";
}
?>
