<?php
require_once 'connection.php';
require_once 'functions.php';
require_once 'cursos.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $curso_id = $_POST['curso'];

    $erro = validarCadastro($nome, $email, $curso_id, $db);

    if (empty($erro)) {
        $query = $db->prepare("INSERT INTO alunos (nome_completo, email, curso_id) VALUES (?, ?, ?)");
        $query->execute([$nome, $email, $curso_id]);

        echo "Aluno cadastrado com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cadastro de Aluno</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      padding: 20px;
    }

    h1 {
      color: #333;
    }

    form {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 10px;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    p.error-message {
      color: red;
    }
  </style>
</head>
<body>
  <h1>Bem vindo(a) a matrícula do aluno!</h1>
  <form method="POST" action="">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" required>
    <br><br>
    <label for="email">E-mail:</label>
    <input type="email" name="email" required>
    <br><br>
    <label for="curso">Curso:</label>
    <select name="curso" required>
      <?php foreach ($cursos as $curso): ?>
        <option value="<?php echo $curso['id']; ?>"><?php echo $curso['nome']; ?></option>
      <?php endforeach; ?>
    </select>
    <br><br>
    <input type="submit" name="enviar" value="Cadastrar">
  </form>
  <?php if (!empty($erro)): ?>
    <p class="error-message"><?php echo $erro; ?></p>
  <?php endif; ?>
</body>
</html>
