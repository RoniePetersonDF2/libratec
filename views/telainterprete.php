<?php
session_start();
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como surdo
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'INT' && $_SESSION['usuario']['perfil'] != 'EMP' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como intérprete ou empresa para acessar essa página!");
    exit;
}

$dbh = Conexao::getInstance();

// Consulta ao banco de dados
$query = "SELECT vagas.descricao, empresa.nome_empresa, empresa.telefone_empresa
          FROM `libratecdb`.`vagas`
          JOIN `libratecdb`.`empresa` ON vagas.id_empresa = empresa.id_empresa";

$stmt = $dbh->prepare($query);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';var_dump($resultados);exit;

?>


<style>
    body {
        background-color: #00002d;
    }
</style>
<!-- DOBRA CABEÇALHO -->
<section class="resultado_margem">
    <br>
    <h2 class="resultado-titulo">Área do Intérprete</h2>
    <h3 class="introducao-area">Abaixo estão listadas as empresas que abriram vagas para trabalhos de intérpretes. Caso você esteja interessado, sinta-se à vontade para entrar em contato por meio dos telefones disponíveis.</h3>

    <div class="cards">
        <?php foreach ($resultados as $resultado) : ?>
            <div class="card-item-empresa">
                <div class="nomecard">

                    <p class="campo-titulo">Nome da empresa:</p>
                    <p class="campo-dado"><?php echo $resultado['nome_empresa']; ?></p>

                    <p class="campo-titulo">Descrição da vaga:</p>
                    <p class="campo-dado"><?php echo $resultado['descricao']; ?></p>

                    <div class="campo-resultado-telefone">
                        <p class="campo-titulo-telefone">Telefone de contato da empresa:</p>
                        <p class="campo-dado-telefone"><?php echo $resultado['telefone_empresa']; ?></p>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
</body>

</html>