<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

require_once 'layouts/site/header.php';
require_once 'layouts/admin/menu.php';
require_once 'login.php';

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
    exit;
}
?>


<body>
    <section class="cards_selecao">
        <br>
        <div class="cards">
            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="usuario_admin_add_surdo.php" class="tirardecoracao">
                        <p><b>SURDO</b></p>
                        <br><br><br>
                        <img src="assets/img/surdoicon2.png" alt="" width="150px">
                    </a>
                </div>
            </div>

            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="usuario_admin_add_interprete.php" class="tirardecoracao">
                        <p><b>INTÉRPRETE</b></p>
                        <br><br><br>
                        <img src="assets/img/interprete.png" alt="" width="150px">
                    </a>
                </div>
            </div>

            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="usuario_admin_add_empresa.php" class="tirardecoracao">
                        <p><b>EMPRESA</b></p>
                        <br><br><br>
                        <img src="assets/img/empresa.png" alt="" width="150px">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

</body>

</html>