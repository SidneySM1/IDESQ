<?php
include_once('conexao.php');

if(isset($_GET['id']) && isset($_GET['aluno_id']))
{
    $id = $_GET['id'];
    $aluno_id = $_GET['aluno_id'];

    // Verifica se a turma e o aluno existem
    $sqlVerificaTurma = "SELECT * FROM turmas WHERE id = $id";
    $sqlVerificaAluno = "SELECT * FROM usuarios WHERE id = $aluno_id";
    $resultTurma = $mysqli->query($sqlVerificaTurma);
    $resultAluno = $mysqli->query($sqlVerificaAluno);

    if($resultTurma->num_rows > 0 && $resultAluno->num_rows > 0)
    {
        // Exibe a confirmação apenas se não for uma solicitação de confirmação
        if (!isset($_GET['confirmacao'])) {
            echo "<script>
                if(confirm('Tem certeza que deseja remover essa turma?')) {
                    // Remove a associação do aluno com a turma
                    var url = 'removerTurma.php?id=' + $id + '&aluno_id=' + $aluno_id + '&confirmacao=1';
                    window.location.href = url;
                } else {
                    // Redireciona de volta à página de edição
                    var url = 'edit.php?id=' + $aluno_id;
                    window.location.href = url;
                }
            </script>";
        } else {
            // Remove a associação do aluno com a turma
            $sqlRemoverTurma = "DELETE FROM alunos_turmas WHERE aluno_id = $aluno_id AND turma_id = $id";
            if ($mysqli->query($sqlRemoverTurma) === TRUE) {
                // Redireciona de volta à página de edição
                $url = "edit.php?id=$aluno_id";
                header("Location: $url");
                exit();
            } else {
                echo "Erro ao remover a turma: " . $mysqli->error;
            }
        }
    }
    else
    {
        echo "Turma ou aluno não encontrados";
    }
}
else
{
    echo "Parâmetros inválidos";
}
?>
