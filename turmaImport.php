<?php
include('conexao.php');
if (isset($_POST['turmaID']) && isset($_FILES['fileInput']['tmp_name'])) {
    $turmaID = $_POST['turmaID'];
    $filePath = $_FILES['fileInput']['tmp_name'];

    // Abrir o arquivo CSV
    $handle = fopen($filePath, 'r');

    if ($handle !== false) {
        // Ler o arquivo linha por linha
        $headerSkipped = false;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            // Ignorar a primeira linha (cabeçalho)
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            // Extrair os dados do aluno
            $nome = trim(mb_convert_encoding($data[0], 'UTF-8'));


            $matricula = $data[1];
            $username = $data[2];
            $password = $data[3];

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
        }

        // Fechar o arquivo
        fclose($handle);

        // Redirecionar de volta para a página da turma
        header("Location: turmaView.php?id=$turmaID");
        exit;
    }
}
?>
