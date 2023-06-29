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
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $senha = isset($_POST['senha']) ? md5($_POST['senha']) : '';
    $cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';

    
    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe 
    # usando como parametros os campos nome e password.
    $query = "INSERT INTO `libratecdb`.`empresa`( `nome_empresa`, `cnpj`, `email_empresa`, 
                        `senha_empresa`,  
                        `endereco_empresa`,`telefone_empresa`,`perfil`) 
                        VALUES (:nome, :cnpj,:email,
                            :senha, 
                            :endereco,:celular, :perfil)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':cnpj', $cnpj);
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
        header('location: usuario_admin.php?success=Cadastro realizado com sucesso!');
    } else {
        header('location: usuario_admin_add_empresa.php?error=Erro ao cadastrar nova conta!');
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# ordenando pelo campo perfil e nome.
$query = "SELECT * FROM `libratecdb`.`empresa` ORDER BY perfil, nome";
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
                    <h2>Cadastro de Empresas</h2>
                </div>
                <form action="" method="post" class="novo__form" enctype="multipart/form-data">
                    
                    <label for="email_surdo">E-mail da empresa</label>
                    <input type="email" name="email" placeholder="Informe o email da empresa." required autofocus>
                    
                    <label for="nome">Nome da empresa</label>
                    <input type="text" name="nome" placeholder="Informe o nome da empresa." required>
                    
                    <label for="cpf_surdo">CNPJ</label>
                    <input type="text" name="cnpj" id="cpf_surdo" maxlength="18" placeholder="Informe o cnpj da empresa." required>
                                        
                    <label for="endereco_surdo">Endereco da empresa</label>
                    <input type="text" name="endereco" placeholder="Informe o endereço da empresa." required>
                    
                    <label for="celular_surdo">Telefone da empresa</label>
                    <input type="tel" id="celular" name="celular" maxlength="15" placeholder="Informe o telefone da empresa." required>
                    
                    <label for="senha_surdo">Senha</label>
                    <input type="password" name="senha" maxlength="8" placeholder="Informe sua senha." required>

                    
                    <label for="perfil">Perfil</label>
                    <select name="perfil">
                        <option value="EMP" <?= isset($row) && $row['perfil'] == 'EMP' ? 'selected' : '' ?>>Empresa</option>
                        <option value="SUR" <?= isset($row) && $row['perfil'] == 'SUR' ? 'selected' : '' ?>>Surdo</option>
                        <option value="INT" <?= isset($row) && $row['perfil'] == 'INT' ? 'selected' : '' ?>>Intérprete</option>
                        <option value="ADM" <?= isset($row) && $row['perfil'] == 'ADM' ? 'selected' : '' ?>>Administrador</option>
                    </select><br><br>

                    </select>
                    <input type="submit" value="Salvar" name="salvar">
                </form>
            </section>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
        </script>

        <script src="assets/js/formata_telefone.js">
        </script>
        <script src="assets/js/formata_cnpj.js">
        </script>
</body>


</html>