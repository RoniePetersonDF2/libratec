<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
?>

<!--DOBRA PALCO PRINCIPAL-->

<!--1ª DOBRA-->

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
    <div class="main_cta">
        <br><br><br><br><br><br>
        <article class="main_cta_content">

            <div class="main_cta_content_spacer">
                <header>
                    <h1>A lingua de sinais é para os olhos o que as palavras é para os ouvidos!</h1>
                </header>
            </div>
        </article>
    </div>
    <!--FIM 1ª DOBRA-->

    <!--INICIO SESSÃO SESSÃO DE ARTIGOS-->
    <section class="main_blog" id="seuespaco">
        <header class="main_blog_header">
            <h1 class="icon-blog">Seu espaço</h1>
        </header>
        <article>
            <div class="imagens_acesso">
                <img src="assets/img/surdoremovido.png" alt="Imagem post" title="Surdo" class="imagem1">
                <p class="category">Área do Surdo</p><br>
                <p>Procure aqui por intérpretes para lhe atender.</p>
                <br>
                <p><a href="telasurdo.php" class="btn">Acesse</a></p>
            </div>
        </article>

        <article>
            <div class="imagens_acesso">
                <img src="assets/img/interprete - Copia.jpg" alt="Imagem post" title="Interprete" class="imagem1">
                <p class="category">Área do Intéprete</p><br>
                <p>Descubra oportunidades de trabalho como intérprete em diversas empresas. Encontre a vaga que mais lhe interessa aqui.</p>
                <br>
                <p><a href="telainterprete.php" class="btn">Acesse</a></p>
            </div>
        </article>
    </section>

    <!--FIM SESSÃO SESSÃO DE ARTIGOS-->


    <!--INICIO DOBRA TUTOR-->
    <section class="main_tutor" id="sobrenos">
        <div class="main_tutor_content">
            <header>
                <h1>Depoimentos de alguns surdos</h1>
            </header>
            <div class="main_tutor_content_img">
                <a href="https://youtu.be/PBAuulMFqrU" target="_blank"><img src="assets/img/prof.png" width="200" title="Professora" alt="Instrutor" class="imagem11"></a>
                <a href="https://youtu.be/_h_Kfa5O8dY" target="_blank"><img src="assets/img/mulhericon.png" width="200" title="Aluna" alt="Instrutor" class="imagem22"></a>
                <a href="https://youtu.be/8tWaTg5wXjk" target="_blank"><img src="assets/img/menino.png" width="200" title="Aluno" alt="Instrutor"></a>
            </div>
            <article class="main_tutor_content_history">
                <header>
                    <h2>LibraTec</h2>
                </header>
                <p>Um projeto interessado em garantir a plenitude dos direitos da pessoa surda.</p>
            </article>
        </div>
    </section>
    <!--FIM DOBRA TUTOR-->
</main>

<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>