<?php
    if (!empty($_GET['id'])) {
        include_once('conexao.php');
        include('protect.php');

        $id = $_GET['id'];

        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
        $result = $mysqli->query($sqlSelect);

        if ($result->num_rows > 0) {
            echo "<script>
                    if (confirm('Tem certeza de que deseja excluir este usuário?')) {
                        window.location.href = 'delete.php?confirm=true&id=$id';
                    } else {
                        window.location.href = 'usuarios.php';
                    }
                </script>";
        }
    }

    if (!empty($_GET['confirm']) && $_GET['confirm'] == 'true' && !empty($_GET['id'])) {
        include_once('conexao.php');

        $id = $_GET['id'];

        $sqlDeleteAluno = "DELETE FROM alunos_turmas WHERE aluno_id=$id";
        if ($mysqli->query($sqlDeleteAluno) === TRUE) {
            
        } else {
            echo "Erro ao excluir o usuário: " . $mysqli->error;
        }

        $sqlDelete = "DELETE FROM usuarios WHERE id=$id";
        if ($mysqli->query($sqlDelete) === TRUE) {
            header('Location: usuarios.php');
            exit();
        } else {
            echo "Erro ao excluir o usuário: " . $mysqli->error;
        }
    }
?>
