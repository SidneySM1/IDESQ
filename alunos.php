<?php
// Inclua o arquivo de proteção e verifique se o usuário tem permissão de acesso
include('protect.php');
include('conexao.php');

$user_type = $_SESSION['user_type'];
$id = $_SESSION['id'];

if ($user_type !== 'admin' && $user_type !== 'gestor' && $user_type !== 'professor') {
    header("Location: dashboard.php");
    exit;
}


// Verifique se o formulário foi enviado
// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os valores do formulário
    $name = $_POST["name"];
    $matricula = $_POST["matricula"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type_insert = $_POST["user_type_insert"];
    $turma = $_POST["turma"];

    // Realize a conexão com o banco de dados
    //$conn = new mysqli('localhost', 'seu_usuario', 'sua_senha', 'seu_banco_de_dados');

    // Verifique se ocorreu algum erro na conexão
    if ($mysqli->connect_error) {
        die("Falha na conexão: " . $mysqli->connect_error);
    }

    // Verifica o tipo de usuário antes de inserir
    if ($user_type_insert === "aluno") {
        // Prepare a declaração INSERT para inserir o novo usuário na tabela 'usuarios'
        $stmt = $mysqli->prepare("INSERT INTO usuarios (name, matricula, username, password, user_type, user_pic) VALUES (?, ?, ?, ?, ?, 4)");
        $stmt->bind_param("sssss", $name, $matricula, $username, $password, $user_type_insert);

        // Execute a declaração preparada
        if ($stmt->execute()) {
            $usuario_id = $stmt->insert_id; // Obtém o ID do usuário inserido

            // Verifica se a turma foi selecionada
            if (!empty($turma)) {
                // Insere o relacionamento entre o usuário e a turma na tabela 'alunos_turmas'
                $stmt2 = $mysqli->prepare("INSERT INTO alunos_turmas (aluno_id, turma_id) VALUES (?, ?)");
                $stmt2->bind_param("ii", $usuario_id, $turma);

                if ($stmt2->execute()) {
                    // Redirecione de volta para a página de usuários após a inclusão
                    header("Location: alunos.php");
                    exit;
                } else {
                    $error_message = "Erro ao incluir o usuário na tabela alunos_turmas: " . $stmt2->error;
                }

                $stmt2->close();
            } else {
                // Redirecione de volta para a página de usuários após a inclusão
                header("Location: alunos.php");
                exit;
            }
        } else {
            $error_message = "Erro ao incluir o usuário, verifique se a matricula e login já existem, ERRO:" . $stmt->error;
        }

        // Feche a declaração
        $stmt->close();
    } elseif ($user_type_insert === "professor") {
                // Prepare a declaração INSERT para inserir o novo usuário na tabela 'usuarios'
                $stmt = $mysqli->prepare("INSERT INTO usuarios (name, matricula, username, password, user_type, user_pic) VALUES (?, ?, ?, ?, ?, 3)");
                $stmt->bind_param("sssss", $name, $matricula, $username, $password, $user_type_insert);
        
                // Execute a declaração preparada
                if ($stmt->execute()) {
                    $usuario_id = $stmt->insert_id; // Obtém o ID do usuário inserido
        
                    // Verifica se a turma foi selecionada
                    if (!empty($turma)) {
                        // Insere o relacionamento entre o usuário e a turma na tabela 'alunos_turmas'
                        $stmt2 = $mysqli->prepare("INSERT INTO professores_turmas (professor_id, turma_id) VALUES (?, ?)");
                        $stmt2->bind_param("ii", $usuario_id, $turma);
        
                        if ($stmt2->execute()) {
                            // Redirecione de volta para a página de usuários após a inclusão
                            header("Location: alunos.php");
                            exit;
                        } else {
                            $error_message = "Erro ao incluir o usuário na tabela professores_turmas: " . $stmt2->error;
                        }
        
                        $stmt2->close();
                    } else {
                        // Redirecione de volta para a página de usuários após a inclusão
                        header("Location: alunos.php");
                        exit;
                    }
                } else {
                    $error_message = "Erro ao incluir o usuário, verifique se a matricula e login já existem, ERRO:" . $stmt->error;
                }
        
                // Feche a declaração
                $stmt->close();
    } elseif ($user_type_insert === "gestor") {
        // Prepare a declaração INSERT para inserir o novo usuário na tabela 'usuarios'
        $stmt = $mysqli->prepare("INSERT INTO usuarios (name, matricula, username, password, user_type, user_pic) VALUES (?, ?, ?, ?, ?, 2)");
        $stmt->bind_param("sssss", $name, $matricula, $username, $password, $user_type_insert);
    
        // Execute a declaração preparada
        if ($stmt->execute()) {
            // Redirecione de volta para a página de usuários após a inclusão
            header("Location: alunos.php");
            exit;
        } else {
            $error_message = "Erro ao incluir o usuário, verifique se a matricula e login já existem, ERRO: " . $stmt->error;
        }
    
        // Feche a declaração
        $stmt->close();
}
    else {
        // Prepare a declaração INSERT para inserir o novo usuário na tabela 'usuarios'
        $stmt = $mysqli->prepare("INSERT INTO usuarios (name, matricula, username, password, user_type, user_pic) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssss", $name, $matricula, $username, $password, $user_type_insert);
    
        // Execute a declaração preparada
        if ($stmt->execute()) {
            // Redirecione de volta para a página de usuários após a inclusão
            header("Location: alunos.php");
            exit;
        } else {
            $error_message = "Erro ao incluir o usuário, verifique se a matricula e login já existem, ERRO: " . $stmt->error;
        }
    
        // Feche a declaração
        $stmt->close();
    }
    
    // Feche a conexão
    //$mysqli->close();
}




















// Realize a conexão com o banco de dados
//$conn = new mysqli('localhost', 'seu_usuario', 'sua_senha', 'seu_banco_de_dados');

// Verifique se ocorreu algum erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}


$sql = "SELECT usuarios.id, usuarios.name, usuarios.matricula, usuarios.user_type, GROUP_CONCAT(turmas.nome SEPARATOR ', ') AS turmas, usuarios.status
    FROM usuarios
    LEFT JOIN alunos_turmas ON usuarios.id = alunos_turmas.aluno_id
    LEFT JOIN turmas ON alunos_turmas.turma_id = turmas.id
    LEFT JOIN professores_turmas ON usuarios.id = professores_turmas.professor_id
    WHERE user_type = 'aluno'";

$sql .= " GROUP BY usuarios.id, usuarios.name, usuarios.matricula, usuarios.user_type, usuarios.status";


// Verifique se há um termo de busca
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Adicione as condições de busca na consulta SQL
    $sql .= " HAVING usuarios.name LIKE '%$search%' OR usuarios.matricula LIKE '%$search%' OR usuarios.user_type LIKE '%$search%'";
}

// Execute a consulta SQL
$result = $mysqli->query($sql);

// Verifique se ocorreu algum erro na execução da consulta
if (!$result) {
    die("Erro na consulta: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <!-- Adicione os estilos CSS necessários -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Alunos cadastrados</h1>
            <!-- Adicione o formulário de busca -->
            <form method="GET" action="alunos.php">
    <div class="form-group">
        <label for="search">Buscar por Nome, Matrícula ou Tipo de Usuário:</label>
        <div class="input-group">
            <input type="text" class="form-control" id="search" name="search" placeholder="Digite o termo de busca">
            <div class="input-group-append">
                <!-- Botão de inclusão de usuário -->
                <?php if ($user_type === 'admin' || $user_type === 'gestor' ): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalIncluirUsuario">
                    <i class="fas fa-plus"></i> Incluir Usuário
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Buscar</button>
</form>
<!-- Exiba a mensagem de erro -->
<?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>$error_message</div>";
}
?>

    <!-- Modal de inclusão de usuário -->
    <div class="modal fade" id="modalIncluirUsuario" tabindex="-1" role="dialog" aria-labelledby="modalIncluirUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Incluir Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="alunos.php">
                        <div class="form-group">
                            <label for="name">Nome:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="matricula">Matrícula:</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" required>
                        </div>
                        <div class="form-group">
                            <label for="user_type_insert">Tipo de Usuário:</label>
                            <select class="form-control" id="user_type_insert" name="user_type_insert">
                            <?php if ($user_type === 'admin'): ?>
                                    <option value="admin">Admin</option>
                                <?php endif; ?>
                                <option value="gestor">Gestor</option>
                                <option value="professor">Professor</option>
                                <option value="aluno" selected>Aluno</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Login:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="turma">Turma:</label>
                            <select class="form-control" id="turma" name="turma">
                                
                            <?php
                                // Realize a conexão com o banco de dados
                                //$conn = new mysqli('localhost', 'seu_usuario', 'sua_senha', 'seu_banco_de_dados');

                                // Verifique se ocorreu algum erro na conexão
                                if ($mysqli->connect_error) {
                                    die("Falha na conexão: " . $mysqli->connect_error);
                                }

                                // Consulta SQL para obter todas as turmas
                                $turmaSql = "SELECT id, nome FROM turmas";

                                // Execute a consulta SQL das turmas
                                $turmaResult = $mysqli->query($turmaSql);

                                // Verifique se ocorreu algum erro na execução da consulta
                                if (!$turmaResult) {
                                    die("Erro na consulta das turmas: " . $mysqli->error);
                                }

                                // Verifique se há turmas retornadas pela consulta
                                if ($turmaResult->num_rows > 0) {
                                    // Loop através dos resultados e exiba as opções de turma
                                    while ($turmaRow = $turmaResult->fetch_assoc()) {
                                        echo "<option value='" . $turmaRow['id'] . "'>" . $turmaRow['nome'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Nenhuma turma encontrada</option>";
                                }

                                // Feche o resultado da consulta das turmas
                                $turmaResult->close();

                                // Feche a conexão com o banco de dados
                                $mysqli->close();
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Exiba a tabela de usuários -->
    <!-- Exiba a tabela de usuários -->
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Matrícula</th>
            <th>Turma's</th>
            <?php
            if ($user_type == 'admin' || $user_type == 'gestor'){
            echo '<th>Status</th>';
            }
            ?>
            <th>Ações</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        // Verifique se há resultados da consulta
        if ($result->num_rows > 0) {
            // Loop através dos resultados e exiba-os na tabela
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['user_type'] . "</td>";
                echo "<td>" . $row['matricula'] . "</td>";
                echo "<td>" . $row['turmas'] . "</td>";
                echo "<td>";
                if ($row['status'] === 'ativo') {
                    echo "<button type='button' class='btn btn-success btn-status' data-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>" . $row['status'] . "</button>";
                } else {
                    echo "<button type='button' class='btn btn-danger btn-status' data-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>" . $row['status'] . "</button>";
                }
                echo "</td>";
                echo "<td>
                <a class='btn btn-sm btn-primary' href='edit.php?id=$row[id]' title='Editar'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                    </a> 
                    <a class='btn btn-sm btn-danger' href='delete.php?id=$row[id]' title='Deletar'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                            <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                        </svg>
                    </a>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum usuário encontrado.</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>


<!-- Modal de edição de usuário -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="editar_usuario.php">
                    <input type="hidden" id="editUserId" name="id">

                    <div class="form-group">
                        <label for="editName">Nome:</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editMatricula">Matrícula:</label>
                        <input type="text" class="form-control" id="editMatricula" name="matricula" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserType">Tipo de Usuário:</label>
                        <select class="form-control" id="editUserType" name="user_type_insert">
                            <?php if ($user_type === 'admin'): ?>
                                    <option value="admin">Admin</option>
                                <?php endif; ?>
                            <option value="gestor">Gestor</option>
                            <option value="professor">Professor</option>
                            <option value="aluno">Aluno</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUsername">Login:</label>
                        <input type="text" class="form-control" id="editUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Password:</label>
                        <input type="password" class="form-control" id="editPassword" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="editTurma">Turma:</label>
                        <select class="form-control" id="editTurma" name="turma">
                            <!-- Opções de turma serão geradas dinamicamente no JavaScript -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Manipula o evento click do botão de editar
        $('.btn-editar').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            var userMatricula = $(this).data('matricula');
            var userTurmas = $(this).data('turmas');
            var userType = $(this).data('user-type');

            // Preenche os campos do formulário de edição com os dados do usuário selecionado
            $('#editUserId').val(userId);
            $('#editName').val(userName);
            $('#editMatricula').val(userMatricula);
            $('#editUserType').val(userType);
            $('#editUsername').val('');
            $('#editPassword').val('');

            // Remove as opções selecionadas anteriormente no campo de turma
            $('#editTurma').find('option').prop('selected', false);

            // Verifica se o usuário possui turmas
            if (userTurmas) {
                // Divide a lista de turmas em um array
                var turmas = userTurmas.split(', ');

                // Seleciona as opções de turma correspondentes aos valores do array
                $.each(turmas, function(index, turma) {
                    $('#editTurma option[value="' + turma + '"]').prop('selected', true);
                });
            }

            // Abre o modal de edição
            $('#modalEditarUsuario').modal('show');
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $('.btn-status').click(function() {
    var userId = $(this).data('id');
    var currentStatus = $(this).data('status');
    var newStatus = currentStatus === 'ativo' ? 'inativo' : 'ativo';

    $.ajax({
      url: 'update_status.php',
      type: 'POST',
      data: { id: userId, status: newStatus },
      success: function(response) {
        if (response === 'success') {
          // Atualiza o status na página
          $('#status-' + userId).text(newStatus);

          // Atualiza o atributo data-status
          $('.btn-status[data-id="' + userId + '"]').data('status', newStatus);

          // Altera o texto do botão
          $('.btn-status[data-id="' + userId + '"]').text(newStatus);

          // Exibe uma mensagem de sucesso
          //alert('Status atualizado com sucesso!');
          location.reload();
        } else {
          alert('Erro ao atualizar o status!');
        }
      },
      error: function() {
        alert('Erro na requisição. Por favor, tente novamente.');
      }
    });
  });
});

$(document).on('click', '.btn-excluir', function() {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        var id = $(this).data('id');
        // Aqui você pode adicionar a lógica para enviar uma requisição AJAX ao servidor e excluir o usuário do banco de dados
        // Você pode usar o ID do usuário recebido como parâmetro
        // Por exemplo, você pode usar o jQuery para enviar uma requisição POST usando o método $.post()
        // Exemplo:
        $.post('excluir_usuario.php', { id: id }, function(data) {
            // Ação após a exclusão do usuário
            // Por exemplo, recarregar a página para atualizar a tabela de usuários
            location.reload();
        });
    }
});
</script>
<style>
  .btn-status {
    width: 100%;
  }

  .btn-status.btn-success {
    background-color: green;
    border-color: green;
  }

  .btn-status.btn-danger {
    background-color: red;
    border-color: red;
  }
</style>
