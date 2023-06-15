<?php
include('protect.php');
include('conexao.php');

// Aqui você precisa ter a lógica para verificar o usuário logado e obter a turma do aluno
// $user_type = 'aluno'; // Exemplo: Definindo o tipo de usuário como aluno
// $turma_aluno = 'TIAI2316'; // Exemplo: Definindo a turma do aluno
$name = $_SESSION['name'];
$user_pic = $_SESSION['user_pic'];
$user_type = $_SESSION['user_type'];
$turma = $_SESSION['turma'];
// Lógica para obter as atividades atribuídas à turma do aluno
// Substitua esta consulta pela sua lógica de consulta ao banco de dados
$sql = "SELECT * FROM atividades WHERE turma_id = '$turma'";
$result = mysqli_query($mysqli, $sql);

// Verificar se ocorreu um erro na consulta
if ($result === false) {
    die("Erro na consulta: " . mysqli_error($mysqli));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Atividades - Aluno</title>
</head>
<body>
    <h1>Atividades - Aluno</h1>

    <?php
    // Verificar se há atividades atribuídas à turma do aluno
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $atividade_id = $row['id'];
            $atividade_titulo = $row['titulo'];
            $atividade_descricao = $row['descricao'];
            ?>

            <div class="atividade">
                <h3><?php echo $atividade_titulo; ?></h3>
                <p><?php echo $atividade_descricao; ?></p>
                <!-- Formulário para a resposta da atividade -->
                <form action="atividade_resp.php" method="POST">
                    <input type="hidden" name="atividade_id" value="<?php echo $atividade_id; ?>">
                    <textarea name="resposta" rows="4" cols="50" placeholder="Digite sua resposta aqui"></textarea>
                    <button type="submit">Responder</button>
                </form>
            </div>

            <?php
        }
    } else {
        echo "Nenhuma atividade encontrada para a sua turma.";
    }
    ?>

</body>
</html>