<?php
require_once 'connection.php';
require_once 'cursos.php';

function listarAlunos($db) {
    $sql = "SELECT * FROM alunos";
    $stmt = $db->query($sql);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($alunos) > 0) {
        echo "<h2>Lista de Alunos</h2>";
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>";

        foreach ($alunos as $aluno) {
            echo "<tr>
                    <td>".$aluno['id']."</td>
                    <td>".$aluno['nome_completo']."</td>
                    <td>".$aluno['email']."</td>
                    <td>
                        <a href='editar.php?id=".$aluno['id']."'>Editar</a>
                        <a href='excluir.php?id=".$aluno['id']."'>Excluir</a>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "Nenhum aluno encontrado.";
    }
}

function editarAluno($db, $id, $nome, $email, $curso_id) {
    $sql = "UPDATE alunos SET nome_completo = :nome, email = :email, curso_id = :curso_id WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

if (isset($_POST['listar'])) {
    try {
        $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        listarAlunos($db);
        $db = null;
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        exit();
    }
}

if (isset($_POST['editar'])) {
    try {
        $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $curso_id = $_POST['curso'];

        editarAluno($db, $id, $nome, $email, $curso_id);

        echo "Aluno editado com sucesso!";

        $db = null;
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Alunos</title>
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

        h2 {
            color: #666;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f5f5f5;
        }

        form {
            margin-top: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
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

        input[type="submit"],
        input[type="button"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #45a049;
        }

        p.error-message {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento de Alunos</h1>

    <h2>Listar Alunos</h2>
    <form method="post">
        <input type="submit" name="listar" value="Listar Alunos">
    </form>

    <h2>Inserir Aluno</h2>
    <form method="post" action="inserir.php">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="curso">Curso:</label>
        <select name="curso" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo $curso['id']; ?>"><?php echo $curso['nome']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="Inserir Aluno">
    </form>

    <h2>Editar Aluno</h2>
    <?php
    if (isset($_GET['id'])) {
        $aluno_id = $_GET['id'];
        try {
            $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM alunos WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $aluno_id);
            $stmt->execute();
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
            $db = null;
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
            exit();
        }
        if ($aluno) {
    ?>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $aluno['id']; ?>">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?php echo $aluno['nome_completo']; ?>" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $aluno['email']; ?>" required>
                <br>
                <label for="curso">Curso:</label>
                <select name="curso" required>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo $curso['id']; ?>" <?php if ($curso['id'] == $aluno['curso_id']) echo 'selected'; ?>><?php echo $curso['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br><br>
                <input type="submit" name="editar" value="Editar Aluno">
            </form>
    <?php
        } else {
            echo "<p class='error-message'>Aluno não encontrado.</p>";
        }
    } else {
        ?>
        <form method="get" action="">
            <label for="id">ID do Aluno:</label>
            <input type="number" name="id" required>
            <br>
            <input type="submit" value="Editar">
        </form>
    <?php
    }
    ?>

    <h2>Excluir Aluno</h2>
    <form id="formExcluirAluno" method="post" action="excluir.php">
        <label for="alunoId">ID:</label>
        <input type="number" name="id" id="alunoId" required>
        <br>
        <input type="submit" value="Excluir Aluno">
    </form>

    <script>
        function confirmarExclusao() {
            var alunoId = document.getElementById('alunoId').value;
            var confirmacao = confirm("Certeza que deseja excluir o aluno de ID " + alunoId + "?");
            return confirmacao;
        }

        var formExcluirAluno = document.getElementById('formExcluirAluno');
        formExcluirAluno.addEventListener('submit', function(event) {
            var confirmado = confirmarExclusao();
            if (!confirmado) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
