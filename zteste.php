<?php
$usuario = 'root';
$senha = '124679N@ruto';
$database = 'login';
$host = '5.183.11.55'; // IP do seu servidor

// Conectar ao banco de dados
$conn = new mysqli($host, $usuario, $senha, $database);

// Verificar se houve algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para selecionar os registros da tabela usuarios
$sql = "SELECT * FROM usuarios";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibir os resultados em tela
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . "<br>";
        echo "Nome: " . $row["name"] . "<br>";
        echo "Email: " . $row["email"] . "<br>";
        echo "<br>";
    }
} else {
    echo "Nenhum registro encontrado na tabela usuarios.";
}

// Fechar conexão com o banco de dados
$conn->close();
?>
