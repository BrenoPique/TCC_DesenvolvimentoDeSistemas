<?php
    function registrarPopup($type, $message) {
        header('Content-Type: application/json');
        // Prepara a resposta como JSON
        echo json_encode([
            'type' => $type,
            'message' => $message
        ]);
    }

    function redirecionar($url) {
        header("Location: $url");
        exit();
    }
?>