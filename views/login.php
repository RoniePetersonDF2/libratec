<?php
    # inclui a classe de conexão com o banco de dados.
    require_once "../database/conexao.php";

    # verifica se os dados do formulário foram passados via método POST.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        # cria duas variáveis (email, password) para armazenar os dados passados via método POST.
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? md5($_POST['password']) : '';

        # cria a variável $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();

        # cria uma consulta no banco de dados verificando se o usuário existe na tabela 'surdo'.
        $querySurdo = "SELECT * FROM `libratecdb`.`surdo` WHERE email_surdo = :email AND senha_surdo = :password";
        $stmtSurdo = $dbh->prepare($querySurdo);
        $stmtSurdo->bindParam(':email', $email);
        $stmtSurdo->bindParam(':password', $password);

        # executa a consulta no banco de dados para verificar o usuário na tabela 'surdo'.
        $stmtSurdo->execute();
        $rowSurdo = $stmtSurdo->fetch(PDO::FETCH_ASSOC);

        # verifica se o resultado retornado da tabela 'surdo' é diferente de NULL.
        if ($rowSurdo) {
            $_SESSION['usuario'] = [
                'nome_surdo' => $rowSurdo['nome_surdo'],
                'perfil' => $rowSurdo['perfil'],
                'id' => $rowSurdo['id_surdo'],
            ];

            if ($rowSurdo['perfil'] === 'ADM') {
                header('location: usuario_admin.php');
                exit;
            } else {
                header('location: index.php');
                exit;
            }
        }

        # cria uma consulta no banco de dados verificando se o usuário existe na tabela 'interprete'.
        $queryInterprete = "SELECT * FROM `libratecdb`.`interprete` WHERE email_interprete = :email AND senha_interprete = :password";
        $stmtInterprete = $dbh->prepare($queryInterprete);
        $stmtInterprete->bindParam(':email', $email);
        $stmtInterprete->bindParam(':password', $password);

        # executa a consulta no banco de dados para verificar o usuário na tabela 'interprete'.
        $stmtInterprete->execute();
        $rowInterprete = $stmtInterprete->fetch(PDO::FETCH_ASSOC);

        # verifica se o resultado retornado da tabela 'interprete' é diferente de NULL.
        if ($rowInterprete) {
            $_SESSION['usuario'] = [
                'nome_interprete' => $rowInterprete['nome_interprete'],
                'perfil' => $rowInterprete['perfil'],
                'id' => $rowInterprete['id_interprete'],
            ];

            if ($rowInterprete['perfil'] === 'ADM') {
                header('location: usuario_admin.php');
                exit;
            } else {
                header('location: index.php');
                exit;
            }
        }

        # cria uma consulta no banco de dados verificando se o usuário existe na tabela 'empresa'.
        $queryEmpresa = "SELECT * FROM `libratecdb`.`empresa` WHERE email_empresa = :email AND senha_empresa = :password";
        $stmtEmpresa = $dbh->prepare($queryEmpresa);
        $stmtEmpresa->bindParam(':email', $email);
        $stmtEmpresa->bindParam(':password', $password);

        # executa a consulta no banco de dados para verificar o usuário na tabela 'empresa'.
        $stmtEmpresa->execute();
        $rowEmpresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

        # verifica se o resultado retornado da tabela 'empresa' é diferente de NULL.
        if ($rowEmpresa) {
            $_SESSION['usuario'] = [
                'nome_empresa' => $rowEmpresa['nome_empresa'],
                'perfil' => $rowEmpresa['perfil'],
                'id' => $rowEmpresa['id_empresa'],
            ];

            if ($rowEmpresa['perfil'] === 'ADM') {
                header('location: usuario_admin.php');
                exit;
            } else {
                header('location: index.php');
                exit;
            }
        }

        # se nenhum resultado foi encontrado nas tabelas 'surdo', 'interprete' e 'empresa', destrói todas as sessões existentes e redireciona para a página inicial.
        session_destroy();
        header('location: index.php?error=Usuário ou senha inválidos.');

        # destroi a conexão com o banco de dados.
        $dbh = null;
    }
?>


<!--POP LOGIN-->
<div class="overlay"></div>
<div class="modal">

    <div class="div_login">
        <form action="index.php" method="post">
            <h1>Login</h1><br>
            <input type="email" name="email" placeholder="Email" class="input" required autofocus>
            <br><br>
            <input type="password" name="password" placeholder="Senha" class="input" required>
            <br><br>
            <button class="button">Enviar</button>
        </form>
        
        <div class="novo__form__login">
            <a href="tela.php">Criar conta</a>
        </div>
    </div>

</div>
<!--FIM POP LOGIN-->