<?php
    include_once('conexao.php');
    include('protect.php');

    // Verifique se o usuário está logado
    if (!isset($_SESSION['user_type'])) {
        // O usuário não está logado, redirecione para a página de login
        header("Location: login.php");
        exit;
    }
    
    // Verifique o tipo de usuário
    $user_type = $_SESSION['user_type'];
    
    // Verifique se o tipo de usuário é "admin" ou "gestor"
    if ($user_type !== 'admin' && $user_type !== 'gestor') {
        // O tipo de usuário não é permitido, redirecione para uma página de acesso negado ou qualquer outra página apropriada
        header("Location: acesso-negado.php");
        exit;
    }
    if(!empty($_GET['id']))
    {
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
        $result = $mysqli->query($sqlSelect);
        if($result->num_rows > 0)
        {
            while($user_data = mysqli_fetch_assoc($result))
            {
                $id2 = $user_data['id'];
                $name = $user_data['name'];
                $senha = $user_data['password'];
                $username = $user_data['username'];
                $email = $user_data['email'];
                $telefone = $user_data['telefone'];
                $sexo = $user_data['genero'];
                $data_nasc = $user_data['data_nascimento'];
                $cidade = $user_data['cidade'];
                $estado = $user_data['estado'];
                $user_pic2 = $user_data['user_pic'];
                $user_type2 = $user_data['user_type'];
                $bio2 = $user_data['bio'];

            }
        }
        else
        {
            header('Location: usuarios.php');
        }
    }
    else
    {
        header('Location: usuarios.php');
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário | GN</title>
    <!-- Adicione o link para o arquivo CSS do Bootstrap -->
    <!-- Link para o arquivo CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link para a biblioteca Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-3WB1Wf1R+Xn9+I6fLx9zJQx+KH5uV5vMBSjYk1gN2JzMyr+LP+zH7UHqiih5tz5E" crossorigin="anonymous">

    <style>
    .perfil-image {
        max-height: 30px;
        max-width: none;
    }
    #profileModal .modal-body .profile-modal-image {
    max-width: 100%;
    max-height: 200px; /* Defina o tamanho máximo desejado */
    }
    </style>
    
<script>
$(document).ready(function() {
  // Ao enviar o formulário de busca, realizar a busca de turmas
  $('#buscaTurmaForm').submit(function(e) {
    e.preventDefault();
    var buscaTurma = $('#buscaTurmaInput').val();

    $.ajax({
      url: 'buscarturma.php',
      type: 'GET',
      data: { buscaTurma: buscaTurma },
      dataType: 'json',
      success: function(response) {
        // Limpar a lista de resultados
        $('#resultadoBuscaTurma').empty();

        // Exibir os resultados da busca
        if (response.length > 0) {
          response.forEach(function(turma) {
            var turmaId = turma.id;
            var turmaNome = turma.nome;

            var itemTurma = $('<div>').addClass('form-check');
            var labelTurma = $('<label>').addClass('form-check-label').text(turmaNome);
            var linkAdicionar = $('<a>').attr('href', 'adicionarturma.php?aluno_id=<?php echo $id; ?>&turma_id=' + turmaId).addClass('ml-2').append($('<i>').addClass('fas fa-plus'));
            
            itemTurma.append(labelTurma, linkAdicionar);
            $('#resultadoBuscaTurma').append(itemTurma);
          });
        } else {
          var mensagem = $('<p>').text('Nenhuma turma encontrada.');
          $('#resultadoBuscaTurma').append(mensagem);
        }
      }
    });
  });
});
</script>';

</head>
<body>
    <div class="container mt-5">
        <form action="saveEdit.php" method="POST" enctype="multipart/form-data">

            <fieldset>
            <legend><b>Editar Usuario</b></legend>
            <div class="text-center">
                <img src="Profile/<?php echo $user_pic2; ?>.png" alt="Imagem do perfil do usuário" class="profile-modal-image rounded-circle">
            </div>
        </div>
        <p class="text-center">Usuario <?php echo $user_type2; ?></p>


                <div class="form-group">
                    <label for="name">Nome completo:</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name;?>" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" class="form-control" value="<?php echo $senha;?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Login:</label>
                    <input type="username" name="username" id="username" class="form-control" value="<?php echo $username;?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?php echo $email;?>" >
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone(com DDD):</label>
                    <input type="tel" name="telefone" id="telefone" class="form-control" value="<?php echo $telefone;?>" >
                </div>
                <div class="form-group">
                    <label>Sexo:</label>
                    <div class="form-check">
                        <input type="radio" id="feminino" name="genero" value="feminino" class="form-check-input" <?php echo ($sexo == 'feminino') ? 'checked' : '';?> required>
                        <label for="feminino" class="form-check-label">Feminino</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="masculino" name="genero" value="masculino" class="form-check-input" <?php echo ($sexo == 'masculino') ? 'checked' : '';?> required>
                        <label for="masculino" class="form-check-label">Masculino</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="outro" name="genero" value="outro" class="form-check-input" <?php echo ($sexo == 'outro') ? 'checked' : '';?> required>
                        <label for="outro" class="form-check-label">Outro</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="<?php echo $data_nasc;?>" >
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo $cidade;?>" >
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" id="estado" class="form-control" value="<?php echo $estado;?>" >
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Avatar do usuário</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="profile_pic" id="profile_pic" onchange="previewImage()">
                            <label class="custom-file-label" for="exampleInputFile">Arquivo de imagem</label>
                        </div>
                    </div>
                    <div id="imagePreview"></div>
                </div>

                <div class="form-group">
                    <label for="bio">Bio:</label>
                    <textarea class="form-control" id="bio" name="bio"><?php echo $bio2; ?></textarea>
                </div>



                <div class="form-group">
                    <label for="turma">Turmas:</label>
    <?php
    if ($user_type2 === 'aluno') {
        // Obtenha as turmas associadas ao usuário atual
        $sqlTurmasUsuario = "SELECT turmas.id, turmas.nome FROM turmas INNER JOIN alunos_turmas ON turmas.id = alunos_turmas.turma_id WHERE alunos_turmas.aluno_id = $id";
        $resultTurmasUsuario = $mysqli->query($sqlTurmasUsuario);

        if ($resultTurmasUsuario && $resultTurmasUsuario->num_rows > 0) {
            while ($turma = $resultTurmasUsuario->fetch_assoc()) {
                $turmaId = $turma['id'];
                $turmaNome = $turma['nome'];

                echo "<div class='form-check'>";
                echo "<label class='form-check-label'>$turmaNome</label>";
                echo "<a href='removerTurma.php?id=$turmaId&aluno_id=$id' class='ml-2'><i class='fas fa-times text-danger'></i></a>";
                echo "</div>";
            }
        } else {
            echo "<p>O aluno não está associado a nenhuma turma.</p>";
        }
        echo "<div class='form-check mt-2'>";
        echo "<button type='button' onclick=\"abrirTurmasAluno($id)\" class='btn-add-turma' data-toggle='modal' data-target='#adicionarTurmaModal'>";
        echo "<i class='fas fa-plus'></i> Adicionar turma";
        echo "</button>";
        echo "<br>";
        ;
    }
    
    ?>
    </div>

                <input type="hidden" name="id" value="<?php echo $id;?>">
                <button type="submit" name="update" class="btn btn-primary btn-lg rounded-pill shadow">Salvar</button>
            </fieldset>

            
        </form>
    </div>







    <!-- Adicione o link para o arquivo JavaScript do Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link para o arquivo JavaScript do Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link para a biblioteca Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
    $(document).ready(function () {
        // Ativa os componentes de seleção múltipla
        $('#turmas').select2();


  });


    
    </script>
<!-- Script para abrir a modal -->
<script>
$(document).ready(function() {
    $('#adicionarTurmaBtn').click(function() {
        $('#adicionarTurmaModal').modal('show');
    });
});

function abrirTurmasAluno(idAluno) {
    var url = "turmasAluno.php?id=" + idAluno;
    window.location.href = url;

}
function previewImage() {
        var fileInput = document.getElementById('profile_pic');
        var file = fileInput.files[0];
        var imagePreview = document.getElementById('imagePreview');

        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.setAttribute('src', e.target.result);
                img.setAttribute('class', 'preview-img');
                imagePreview.innerHTML = '';
                imagePreview.appendChild(img);
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = '';
        }
    }

document.getElementById("btnAlterarImagem").addEventListener("click", function() {
  document.getElementById("fileToUpload").click();
});

document.getElementById("fileToUpload").addEventListener("change", function() {
  var file = this.files[0];
  var form = new FormData();
  form.append("fileToUpload", file);
  form.append("user_id", "<?php echo $id; ?>");

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "uploadprofile.php", true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        alert(response.message);
        window.location.reload();
      } else {
        alert(response.message);
      }
    } else {
      alert("Ocorreu um erro ao enviar o formulário.");
    }
  };
  xhr.send(form);
});





</script>';
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <style>
    .perfil-image {
        max-height: 30px;
        max-width: none;
    }
        .profile-modal-image {
    max-width: 100%;
    max-height: 200px; /* Defina o tamanho máximo desejado */
    }
    .btn-add-turma {
        padding: 6px 12px;
  border: none;
  border-radius: 4px;
  background-color: #4CAF50;
  color: #FFFFFF;
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-add-turma:hover {
    background-color: #45a049;
}

.btn-add-turma:active {
  background-color: #3e8e41;
}
.preview-img {
        max-width: 100px;
        max-height: 100px;
    }
    </style>
    
</body>
</html>
