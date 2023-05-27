<?php

include('conexao.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');




if (isset($_POST['username']) && isset($_POST['password'])) {
    // Filtro de entrada de dados para evitar SQL injection
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);


    // Conexão com o banco de dados
    $conn = mysqli_connect("localhost:3306", "root", "", "login");

    $sql_code = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
    $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->$error);


    // Verificação da conexão com o banco de s
    if (!$conn) {
        die("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
    }

    // Consulta SQL para verificar as credenciais do usuário
    $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificação das credenciais do usuário
    if (mysqli_num_rows($result) == 1) {

        
        $usuario = $sql_query->fetch_assoc();

        if(!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['id'] = $usuario['id'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['name'] = $usuario['name'];
        $_SESSION['user_pic'] = $usuario['user_pic'];
        $_SESSION['user_type'] = $usuario['user_type'];
        $_SESSION['turma'] = $usuario['turma'];

        header("Location: dashboard.php");
    } else {
        //echo "Usuário ou senha inválidos.";
        //header("Location: login-invalid.html");
        $error_message = "Usuário ou senha inválidos.";
        
    }
    
}


















// Verificar se o usuário está acessando do dispositivo móvel
function isMobileDevice() {
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']);
}

// Verificar se o usuário está acessando de um dispositivo móvel
if (isMobileDevice()) {
    // Conteúdo da página para dispositivos móveis
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon.png">
        <title>Login - Dispositivo Móvel</title>
        <style>
            /* Estilos para dispositivos m */
            body {
                background-color: #f2f2f2;
                text-align: center;
            }

            h1 {
                margin-top: 50px;
                font-size: 24px;
            }

            .login {
                margin: 50px auto;
                width: 90%;
                max-width: 300px;
                padding: 20px;
                background-color: #f4f4f4;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

                padding: 20px;
                border: 1px solid #ccc;
            }
            

            .form-group {
                margin-bottom: 10px;
            }

            label {
                display: block;
                font-weight: bold;
            }

            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            button[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            button[type="submit"]:hover {
                background-color: #0069d9;
            }




            .login img {
                width: 100px;
                height: auto;
                margin: auto;
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            input[type="text"],
            input[type="password"] {
                width: 90%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 10px;
                border-radius: 5px;
                margin-top: 10px;
            }

            .form-group label {
                text-align: left;
            }

            footer {

                position: absolute;
                bottom: 0;
                width: 100%;
                height: 35px;
                background-color: #fff;
                color: rgb(87, 78, 78);
                font-size: 14px;
                font-family: 'Open Sans', sans-serif;
                padding: 10px;
                border-top: 1px solid #ccc;
                text-align: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <h1>mobile</h1>
        <div class="login">
        <img src="logo.png" alt="Logo da empresa">
        <h1>Login</h1>
        <?php if (isset($error_message)) { ?>
        <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>

            </form>
        </div>


        <footer>
    <p>Teste de desenvolvimento, Sidney Mota</p>
  </footer>
        </body>
    </html>
    <?php
} else {
    // Conteúdo da página para desktop
    ?>
        <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon.png">
        <title>Login</title>
        <style>
            /* Estilos para dispositivos m */
            body {
                background-color: #f2f2f2;
                text-align: center;
            }

            h1 {
                margin-top: 50px;
                font-size: 24px;
            }

            .login {
                margin: 50px auto;
                width: 90%;
                max-width: 300px;
                padding: 20px;
                background-color: #f4f4f4;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

                padding: 20px;
                border: 1px solid #ccc;
            }
            

            .form-group {
                margin-bottom: 10px;
            }

            label {
                display: block;
                font-weight: bold;
            }

            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            button[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            button[type="submit"]:hover {
                background-color: #0069d9;
            }




            .login img {
                width: 100px;
                height: auto;
                margin: auto;
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            input[type="text"],
            input[type="password"] {
                width: 90%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 10px;
                border-radius: 5px;
                margin-top: 10px;
            }

            .form-group label {
                text-align: left;
            }
            footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 35px;
                background-color: #fff;
                color: rgb(87, 78, 78);
                font-size: 14px;
                font-family: 'Open Sans', sans-serif;
                padding: 10px;
                border-top: 1px solid #ccc;
                text-align: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="login">
        <img src="logo.png" alt="Logo da empresa">
        <h1>Login</h1>
        <?php if (isset($error_message)) { ?>
        <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>

            </form>
        </div>


        <footer>
    <p>Teste de desenvolvimento, Sidney Mota</p>
  </footer>
        </body>
    </html>
    <?php
}
?>