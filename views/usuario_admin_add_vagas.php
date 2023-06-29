<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
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
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $nome_empresa = isset($_POST['nome_empresa']) ? $_POST['nome_empresa'] : '';


    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe 
    # usando como parametros os campos nome e password.
    $query = "INSERT INTO `libratecdb`.`vagas`( `nome_empresa`, `descricao`) 
                        VALUES (:nome_empresa, :descricao)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome_empresa', $nome_empresa);
    $stmt->bindParam(':descricao', $descricao);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();
    // echo '<pre>'; var_dump($dt_nasc_surdo, $stmt->rowCount(), $dbh->errorInfo()); exit;

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: index.php?success=Vaga cadastrada com sucesso!');
    } else {
        header('location: cadastra_vaga.php?error=Erro ao cadastrar a vaga!');
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
                    <h2>Cadastrar vagas para intérpretes</h2>
                </div>
                <form action="" method="post" class="novo__form" enctype="multipart/form-data">
                    
                    <label for="nome_surdo">Nome da empresa</label>
                    <input type="text" name="nome_empresa" placeholder="Informe seu nome." required>
                    
                    <label for="nome_surdo">Descrição da vaga</label>
                    <textarea name="descricao" placeholder="Informe a descrição da vaga." required></textarea>
                    <br><br>


                    
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