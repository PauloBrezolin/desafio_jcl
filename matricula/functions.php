<?php
function validarCadastro($nome, $email, $curso_id, $db) {
    // Verifica se o nome foi informado
    if (empty($nome)) {
        return "O campo Nome é obrigatório.";
    }

    // Verifica se o email foi informado
    if (empty($email)) {
        return "O campo E-mail é obrigatório.";
    }

    // Verifica se um curso foi escolhido
    if (empty($curso_id)) {
        return "É necessário escolher um curso.";
    }

    // Verifica se o aluno já foi cadastrado anteriormente
    $query = $db->prepare("SELECT COUNT(*) FROM alunos WHERE email = ?");
    $query->execute([$email]);
    $count = $query->fetchColumn();

    if ($count > 0) {
        return "O aluno já foi cadastrado anteriormente.";
    }

    return ""; // Retornar uma string vazia indica que não houve erros
}
?>