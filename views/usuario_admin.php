<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header
require_once 'layouts/site/header.php';

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
    exit;
}
?>

<body style="background-image: linear-gradient(to right,#044cab,#00002d,#044cab);">
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
        <div class="main_opc">
            <br>
            <section class="main_course" id="escola">
                <header class="main_course_header">

                </header>
                <div class="main_course_content">
                    <article>
                        <h2 align="center">Cadastrar dados</h2>
                        <header>

                            <p align="center">
                                <a href="tela_admin_cadastra.php"><img src="assets/img/cadastrardados.png" width="200" title="Cadastrar"></a>
                            </p>

                        </header>
                    </article>
                    <article>
                        <h2 align="center">Alterar dados</h2>
                        <header>

                            <p align="center">
                                <a href="tela_admin_lista.php"><img src="assets/img/alterardados.png.png" width="200" title="Alterar"></a></p>

                        </header>
                    </article>

                </div>
                </article>
            </section>
        </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>