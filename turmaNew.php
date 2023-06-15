<?php
include('conexao.php');
include('protect.php');

// Recuperar a lista de cursos
$query = "SELECT id, nome FROM cursos";
$result = mysqli_query($mysqli, $query);
$cursos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Recuperar a lista de professores
$query = "SELECT id, name FROM usuarios WHERE user_type = 'professor'";
$result = mysqli_query($mysqli, $query);
$professores = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Adicionar Turma</title>
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
        <h2>Adicionar Turma</h2>

        <form method="POST" action="turmaNewSave.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Turma</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="curso" class="form-label">Curso</label>
                <select id="curso" name="curso" class="form-select">
                    <?php foreach ($cursos as $curso) { ?>
                        <option value="<?php echo $curso['id']; ?>"><?php echo $curso['nome']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="professores" class="form-label">Professores (opcional)</label>
                <select id="professores" name="professores[]" multiple="multiple">
                    <?php foreach ($professores as $professor) { ?>
                        <option value="<?php echo $professor['id']; ?>"><?php echo $professor['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Adicionar Turma</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#professores').multiSelect();
        });
    </script>
</body>
</html>
