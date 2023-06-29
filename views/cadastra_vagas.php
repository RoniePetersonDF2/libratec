<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como empresa
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'EMP' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como empresa para cadastrar vagas!");
    exit;
}

$id_empresa = $_SESSION['usuario']['id'];
//echo'<pre>';var_dump($id_empresa);exit;

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';


    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    // Insere a vaga no banco de dados
    $query = "INSERT INTO `libratecdb`.`vagas` (id_empresa, descricao)
                 VALUES (:id, :descricao)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $id_empresa);
    $stmt->bindParam(':descricao', $descricao);


    if ($stmt->execute()) {
        header('location: perfil_emp.php?success=Vaga cadastrada com sucesso!');
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
                    <input type="text" value="<?php echo $_SESSION['usuario']['nome_empresa']; ?>" required readonly>

                    <label for="nome_surdo">Descrição da vaga</label>
                    <textarea name="descricao" id="descricao-vaga" placeholder="Informe a descrição da vaga." rows="6" cols="50" class="textarea-estendido" maxlength="255" required></textarea>
                    <div class="contador-wrapper">
                        <span id="contador-vaga"></span>
                    </div><br><br>

                    <input type="submit" value="Enviar" name="salvar">
                </form>
            </section>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

    <script src="assets/js/contador_vaga.js"></script>
</body>


</html>