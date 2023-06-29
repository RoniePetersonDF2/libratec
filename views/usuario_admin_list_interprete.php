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


# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id do enviado por post para delete ou update.
    $id = (isset($_POST['id']) ? $_POST['id'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post se é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuario com id informado 
        $query = "DELETE FROM `libratecdb`.`interprete` WHERE id_interprete = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id', $id);

        # executa a consulta banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantiade de registros excluido é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de admin com mensagem de erro.
        if ($stmt->rowCount()) {
            header('location: usuario_admin_list_interprete.php?success=Usuário excluído com sucesso!');
        } else {
            header('location: usuario_admin_list_interprete.php?error=Erro ao excluir usuário!');
        }
    }
}
# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# ordenando pelo campo perfil e nome.
$query = "SELECT * FROM `libratecdb`.`interprete` order by perfil, nome_interprete ";
$stmt = $dbh->prepare($query);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


# destroi a conexao com o banco de dados.
$dbh = null;

?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>

    <main>
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

        </div>
        <div class="main_opc">


            <div class="main_stage">
                <div class="main_stage_content">
                    <div>
                        <button class="btn" style="min-height: 40px; margin-bottom: 10px;" onclick="javascript:window.location='usuario_admin_add_interprete.php'">Novo usuário</button>
                    </div>
                    <article>
                        <header>

                            <table border="0" width="1300px" class="table">

                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Ação</th>
                                </tr>

                                <?php
                                # verifica se os dados existem na variavel $row.
                                # se existir faz um loop nos dados usando foreach.
                                # cria uma variavel $count para contar os registros da tabela.
                                # se não existir vai para o else e imprime uma mensagem.
                                if ($rows) {
                                    $count = 1;
                                    foreach ($rows as $row) { ?>
                                        <tr>
                                            <td><?= $count ?></td>
                                            <td><?= $row['nome_interprete'] ?></td>
                                            <td><?= $row['email_interprete'] ?></td>
                                            <td><?= $row['perfil'] ?></td>
                                            <td>
                                                <div class="btn_alinhamento">
                                                    <a href="usuario_admin_upd_interprete.php?id=<?= $row['id_interprete'] ?>" class="btn">Editar</a>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id" value="<?= $row['id_interprete'] ?>" />
                                                        <button class="btn" name="botao" value="deletar" onclick="return confirm('Deseja excluir o usuário?');">Apagar</button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php $count++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="6"><strong>Não existem usuários cadastrados.</strong></td>
                                    </tr>
                                <?php } ?>
                            </table>

                        </header>
                    </article>

                </div>
            </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>