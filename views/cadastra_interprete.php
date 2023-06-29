<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $senha = isset($_POST['senha']) ? md5($_POST['senha']) : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $celular = isset($_POST['celular']) ? $_POST['celular'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';


    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe 
    # usando como parametros os campos nome e password.
    $query = "INSERT INTO `libratecdb`.`interprete`( `nome_interprete`, `email_interprete`, `senha_interprete`,`endereco_interprete`,`celular_interprete`,`certificado`) 
           VALUES (:nome,
           :email,
           :senha,
           :endereco,
           :celular,
           :descricao)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':descricao', $descricao);


    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # verifica se a quantiade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: index.php?success=Intérprete cadastrado com sucesso!');
    } else {
        header('location: cadastra_interprete.php?error=Erro ao se cadastrar como intérprete!');
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
                    <h2>Cadastrar-se como intérprete</h2>
                </div>
                <form action="" method="post" class="novo__form">

                    <label for="nome">Nome</label>
                    <input type="text" name="nome" placeholder="Informe seu nome." required>

                    <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus>

                    <label for="endereco_interprete">Endereco</label>
                    <input type="text" name="endereco" placeholder="Informe seu endereço." required>

                    <label for="certificado">Fale um pouco sobre sua experiência profissional</label>
                    <textarea name="descricao" id="descricao-experiencia" placeholder="Descrição." rows="6" cols="50" class="textarea-estendido" maxlength="255" required></textarea>
                    <div class="contador-wrapper">
                        <span id="contador-experiencia"></span>
                    </div>


                    <label for="celular_interprete">Telefone</label>
                    <input type="text" id="celular_interprete" name="celular" maxlength="15" placeholder="Informe seu telefone." required>

                    <label for="password">Senha</label>
                    <input type="password" name="senha" placeholder="Digite sua senha." required><br><br>


                    <input type="submit" value="Enviar" name="salvar">

                </form>
            </section>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

    <script src="assets/js/formata_telefone.js"></script>
    <script src="assets/js/contador_experiencia.js"></script>
</body>


</html>