<?php
require_once 'connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("DELETE FROM alunos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $response = array('Aluno excluido com sucesso.');
        echo json_encode($response);

        $db = null;
    } catch (PDOException $e) {
        $response = array('error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage());
        echo json_encode($response);
        exit();
    }
}
?>