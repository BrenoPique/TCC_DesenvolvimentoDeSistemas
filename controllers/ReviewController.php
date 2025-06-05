<?php
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../utils/Popup.php';

class ReviewController
{
    private $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function submit()
    {
        ob_clean();

        if (!isset($_SESSION['user_id'])) {
            Popup::showError('Você precisa estar logado para enviar uma avaliação.');
            exit;
        }

        $restaurantId = isset($_POST['restaurantId']) ? (int)$_POST['restaurantId'] : null;
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
        $reviewText = isset($_POST['reviewText']) ? trim($_POST['reviewText']) : null;
        $userId = $_SESSION['user_id'];

        if (!$restaurantId || !$rating || !$reviewText) {
            Popup::showError('Por favor, preencha todos os campos');
            exit;
        }

        try {
            $success = $this->reviewModel->addReview($restaurantId, $userId, $reviewText, $rating);

            if ($success) {
                Popup::showSuccess('Avaliação enviada com sucesso');
            } else {
                Popup::showError('Você já avaliou este restaurante');
            }
        } catch (Exception $e) {
            Popup::showError('Erro ao enviar avaliação');
        }
        exit;
    }
}
