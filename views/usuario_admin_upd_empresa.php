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

# verifica se uma variavel id foi passada via GET 
$id_empresa = isset($_GET['id']) ? $_GET['id'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';

    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `libratecdb`.`empresa` SET `email_empresa` = :email,
                    `nome_empresa` = :nome, `perfil` = :perfil, 
                    cnpj = :cnpj, 
                    endereco_empresa = :endereco,
                    telefone_empresa = :celular
                    WHERE id_empresa = :id_empresa";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':id_empresa', $id_empresa);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: usuario_admin_list_empresa.php?success=Usuário atualizado com sucesso!');
    } else {
        $error = $dbh->errorInfo();
        var_dump($error);
        header('location: usuario_admin_upd_empresa.php?error=Erro ao atualizar o usuário!');
    }
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `libratecdb`.`empresa` WHERE id_empresa=:id_empresa LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':id_empresa', $id_empresa);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo '<pre>';var_dump($row);exit;

# destroi a conexao com o banco de dados.
$dbh = null;
# se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: usuario_admin_list_empresa.php?error=Usuário inválido.');
}

# destroi a conexao com o banco de dados.
$dbh = null;
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
                    <h2>Atualizar dados da empresa</h2>
                </div>
                <form action="" method="post" class="novo__form">

                    <label for="email_surdo">E-mail da empresa</label>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus value="<?= isset($row) ? $row['email_empresa'] : '' ?>">

                    <label for="nome_surdo">Nome da empresa</label>
                    <input type="text" name="nome" placeholder="Informe seu nome." value="<?= isset($row) ? $row['nome_empresa'] : '' ?>" required>

                    <label for="cpf_surdo">CNPJ</label>
                    <input type="text" name="cnpj" maxlength="18" placeholder="Informe seu cpf." value="<?= isset($row) ? $row['cnpj'] : '' ?>" required readonly>

                    <label for="endereco_surdo">Endereço da empresa</label>
                    <input type="text" name="endereco" placeholder="Informe seu endereço." value="<?= isset($row) ? $row['endereco_empresa'] : '' ?>" required>

                    <label for="celular_surdo">Telefone da empresa</label>
                    <input type="text" name="celular" maxlength="15" placeholder="Informe seu Telefone." value="<?= isset($row) ? $row['telefone_empresa'] : '' ?>" required>

                    <label for="perfil">Perfil</label>
                    <select name="perfil">
                        <option value="INT" <?= isset($row) && $row['perfil'] == 'INT' ? 'selected' : '' ?>>Intérprete</option>
                        <option value="SUR" <?= isset($row) && $row['perfil'] == 'SUR' ? 'selected' : '' ?>>Surdo</option>
                        <option value="EMP" <?= isset($row) && $row['perfil'] == 'EMP' ? 'selected' : '' ?>>Empresa</option>
                        <option value="ADM" <?= isset($row) && $row['perfil'] == 'ADM' ? 'selected' : '' ?>>Administrador</option>
                    </select><br><br>


                    <input type="submit" value="Salvar" name="salvar">
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