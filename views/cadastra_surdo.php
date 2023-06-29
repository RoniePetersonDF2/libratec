<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";


# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $senha = isset($_POST['senha']) ? md5($_POST['senha']) : '';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $dt_nasc = isset($_POST['dt_nasc']) ? $_POST['dt_nasc'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';

    #echo '<pre>';var_dump($_POST); exit;


    # definie o caminho onde sera gravado o arquivo.
    $uploaddir = __DIR__ . '/assets/img/laudos/';
    $laudo_med = basename($_FILES['laudo_med']['name']);
    $uploadfile = $uploaddir . $laudo_med;
    # verifica se o diretorio existe? Se não existir cria um novo.
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777);
    }
    # recebe o arquivo a ser gravado e inserido no diretorio criado. 
    # Se sim, gravano diretorio. Se não, limpa o nome da variavel que
    # sera usada no banco de dados.
    if (!move_uploaded_file($_FILES['laudo_med']['tmp_name'], $uploadfile)) {
        $imagemName  = '';
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe 
    # usando como parametros os campos nome e password.
    $query = "INSERT INTO `libratecdb`.`surdo`( `nome_surdo`, `cpf_surdo`, `email_surdo`, 
                        `senha_surdo`, `dt_nasc_surdo`, `laudo_med_surdo`,  
                        `endereco_surdo`,`celular_surdo`) 
                        VALUES (:nome, :cpf,:email,
                            :senha, :dt_nasc, :laudo_med, 
                            :endereco, :celular)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':dt_nasc', $dt_nasc);
    $stmt->bindParam(':laudo_med', $laudo_med);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':senha', $senha);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();
    // echo '<pre>'; var_dump($dt_nasc_surdo, $stmt->rowCount(), $dbh->errorInfo()); exit;

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: index.php?success=Surdo cadastrado com sucesso!');
    } else {
        header('location: cadastra_surdo.php?error=Erro ao se cadastrar como surdo!');
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}
?>

<body>
    <main>
        <div class="main_opc">
            <?php
            # Verifica se existe uma mensagem de erro enviada via GET
            if (isset($_GET['error'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: '<?= $_GET['error'] ?>',
                    });
                </script>
            <?php
            }
            # Verifica se existe uma mensagem de sucesso enviada via GET
            elseif (isset($_GET['success'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: '<?= $_GET['success'] ?>',
                    });
                </script>
            <?php
            }
            ?>
            <section>
                <div class="novo__form__titulo">
                    <h2>Cadastrar-se como surdo</h2>
                </div>
                <form action="" method="post" class="novo__form" enctype="multipart/form-data">

                    <label for="email_surdo">E-mail</label>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus>

                    <label for="nome_surdo">Nome</label>
                    <input type="text" name="nome" placeholder="Informe seu nome." required>

                    <label for="cpf_surdo">CPF</label>
                    <input type="text" name="cpf" id="cpf_surdo" maxlength="14" placeholder="Informe seu cpf." required>

                    <label for="dt_nasc_surdo">Data de nascimento</label>
                    <input type="date" name="dt_nasc" placeholder="Informe sua data de nascimento." required>

                    <label for="endereco_surdo">Endereco</label>
                    <input type="text" name="endereco" placeholder="Informe seu endereço." required>

                    <label for="celular_surdo">Telefone</label>
                    <input type="celular_surdo" id="celular_surdo" name="celular" maxlength="15" placeholder="Informe seu telefone." required>

                    <label for="laudo_med_surdo">Laudo médico</label>
                    <input type="file" name="laudo_med" placeholder="Informe o laudo médico." required>

                    <label for="senha_surdo">Senha</label>
                    <input type="password" name="senha" maxlength="5" placeholder="Informe seu password." required><br><br>

                    
                    <input type="submit" value="Enviar" name="salvar">
                </form>
            </section>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

    <script src="assets/js/formata_telefone.js">
    </script>
    <script src="assets/js/formata_cpf.js">
    </script>
</body>


</html>