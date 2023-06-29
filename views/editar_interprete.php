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
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'INT' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como intérprete para acessar essa página!");
    exit;
}

// Buscar os dados do intérprete logado
$id_interprete = $_SESSION['usuario']['id']; // Supondo que o ID do intérprete esteja armazenado na variável 'id'
//echo'<pre>';var_dump($_SESSION);exit;


# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `libratecdb`.`interprete` SET `nome_interprete` = :nome,  
                    endereco_interprete = :endereco,
                    celular_interprete = :celular,
                    certificado = :descricao
                    WHERE id_interprete = :id_interprete";
    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id_interprete', $id_interprete);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # Atualiza o nome do usuário na sessão
    $_SESSION['usuario']['nome_interprete'] = $nome;

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: perfil_inter.php?success=Conta atualizada com sucesso!');
    } else {
        $error = $dbh->errorInfo();
        var_dump($error);
        header('location: editar_interprete.php?error=Erro ao atualizar a sua conta!');
    }
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `libratecdb`.`interprete` WHERE id_interprete=:id LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':id', $id_interprete);

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
    header('location: perfil_inter.php?error=Usuário inválido.');
}

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
                    <h2>Atualizar dados da sua conta</h2>
                </div>
                <form action="" method="post" class="novo__form" onsubmit="return confirmUpdate();">

                    <label for="nome_interprete">Nome</label>
                    <input type="text" name="nome" placeholder="Informe seu nome." value="<?= isset($row) ? $row['nome_interprete'] : '' ?>" required>

                    <label for="email_interprete">E-mail</label>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus value="<?= isset($row) ? $row['email_interprete'] : '' ?>" readonly>

                    <label for="endereco_interprete">Endereço</label>
                    <input type="text" name="endereco" placeholder="Informe seu endereço." value="<?= isset($row) ? $row['endereco_interprete'] : '' ?>" required>

                    <label for="celular_interprete">Telefone</label>
                    <input type="text" name="celular" maxlength="15" placeholder="Informe seu Telefone." value="<?= isset($row) ? $row['celular_interprete'] : '' ?>" required>

                    <label for="certificado">Fale um pouco sobre sua experiência profissional</label>
                    <textarea name="descricao" id="descricao-experiencia" placeholder="Descrição." rows="6" cols="50" class="textarea-estendido" maxlength="255" required><?php echo $row['certificado']; ?></textarea>
                    <div class="contador-wrapper">
                        <span id="contador-experiencia"></span>
                    </div><br><br>


                    <input type="submit" value="Salvar" name="salvar">
                </form>
            </section>

            <script>
                function confirmUpdate() {
                    return confirm("Deseja realmente alterar os dados da sua conta?");
                }
            </script>

        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

    <script src="assets/js/formata_telefone.js"></script>
    <script src="assets/js/formata_cpf.js"></script>
    <script src="assets/js/contador_experiencia.js"></script>
</body>


</html>