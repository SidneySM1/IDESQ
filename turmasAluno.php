<?php
include_once('conexao.php');
// Obtenha o ID do aluno
$aluno_id = $_GET['id'];

// Obtenha todas as turmas da tabela turmas
$query = "SELECT * FROM turmas";
$result = mysqli_query($mysqli, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Turmas Aluno</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <h1>Turmas</h1>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ação</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td>
                    <?php
                    // Verifique se o aluno já está inscrito nesta turma
                    $query = "SELECT * FROM alunos_turmas WHERE aluno_id='$aluno_id' AND turma_id='{$row['id']}'";
                    $check_result = mysqli_query($mysqli, $query);
                    if (mysqli_num_rows($check_result) > 0) {
                        // O aluno já está inscrito nesta turma
                        echo "Inscrito";
                    } else {
                        // O aluno não está inscrito nesta turma
                        echo "<a href='adicionarTurma.php?turma_id={$row['id']}&aluno_id=$aluno_id'>Adicionar</a>";
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>