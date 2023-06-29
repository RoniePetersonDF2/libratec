<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como surdo
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'SUR' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como surdo para acessar essa página!");
    exit;
}

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

// Buscar os dados do intérprete logado
$id_surdo = $_SESSION['usuario']['id']; // Supondo que o ID do intérprete esteja armazenado na variável 'id'
//echo'<pre>';var_dump($_SESSION);exit;

# verifica se os dados do formulário foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id enviado por post para delete ou update.
    $id = (isset($_POST['id']) ? $_POST['id'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuário com id informado 
        $query = "DELETE FROM `libratecdb`.`surdo` WHERE id_surdo = :id";
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
            header('location: perfil_surdo.php?error=Erro ao excluir a sua conta!');
        }
    }
}

$query = "SELECT * FROM `libratecdb`.`surdo` WHERE id_surdo = :id";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':id', $id_surdo);
$stmt->execute();

$surdo = $stmt->fetch(PDO::FETCH_ASSOC);
// echo'<pre>';var_dump($surdo);exit;
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
                    <a href="editar_surdo.php">Editar</a>

                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $surdo['id_surdo'] ?>" />
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

                        <div class="col-md-3">
                            <h5 class="dado_titulo">Nome</h5>

                            <div class="col-md-9 text-secondary">
                                <p class="dado_campo"><?php echo $surdo['nome_surdo']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="dado_titulo">E-mail</h5>

                                <div class="col-md-9 text-secondary">
                                    <p class="dado_campo"><?php echo $surdo['email_surdo']; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <h5 class="dado_titulo">Telefone</h5>

                                    <div class="col-md-9 text-secondary">
                                        <p class="dado_campo"><?php echo $surdo['celular_surdo']; ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5 class="dado_titulo">CPF</h5>

                                        <div class="col-md-9 text-secondary">
                                            <p class="dado_campo"><?php echo $surdo['cpf_surdo']; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5 class="dado_titulo">Endereço</h5>

                                            <div class="col-md-9 text-secondary">
                                                <p class="dado_campo"><?php echo $surdo['endereco_surdo']; ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5 class="dado_titulo">Data de Nascimento</h5>

                                                <div class="col-md-9 text-secondary">
                                                    <p class="dado_campo"><?php echo date('d/m/Y', strtotime($surdo['dt_nasc_surdo'])); ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h5 class="dado_titulo">Laudo Médico</h5>

                                                    <div class="col-md-9 text-secondary">
                                                        <p class="dado_campo"><?php echo $surdo['laudo_med_surdo']; ?></p>
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