<?php
include('conexao.php');

if (isset($_GET['id'])) {
    $turmaID = $_GET['id'];

    // Obter informações da turma
    $query = "SELECT turmas.*, cursos.nome AS nome_curso FROM turmas INNER JOIN cursos ON turmas.curso_id = cursos.id WHERE turmas.id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);
    $turma = mysqli_fetch_assoc($result);

    if (!$turma) {
        echo "Turma não encontrada.";
        exit;
    }

    // Obter informações dos alunos da turma
    $query = "SELECT usuarios.* FROM usuarios INNER JOIN alunos_turmas ON usuarios.id = alunos_turmas.aluno_id WHERE alunos_turmas.turma_id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);

    // Verificar se a consulta foi bem-sucedida
    if ($result) {
        $alunos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "Erro na consulta SQL: " . mysqli_error($mysqli);
        exit;
    }

    // Obter lista de todos os professores
    $query = "SELECT id, name FROM usuarios WHERE user_type = 'professor'";
    $result = mysqli_query($mysqli, $query);
    $professores = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Obter lista de professores vinculados à turma
    $query = "SELECT professor_id FROM professores_turmas WHERE turma_id = '$turmaID'";
    $result = mysqli_query($mysqli, $query);
    $professoresVinculados = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Criar um array com os IDs dos professores vinculados à turma
    $professoresVinculadosIDs = array();
    foreach ($professoresVinculados as $professorVinculado) {
        $professoresVinculadosIDs[] = $professorVinculado['professor_id'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visualizar Turma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css">
    <style>
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Visualizar Turma</h2>

        <form method="POST" action="turmaViewSave.php">
    <input type="hidden" name="id" value="<?php echo $turmaID; ?>">
            <div class="mb-3">
                <label for="nomeTurma" class="form-label">Nome da Turma:</label>
                <input type="text" class="form-control" id="nomeTurma" name="nome" value="<?php echo $turma['nome']; ?>">

            </div>
            <div class="mb-3">
                <label for="curso" class="form-label">Curso</label>
                <select id="curso" name="curso" class="form-select">
                    <?php
                    // Obter informações dos cursos
                    $query = "SELECT * FROM cursos";
                    $result = mysqli_query($mysqli, $query);
                    // Verificar se a consulta foi bem-sucedida
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['id'] == $turma['curso_id']) ? "selected" : "";
                        echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                    }
                } else {
                    echo "Erro na consulta SQL: " . mysqli_error($mysqli);
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="professores" class="form-label">Professores (opcional)</label>
            <select id="professores" name="professores[]" multiple="multiple">
                <?php foreach ($professores as $professor) { ?>
                    <?php
                    $selected = in_array($professor['id'], $professoresVinculadosIDs) ? "selected" : "";
                    ?>
                    <option value="<?php echo $professor['id']; ?>" <?php echo $selected; ?>><?php echo $professor['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
    
    <h3>Usuários Inseridos na Turma</h3>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                
                <th>Sexo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($alunos as $aluno) {
                echo "<tr>";
                echo "<td>{$aluno['name']}</td>";
                
                echo "<td>{$aluno['genero']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
<script>
    $(document).ready(function() {
        $('#professores').multiSelect({
            selectableHeader: "<div class='custom-header'>Professores disponiveis</div>",
  selectionHeader: "<div class='custom-header'>Selecionados</div>",
 
        });
    });
</script>
</body>
</html>
