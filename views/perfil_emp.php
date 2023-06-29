<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como empresa
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'EMP' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como empresa para acessar essa página!");
    exit;
}

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

// Buscar os dados do intérprete logado
$id_empresa = $_SESSION['usuario']['id']; // Supondo que o ID do intérprete esteja armazenado na variável 'id'
//echo'<pre>';var_dump($_SESSION);exit;

# verifica se os dados do formulário foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id enviado por post para delete ou update.
    $id = (isset($_POST['id']) ? $_POST['id'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuário com id informado 
        $query = "DELETE FROM `libratecdb`.`empresa` WHERE id_empresa = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id', $id);

        # executa a consulta no banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantidade de registros excluídos é maior que zero.
        # se sim, redireciona para a página de admin com mensagem de sucesso.
        # se não, redireciona para a página de perfil com mensagem de erro.
        if ($stmt->rowCount()) {
            # destroi a sessão do usuário
            session_destroy();
            header('location: index.php?success=Sua conta foi excluída com sucesso!');
        } else {
            header('location: perfil_emp.php?error=Erro ao excluir a sua conta!');
        }
    }
}

$query = "SELECT * FROM `libratecdb`.`empresa` WHERE id_empresa = :id";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':id', $id_empresa);
$stmt->execute();

$empresa = $stmt->fetch(PDO::FETCH_ASSOC);
// echo'<pre>';var_dump($empresa);exit;
?>

<body>
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
    <style>
        body {
            background-image: linear-gradient(to right, #1006ab, #00002d, #1006ab);
        }
    </style>

    <div class="container">
        <div class="main">
            <div class="topbar">
                <div class="edita_dados_perfil">
                    <a href="editar_empresa.php">Editar</a>

                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $empresa['id_empresa'] ?>" />
                        <button class="edita_dados_perfil_delete" name="botao" value="deletar" onclick="return confirm('Deseja realmente excluir esta conta?');">Apagar</button>
                    </form>
                </div>

                <button class="navega_perfil perfil_volta_botao" onclick="history.back()">Voltar</button>

            </div>
            <div class="row">
                <div class="card text-center sidebar">
                    <div class="card-body">
                        <img src="" class="rounded-circle" width="150">
                        <div class="mt-3">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mt1">
                <div class="card mb-3 content">
                    <h1 class="perfil_titulo">Meu Perfil</h1>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">Nome da Empresa</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $empresa['nome_empresa']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">E-mail</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $empresa['email_empresa']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">CNPJ</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $empresa['cnpj']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">Endereço</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $empresa['endereco_empresa']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">Telefone</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $empresa['telefone_empresa']; ?></p>
                            </div>
                        </div>
                        <br><br><br><br><br>

                        <div class="btn_alinhamento">
                            <p><a href="cadastra_vagas.php" class="btn">Cadastrar uma vaga</a></p>
                            <p><a href="vagas_painel.php" class="btn2">Editar vagas</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

</body>

</html>