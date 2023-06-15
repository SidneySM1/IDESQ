<?php
include_once('conexao.php');
include_once('protect.php');
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $senha = $_POST['senha'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $sexo = $_POST['genero'];
    $data_nasc = $_POST['data_nascimento'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $bio2 = $_POST['bio'];
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    // Lidar com o upload da foto de perfil
    if (!empty($_FILES['profile_pic']['name'])) {
        $formatP = array("png", "jpg", "jpeg", "gif");
        $extensao = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

        if (in_array($extensao, $formatP)) {
            $pasta = "Profile/";
            $temporario = $_FILES['profile_pic']['tmp_name'];
            $newPicName = $mysqli->query("SELECT MAX(user_pic) FROM usuarios")->fetch_row()[0] + 1;
            $novoNome = "$newPicName.$extensao";

            if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                $mysqli->query("UPDATE usuarios SET user_pic='$newPicName' WHERE id=$id");
            } else {
                echo "Erro, não foi possivel fazer o upload do arquivo!";
            }
        } else {
            echo "Formato inválido";
        }
    }

    // Atualiza os dados do usuário no banco de dados
    $sqlUpdate = "UPDATE usuarios SET password='$senha', email='$email', telefone='$telefone', genero='$sexo', data_nascimento='$data_nasc', cidade='$cidade', estado='$estado', bio='$bio2' WHERE id=$id";
    $result = $mysqli->query($sqlUpdate);

    if ($result) {
        // Redireciona de volta para a página de sistema.php após a atualização
        header('Location: logout.php');
    } else {
        echo "Ocorreu um erro ao atualizar os dados do usuário.";
    }
} else {
    header('Location: usuarios.php');
}
?>
