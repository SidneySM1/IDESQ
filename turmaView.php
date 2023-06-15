<?php
include('protect.php');
include('conexao.php');

$user_type = $_SESSION['user_type'];

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    
    <style>
        .container {
            margin-top: 30px;
        }


    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>


</head>
<body>
    <div class="container">
        <h2>Visualizar Turma</h2>

        <form method="POST" action="turmaViewSave.php">
            <input type="hidden" name="id" value="<?php echo $turmaID; ?>">
            <div class="mb-3">
                <label for="nomeTurma" class="form-label">Nome da Turma:</label>
                <input type="text" class="form-control" id="nomeTurma"name="nome" value="<?php echo $turma['nome']; ?>">
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
        <button type="submit" class="btn btn-primary">
        <i class="far fa-save"></i> Salvar</button>
    
  

    
    <h3>Alunos da turma</h3>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adicionarAlunoModal">
                <i class="fas fa-user-plus"></i>  Adicionar Aluno
</button>
    
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fas fa-user-plus"></i>Importar Alunos</button>


        <!-- Botão "Ativar Todos" -->
<a href="turmaAlunoStatus.php?action=ativar&turma_id=<?php echo $turmaID; ?>" class="btn btn-success"
   onclick="return confirm('Tem certeza de que deseja ativar todos os alunos desta turma?')">Ativar Todos</a>

<!-- Botão "Inativar Todos" -->
<a href="turmaAlunoStatus.php?action=inativar&turma_id=<?php echo $turmaID; ?>" class="btn btn-danger"
   onclick="return confirm('Tem certeza de que deseja inativar todos os alunos desta turma?')">Inativar Todos</a>

    











<!-- Modal de Importação de Alunos -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Importar Alunos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="turmaImport.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Selecione o arquivo:</label>
                        <input class="form-control" type="file" id="fileInput" name="fileInput">
                        <input type="hidden" name="turmaID" value="<?php echo $turmaID; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                <a href="modelos/importar.csv" class="btn btn-success"><i class="fa fa-download"></i> Baixar Modelo CSV
            </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
                <input type="hidden" name="turmaID" value="<?php echo $turmaID; ?>">
            </form>
        </div>
    </div>
</div>







<!-- Modal de adicionar aluno -->
<div class="modal fade" id="adicionarAlunoModal" tabindex="-1" role="dialog" aria-labelledby="adicionarAlunoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adicionarAlunoModalLabel">Adicionar Aluno</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulário de adicionar aluno -->
        <form method="POST" action="inserirAluno.php">
          <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>
          <div class="form-group">
            <label for="matricula">Matrícula:</label>
            <input type="text" class="form-control" id="matricula" name="matricula" required>
          </div>
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <input type="hidden" name="turma_id" value="<?php echo $turmaID; ?>">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Adicionar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Matricula</th>
                <th>Ultimo acesso</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($alunos as $aluno) {
                echo "<tr>";
                echo "<td>{$aluno['name']}</td>";
                echo "<td>{$aluno['matricula']}</td>";
                echo "<td>{$aluno['ultimo_acesso']}</td>";
                echo "<td>{$aluno['status']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    
    <?php if ($user_type === 'admin' || $user_type === 'gestor') { ?>
    <form method="POST" action="turmaDelete.php" onsubmit="return confirm('Tem certeza que deseja excluir esta turma?');">
        <input type="hidden" name="id" value="<?php echo $turmaID; ?>">
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Excluir Turma</button>
    </form>
<?php } ?>


</div>




<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




<script>
    $(document).ready(function() {
        $('#professores').multiSelect({
            selectableHeader: "<div class='custom-header'>Professores disponíveis</div>",
            selectionHeader: "<div class='custom-header'>Selecionados</div>",
        });
    });
        // Função para abrir a modal de adicionar aluno
        function abrirModalAdicionarAluno() {
        $('#modalAdicionarAluno').modal('show'); // Abre a modal
    }
    
  $(document).ready(function() {
    $('#fecharModal').click(function() {
      $('#adicionarAlunoModal').modal('hide');
    });
  });
</script>
</body>
</html>
