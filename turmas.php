<?php
include('conexao.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Turmas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Turmas</h2>

        <div class="col-md-6">
    <div class="input-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar turma ou curso">
        <button class="btn btn-primary" id="addTurmaButton">Adicionar Turma</button>
    </div>
</div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome da Turma</th>
                    <th>Nome do Curso</th>
                    <th>Professores Responsáveis</th>
                    <th>Ações</th> <!-- Nova coluna para o botão "Visualizar" -->
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                // Query para obter as turmas com seus respectivos professores e cursos
                $query = "SELECT turmas.id AS turma_id, turmas.nome AS nome_turma, cursos.nome AS nome_curso, GROUP_CONCAT(usuarios.name SEPARATOR ', ') AS nome_professores
                          FROM turmas
                          LEFT JOIN professores_turmas ON turmas.id = professores_turmas.turma_id
                          LEFT JOIN usuarios ON professores_turmas.professor_id = usuarios.id
                          INNER JOIN cursos ON turmas.curso_id = cursos.id
                          GROUP BY turmas.id";

                $result = mysqli_query($mysqli, $query);

                // Verifica se ocorreu um erro na consulta SQL
                if ($result === false) {
                    echo "Erro na consulta SQL: " . mysqli_error($mysqli);
                    exit;
                }

                // Verifica se há turmas no banco de dados
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $turmaID = $row['turma_id'];
                        $nomeTurma = $row['nome_turma'];
                        $nomeCurso = $row['nome_curso'];
                        $nomeProfessores = $row['nome_professores'];

                        // Exibe as informações da turma em uma linha da tabela
                        echo "<tr>";
                        echo "<td>$nomeTurma</td>";
                        echo "<td>$nomeCurso</td>";
                        echo "<td>$nomeProfessores</td>";
                        echo "<td><a href='turmaView.php?id=$turmaID' class='btn btn-primary'>Visualizar</a></td>"; // Botão "Visualizar"

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhuma turma encontrada.</td></tr>";
                }

                // Fecha a conexão com o banco de dados
                mysqli_close($mysqli);
                ?>
            </tbody>
        </table>

        
    </div>

    <script>
        // Função para filtrar as turmas de acordo com o valor da barra de pesquisa
        function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var tableBody = document.getElementById("tableBody");
            var rows = tableBody.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var tdNomeTurma = rows[i].getElementsByTagName("td")[0];
                var tdNomeCurso = rows[i].getElementsByTagName("td")[1];
                var tdNomeProfessores = rows[i].getElementsByTagName("td")[2];
                if (tdNomeTurma || tdNomeCurso || tdNomeProfessores) {
                var turma = tdNomeTurma.textContent || tdNomeTurma.innerText;
                var curso = tdNomeCurso.textContent || tdNomeCurso.innerText;
                var professores = tdNomeProfessores.textContent || tdNomeProfessores.innerText;

                if (turma.toLowerCase().indexOf(filter) > -1 || curso.toLowerCase().indexOf(filter) > -1 || professores.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }

    // Adiciona um evento de input à barra de pesquisa
    document.getElementById("searchInput").addEventListener("input", filterTable);

    // Evento de clique do botão "Adicionar Turma"
document.getElementById("addTurmaButton").addEventListener("click", function() {
    window.location.href = "turmaNew.php";
});
</script>
</body>
</html>
