<?php
require_once 'connection.php';
require_once 'cursos.php';

function editarAluno($db, $id, $nome, $email, $curso_id) {
    $sql = "UPDATE alunos SET nome_completo = :nome, email = :email, curso_id = :curso_id WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
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
