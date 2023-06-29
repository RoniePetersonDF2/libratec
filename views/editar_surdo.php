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
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'SUR' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como surdo para acessar essa página!");
    exit;
}

// Buscar os dados do intérprete logado
$id_surdo = $_SESSION['usuario']['id']; // Supondo que o ID do intérprete esteja armazenado na variável 'id'
//echo'<pre>';var_dump($_SESSION);exit;


# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();


# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $dt_nasc = isset($_POST['dt_nasc']) ? $_POST['dt_nasc'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';

    // Faz a consulta para buscar o valor atual armazenado em $rowImagem
    $querySelect = "SELECT laudo_med_surdo FROM `libratecdb`.`surdo` WHERE id_surdo = :id_surdo";
    $stmtSelect = $dbh->prepare($querySelect);
    $stmtSelect->bindParam(':id_surdo', $id_surdo);
    $stmtSelect->execute();
    $rowImagem = $stmtSelect->fetchColumn();
    // echo '<pre>';var_dump($rowImagem);exit;


    $uploaddir = __DIR__ . '/assets/img/laudos/';

    // Verifica se o campo 'laudo_med' está definido e se não há nenhum erro no upload
    if (isset($_FILES['laudo_med']) && $_FILES['laudo_med']['error'] !== UPLOAD_ERR_NO_FILE) {
        $laudo_med = basename($_FILES['laudo_med']['name']);
        $uploadfile = $uploaddir . $laudo_med;

        // Verifica se o diretório existe. Se não existir, cria um novo.
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0777, true);
        }

        // Move o arquivo para o diretório
        if (!move_uploaded_file($_FILES['laudo_med']['tmp_name'], $uploadfile)) {
            $laudo_med = ''; // Limpa o nome da variável para ser usado no banco de dados
        }
    } elseif (!empty($rowImagem)) {
        // O usuário não selecionou uma imagem, mas existe uma imagem armazenada em $rowImagem
        $laudo_med = $rowImagem;
    } else {
        $laudo_med = ''; // O usuário não selecionou uma imagem e não há uma imagem armazenada anteriormente
    }


    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `libratecdb`.`surdo` SET `nome_surdo` = :nome, 
                    `dt_nasc_surdo` = :dt_nasc,
                    endereco_surdo = :endereco,
                    celular_surdo = :celular,
                    laudo_med_surdo = :laudo_med
                    WHERE id_surdo = :id_surdo";

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':dt_nasc', $dt_nasc);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':laudo_med', $laudo_med);
    $stmt->bindParam(':id_surdo', $id_surdo);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # Atualiza o nome do usuário na sessão
    $_SESSION['usuario']['nome_surdo'] = $nome;

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: perfil_surdo.php?success=Conta atualizada com sucesso!');
    } else {
        $error = $dbh->errorInfo();
        var_dump($error);
        header('location: editar_surdo.php?error=Erro ao atualizar a sua conta!');
    }
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `libratecdb`.`surdo` WHERE id_surdo=:id LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':id', $id_surdo);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// echo '<pre>';var_dump($row);exit;

# destroi a conexao com o banco de dados.
$dbh = null;
# se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: perfil_surdo.php?error=Usuário inválido.');
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
                <form action="" method="post" class="novo__form" enctype="multipart/form-data" onsubmit="return confirmUpdate();">

                    <label for="nome_interprete">Nome</label>
                    <input type="text" name="nome" placeholder="Informe seu nome." value="<?= isset($row) ? $row['nome_surdo'] : '' ?>" required>

                    <label for="email_interprete">E-mail</label>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus value="<?= isset($row) ? $row['email_surdo'] : '' ?>" readonly>

                    <label for="cpf_surdo">CPF</label>
                    <input type="text" name="cpf" id="cpf_surdo" maxlength="14" placeholder="Informe seu cpf." required value="<?= isset($row) ? $row['cpf_surdo'] : '' ?>" readonly>

                    <label for="dt_nasc_surdo">Data de nascimento</label>
                    <input type="date" name="dt_nasc" placeholder="Informe sua data de nascimento." required value="<?= isset($row) ? $row['dt_nasc_surdo'] : '' ?>">

                    <label for="endereco_interprete">Endereço</label>
                    <input type="text" name="endereco" placeholder="Informe seu endereço." value="<?= isset($row) ? $row['endereco_surdo'] : '' ?>" required>

                    <label for="celular_interprete">Telefone</label>
                    <input type="text" name="celular" maxlength="15" placeholder="Informe seu Telefone." value="<?= isset($row) ? $row['celular_surdo'] : '' ?>" required>

                    <label for="laudo_med_surdo">Laudo médico</label>
                    <input type="file" name="laudo_med" placeholder="Informe o laudo médico." value="<?= isset($row) ? $row['laudo_med_surdo'] : '' ?>"><br><br>


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

    <script src="assets/js/formata_telefone.js">
    </script>
    <script src="assets/js/formata_cpf.js">
    </script>
</body>


</html>