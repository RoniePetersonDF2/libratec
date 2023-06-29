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

// Verificando se o usuário está logado como surdo
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'EMP' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como empresa para acessar essa página!");
    exit;
}
// Buscar os dados da empresa logada
$id_empresa = $_SESSION['usuario']['id']; // Supondo que o ID da empresa esteja armazenado na variável 'id'
//echo'<pre>';var_dump($_SESSION);exit;
$id_vaga = base64_decode(urldecode($_GET['id']));

# cria a variável $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulário foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variável (descricao) para armazenar a descrição passada via método POST.
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

    # cria uma consulta no banco de dados para atualizar a descrição da vaga.
    # utiliza o ID da empresa logada como filtro.
    $query = "UPDATE `libratecdb`.`vagas` SET `descricao` = :descricao
          WHERE id_empresa = :id_empresa AND id_vagas = :id_vaga";
    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id_empresa', $id_empresa);
    $stmt->bindParam(':id_vaga', $id_vaga);

    # executa a consulta no banco de dados para atualizar a descrição da vaga.
    $stmt->execute();


    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: vagas_painel.php?success=Vaga de intérprete atualizada com sucesso!');
    } else {
        $encrypted_id_vaga = urlencode(base64_encode($id_vaga));
        header("location: editar_vaga.php?error=Erro ao atualizar a vaga&id={$encrypted_id_vaga}");
    }
    
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
# cria uma consulta no banco de dados para obter a descrição da vaga com base no ID da vaga.
$query = "SELECT descricao FROM `libratecdb`.`vagas` WHERE id_vagas = :id_vaga AND id_empresa = :id_empresa";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':id_vaga', $id_vaga);
$stmt->bindParam(':id_empresa', $id_empresa);

# executa a consulta no banco de dados e aguarda o resultado.
$stmt->execute();

# Obtém a descrição da vaga do resultado da consulta.
# Se existir um registro, obtém a descrição, caso contrário, define a descrição como vazia.
$descricao = '';
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $descricao = $row['descricao'];
}
//echo '<pre>';var_dump($row);exit;

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
                    <h2>Atualizar dados da vaga de intérprete</h2>
                </div>
                <form action="" method="post" class="novo__form" onsubmit="return confirmUpdate();">

                    <label for="nome_surdo">Nome da empresa</label>
                    <input type="text" value="<?php echo $_SESSION['usuario']['nome_empresa']; ?>" required readonly>

                    <label for="descricao">Descrição da vaga</label>
                    <textarea name="descricao" id="descricao-vaga" placeholder="Informe a descrição da vaga." rows="6" cols="50" class="textarea-estendido" maxlength="255" required><?php echo $descricao; ?></textarea>
                    <div class="contador-wrapper">
                        <span id="contador-vaga"></span>
                    </div><br><br>


                    <input type="submit" value="Enviar" name="salvar">
                </form>
            </section>

            <script>
                function confirmUpdate() {
                    return confirm("Deseja realmente alterar a vaga?");
                }
            </script>

        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

    <script src="assets/js/contador_vaga.js"></script>
</body>


</html>