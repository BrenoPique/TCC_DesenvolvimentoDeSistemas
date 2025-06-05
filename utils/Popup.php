<?php

class Popup
{
    public static function show($message, $type, $redirect = null)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        if ($type === 'error') {
            echo json_encode([
                "type" => "error",
                "message" => $message,
                "redirect" => $redirect
            ]);
        } elseif ($type === 'success') {
            echo json_encode([
                "type" => "success",
                "message" => $message,
                "redirect" => $redirect
            ]);
        } else {
            echo json_encode([
                "type" => "error",
                "message" => "Erro.",
                "redirect" => $redirect
            ]);
        }
    }

    public static function showError($message, $redirect = null)
    {
        self::show($message, 'error', $redirect);
    }

    public static function showSuccess($message, $redirect = null)
    {
        self::show($message, 'success', $redirect);
    }
}
