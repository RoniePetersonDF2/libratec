<?php
session_start();
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como surdo
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'SUR' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como surdo para acessar essa página!");
    exit;
}

$dbh = Conexao::getInstance();

// Consulta ao banco de dados
$query = "SELECT nome_interprete, endereco_interprete, celular_interprete, certificado
          FROM `libratecdb`.`interprete`";

$stmt = $dbh->prepare($query);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>';var_dump($resultados);exit;
?>

<style>
    body {
        background-color: #00002d;
    }
</style>
<!-- DOBRA CABEÇALHO -->
<section class="resultado_margem">
    <br>
    <h2 class="resultado-titulo">Área do Surdo</h2>
    <h3 class="introducao-area">Aqui você encontra intérpretes disponíveis para te atender no que precisar!</h3>

    <div class="cards-resultado">
        <?php foreach ($resultados as $resultado) : ?>
            <div class="card-item">
                <div class="nomecard">

                    <p class="campo-titulo">Nome do intérprete:</p>
                    <p class="campo-dado"><?php echo $resultado['nome_interprete']; ?></p>

                    <p class="campo-titulo">Descrição:</p>
                    <p class="campo-dado"><?php echo $resultado['certificado']; ?></p>

                    <div class="campo-resultado-endereco">
                        <p class="campo-titulo">Endereço do intérprete:</p>
                        <p class="campo-dado"><?php echo $resultado['endereco_interprete']; ?></p>
                    </div>

                    <div class="campo-resultado-telefone">
                        <p class="campo-titulo-telefone">Telefone do intérprete:</p>
                        <p class="campo-dado-telefone"><?php echo $resultado['celular_interprete']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
</body>

</html>