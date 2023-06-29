<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once "../database/conexao.php";

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
    exit;
}

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $email_surdo = isset($_POST['email_surdo']) ? $_POST['email_surdo'] : '';
    $nome_surdo = isset($_POST['nome_surdo']) ? $_POST['nome_surdo'] : '';
    $senha_surdo = isset($_POST['senha_surdo']) ? md5($_POST['senha_surdo']) : '';
    $cpf_surdo = isset($_POST['cpf_surdo']) ? $_POST['cpf_surdo'] : '';
    $dt_nasc_surdo = isset($_POST['dt_nasc_surdo']) ? $_POST['dt_nasc_surdo'] : '';
    $celular_surdo = isset($_POST['celular_surdo']) ? $_POST['celular_surdo'] : '';
    $endereco_surdo = isset($_POST['endereco_surdo']) ? $_POST['endereco_surdo'] : '';
    $perfil = 'USU';

    #echo '<pre>';var_dump($_POST); exit;
    # definie o caminho onde sera gravado o arquivo.
    $uploaddir = __DIR__ . '/assets/img/laudos/';
    $laudo_med_surdo = basename($_FILES['laudo_med_surdo']['name']);
    $uploadfile = $uploaddir . $laudo_med_surdo;
    # verifica se o diretorio existe? Se não existir cria um novo.
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777);
    }
    # recebe o arquivo a ser gravado e inserido no diretorio criado. 
    # Se sim, gravano diretorio. Se não, limpa o nome da variavel que
    # sera usada no banco de dados.
    if (!move_uploaded_file($_FILES['laudo_med_surdo']['tmp_name'], $uploadfile)) {
        $imagemName  = '';
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe 
    # usando como parametros os campos nome e password.
    $query = "INSERT INTO `libratecdb`.`surdo`( `nome_surdo`, `cpf_surdo`, `email_surdo`, 
                    `senha_surdo`, `dt_nasc_surdo`, `laudo_med_surdo`,  
                    `endereco_surdo`,`celular_surdo`) 
                    VALUES (:nome_surdo, :cpf_surdo,:email_surdo,
                        :senha_surdo, :dt_nasc_surdo, :laudo_med_surdo, 
                        :endereco_surdo,:celular_surdo :perfil)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email_surdo', $email_surdo);
    $stmt->bindParam(':nome_surdo', $nome_surdo);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':cpf_surdo', $cpf_surdo);
    $stmt->bindParam(':dt_nasc_surdo', $dt_nasc_surdo);
    $stmt->bindParam(':laudo_med_surdo', $laudo_med_surdo);
    $stmt->bindParam(':endereco_surdo', $endereco_surdo);
    $stmt->bindParam(':celular_surdo', $celular_surdo);
    $stmt->bindParam(':senha_surdo', $senha_surdo);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();
    // echo '<pre>'; var_dump($dt_nasc_surdo, $stmt->rowCount(), $dbh->errorInfo()); exit;

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: usuario_admin.php?success=Cadastro realizado com sucesso!');
    } else {
        header('location: usuario_admin_new.php?error=Erro ao cadastrar nova conta!');
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}
?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>
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
                    <h2>Nova Conta</h2>
                </div>
                <form action="" method="post" class="novo__form" enctype="multipart/form-data">
                    <label for="email_surdo">E-mail</label><br>
                    <input type="email" name="email_surdo" placeholder="Informe seu e-mail." required autofocus><br><br>
                    <label for="nome_surdo">Nome</label><br>
                    <input type="text" name="nome_surdo" placeholder="Informe seu nome." required><br><br>
                    <label for="cpf_surdo">CPF</label><br>
                    <input type="text" name="cpf_surdo" id="cpf_surdo" maxlength="11" placeholder="Informe seu cpf." required><br><br>
                    <script>
                        const cpfInput = document.getElementById('cpf_surdo');
                        cpfInput.addEventListener('input', function(e) {
                            let valor = e.target.value;
                            // Remove todos os caracteres não numéricos
                            valor = valor.replace(/\D/g, '');
                            // Insere a formatação do CPF
                            valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                            // Define o valor formatado no campo de entrada
                            e.target.value = valor;
                        });
                    </script>
                    <label for="dt_nasc_surdo">Data de nascimento</label><br>
                    <input type="date" name="dt_nasc_surdo" placeholder="Informe sua data de nascimento." required><br><br>
                    <label for="endereco_surdo">Endereco</label><br>
                    <input type="text" name="endereco_surdo" placeholder="Informe seu endereço." required><br><br>
                    <label for="celular_surdo">Telefone</label><br>
                    <input type="celular_surdo" id="celular_surdo" name="celular_surdo" maxlength="15" placeholder="Informe seu telefone." required><br><br>
                    <script>
                        const telefoneInput = document.getElementById('celular_surdo');
                        telefoneInput.addEventListener('input', function(e) {
                            let valor = e.target.value;
                            // Remove todos os caracteres não numéricos
                            valor = valor.replace(/\D/g, '');
                            // Formata o número como (XXX) XXX-XXXX
                            valor = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                            // Define o valor formatado no campo de entrada
                            e.target.value = valor;
                        });
                    </script>
                    <label for="laudo_med_surdo">Laudo médico</label><br>
                    <input type="file" name="laudo_med_surdo" placeholder="Informe o laudo médico." required><br><br>
                    <label for="senha_surdo">Password</label><br>
                    <input type="password" name="senha_surdo" maxlength="5" placeholder="Informe seu password." required><br><br>
                    <input type="submit" value="Enviar" name="salvar">
                </form>
            </section>
        </div>

    </main>

</body>


</html>