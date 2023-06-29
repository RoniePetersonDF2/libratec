<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

# Verificando se o usuário está logado como empresa
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'EMP' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como empresa para acessar essa página!");
    exit;
}
// echo'<pre>';var_dump($_SESSION);exit;


# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

// Buscar os dados da empresa logada
$id_empresa = $_SESSION['usuario']['id'];
// echo'<pre>';var_dump($id_empresa);exit;


# verifica se os dados do formulário foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id enviado por post para delete ou update.
    $id_vagas = (isset($_POST['id']) ? $_POST['id'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuário com id informado 
        $query = "DELETE FROM `libratecdb`.`vagas` WHERE id_vagas = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id', $id_vagas);

        # executa a consulta no banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantidade de registros excluídos é maior que zero.
        # se sim, redireciona para a página de admin com mensagem de sucesso.
        # se não, redireciona para a página de perfil com mensagem de erro.
        if ($stmt->rowCount()) {
            header('location: vagas_painel.php?success=Vaga excluída com sucesso!');
            exit;
        } else {
            header('location: vagas_painel.php?error=Erro ao excluir esta vaga!');
            exit;
        }
    }

}

$query = "SELECT v.*, empresa.nome_empresa 
          FROM `libratecdb`.`vagas` AS v
          INNER JOIN `libratecdb`.`empresa` ON v.id_empresa = empresa.id_empresa
          WHERE v.id_empresa = :id_empresa";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':id_empresa', $id_empresa);
$stmt->execute();

$vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo'<pre>';var_dump($vagas);exit;



    # destroi a conexao com o banco de dados.
    $dbh = null;
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
        <h2 class="icon-blog">Vagas abertas por sua empresa</h2>
    </div>
    <?php foreach ($vagas as $vaga) { ?>
        <div class="vagas_edita">
        <div class="botoes-edicao">
            <button type="button" class="botao-editar" onclick="window.location.href='editar_vaga.php?id=<?= urlencode(base64_encode($vaga['id_vagas'])) ?>'">Editar</button>
            
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $vaga['id_vagas'] ?>">
                <!-- <?php /*echo'<pre>';var_dump($vaga['id_vagas']);exit;*/ ?> -->
                <button class="botao-apagar" name="botao" value="deletar" onclick="return confirm('Deseja realmente excluir esta vaga?');">Apagar</button>
            </form>

        </div>

            <label for="nome_surdo">Nome da empresa</label><br>
            <p class="nome_empresa_painel"><?php echo $_SESSION['usuario']['nome_empresa']; ?><br><br></p>

            <label for="descricao">Descrição da vaga</label><br>
            <textarea name="descricao" id="descricao-vaga" placeholder="Informe a descrição da vaga." rows="6" cols="50" class="textarea-estendido" maxlength="255" required readonly><?php echo $vaga['descricao']; ?></textarea>
            <br><br>
        </div>
    <?php } ?>
</section>

        </div>
    </main>
</body>


</html>