<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['alunos'])) {
    $arquivo = $_FILES['alunos']['tmp_name'];
    $extensao = pathinfo($_FILES['alunos']['name'], PATHINFO_EXTENSION);

    // Verifica a extensão do arquivo
    if ($extensao == 'csv') {
        // Lê o arquivo CSV e insere os alunos na tabela usuarios
        if (($handle = fopen($arquivo, 'r')) !== false) {
            // Ignora a primeira linha (cabeçalho)
            fgetcsv($handle);
        
            $importedStudents = 0;
        
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) >= 4) {
                    $nome = $data[0];
                    $matricula = $data[1];
                    $username = $data[2];
                    $password = $data[3];
        
                    // Resto do código para inserir o aluno na tabela "usuarios" e vinculá-lo à turma
        
                    $importedStudents++;
                } else {
                    fclose($handle);
                    echo "Dados inválidos no arquivo CSV.";
                    exit;
                }
            }
        
            fclose($handle);
        
            echo "Foram importados {$importedStudents} alunos com sucesso!";
        } else {
            echo "Erro ao ler o arquivo CSV.";
        }
         
    } else {
        echo "Formato de arquivo inválido. Por favor, envie um arquivo CSV.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Importar Alunos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Importar Alunos</h2>

        <form method="POST" action="turmaImport.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="alunos" class="form-label">Selecione o arquivo de alunos (CSV):</label>
                <input type="file" class="form-control" id="alunos" name="alunos" required accept=".csv">
            </div>

            <button type="submit" class="btn btn-primary">Importar Alunos</button>
        </form>
    </div>
</body>
</html>
