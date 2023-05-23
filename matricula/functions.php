<?php
function validarCadastro($nome, $email, $curso_id, $db) {
    if (empty($nome)) {
        return "O campo Nome é obrigatório.";
    }

    if (empty($email)) {
        return "O campo E-mail é obrigatório.";
    }

    if (empty($curso_id)) {
        return "É necessário escolher um curso.";
    }

    $query = $db->prepare("SELECT COUNT(*) FROM alunos WHERE email = ?");
    $query->execute([$email]);
    $count = $query->fetchColumn();

    if ($count > 0) {
        return "O aluno já foi cadastrado anteriormente.";
    }

    return "";
}
?>
