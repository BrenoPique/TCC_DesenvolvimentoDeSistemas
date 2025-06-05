<?php
require_once __DIR__ . '/../models/FavoriteModel.php';

class FavoriteController
{
    public function toggle($params)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ]);
            exit;
        }

        $restaurantId = $params[0] ?? null;
        if (!$restaurantId) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID do restaurante não fornecido'
            ]);
            exit;
        }

        try {
            $favoriteModel = new FavoriteModel();
            $isFavorite = $favoriteModel->isFavorite($restaurantId);

            if ($isFavorite) {
                $success = $favoriteModel->removeFavorite($restaurantId);
                $message = 'Removido dos favoritos';
            } else {
                $success = $favoriteModel->addFavorite($restaurantId);
                $message = 'Adicionado aos favoritos';
            }

            echo json_encode([
                'success' => $success,
                'isFavorite' => !$isFavorite,
                'message' => $message
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao processar sua solicitação'
            ]);
        }
        exit;
    }
}
