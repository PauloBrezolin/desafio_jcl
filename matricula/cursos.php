<?php
require_once 'connection.php';

try {
    $query = $db->query("SELECT id, nome FROM cursos");
    $cursos = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar cursos: " . $e->getMessage();
    exit();
}
?>