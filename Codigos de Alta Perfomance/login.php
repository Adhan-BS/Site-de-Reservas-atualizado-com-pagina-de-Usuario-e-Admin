<?php
session_start();
require_once 'conexao.php'; //forca conexao com o banco

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    //procura o usuario no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
    $stmt->execute([$login, $senha]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        //guarda as informações no crachá
        $_SESSION['logado'] = true;
        $_SESSION['usuario_nome'] = $user['login'];
        $_SESSION['usuario_papel'] = $user['papel']; // ve se e admin ou cliente
        
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - RoomSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center mb-4">RoomSync</h3>
        
        <?php if($erro): ?>
            <div class="alert alert-danger text-center"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Usuário</label>
                <input type="text" name="login" class="form-control" placeholder="Ex: admin ou aluno" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" placeholder="Digite a senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar no Sistema</button>
        </form>
    </div>
</body>
</html>
</html>