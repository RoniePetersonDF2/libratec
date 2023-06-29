<?php

    require_once 'layouts/site/header.php';
    require_once 'layouts/site/menu.php';
    require_once 'login.php';

?>


<body>
    
    <section class="cards_selecao">
        <br>
        <div class="cards">
            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="cadastra_surdo.php" class="tirardecoracao">
                        <p class="campo-titulo">SURDO</p>
                        <br><br><br>
                        <img src="assets/img/surdoicon2.png" alt="" width="150px">
                    </a>
                </div>
            </div>

            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="cadastra_interprete.php" class="tirardecoracao">
                        <p class="campo-titulo">INTÉRPRETE</p>
                        <br><br><br>
                        <img src="assets/img/interprete.png" alt="" width="150px">
                    </a>
                </div>
            </div>

            <div class="card-item-opcao">
                <div class="nomecard">
                    <a href="cadastra_empresa.php" class="tirardecoracao">
                        <p class="campo-titulo">EMPRESA</p>
                        <br><br><br>
                        <img src="assets/img/empresa.png" alt="" width="150px">
                    </a>
                </div>
            </div>
        <!-- </div><br><br><br><br><br><br><br> -->
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