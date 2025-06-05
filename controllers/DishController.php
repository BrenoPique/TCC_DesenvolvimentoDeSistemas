<?php
require_once __DIR__ . '/../utils/RenderView.php';
require_once __DIR__ . '/../models/DishModel.php';
require_once __DIR__ . '/../models/PartnerModel.php';
require_once __DIR__ . '/../models/RestrictionModel.php';
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../models/FavoriteModel.php'; // Adicionar esta linha

class DishController
{
    private $dishModel;
    private $partnerModel;
    private $restrictionModel;
    private $reviewModel;
    private $favoriteModel; // Adicionar esta linha

    public function __construct()
    {
        $this->dishModel = new DishModel();
        $this->partnerModel = new PartnerModel();
        $this->restrictionModel = new RestrictionModel();
        $this->reviewModel = new ReviewModel();
        $this->favoriteModel = new FavoriteModel(); // Adicionar esta linha
    }

    public function index($params)
    {
        $restaurantId = isset($params[0]) ? intval($params[0]) : null;

        if (!$restaurantId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        // Verifica visualizações diárias
        $today = date('Y-m-d');
        $viewKey = "restaurant_view_{$restaurantId}_{$today}";

        if (!isset($_SESSION[$viewKey])) {
            // Incrementa visualização apenas uma vez por dia
            $this->partnerModel->incrementViews($restaurantId);
            $_SESSION[$viewKey] = true;
        }

        $restaurant = $this->partnerModel->getPartnerById($restaurantId);
        $dishes = $this->dishModel->getDishesByRestaurant($restaurantId);
        $restricoes = $this->restrictionModel->getRestrictions();

        // Buscar avaliações do restaurante
        $reviews = $this->reviewModel->getReviewsWithUserInfo($restaurantId);
        $rating = $this->reviewModel->getRestaurantRating($restaurantId);

        // Processar os dados do restaurante
        $restaurant['rating'] = number_format($rating['media'], 1);
        $restaurant['total_avaliacoes'] = $rating['total'];

        // Adicionar verificação de favorito
        if (isset($_SESSION['user_id'])) {
            $restaurant['is_favorite'] = $this->favoriteModel->isFavorite($restaurantId);
        } else {
            $restaurant['is_favorite'] = false;
        }

        foreach ($dishes as &$dish) {
            $dish['restricoes'] = $this->restrictionModel->getRestrictionsDish($dish['id_prato']);
        }

        return RenderView::loadView('searchMenu', [
            'title' => $restaurant['nome'] . ' - Cardápio',
            'nav' => 'navHome',
            'css' => BASE_URL . '/assets/css/home/menu/default.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
            'restaurant' => $restaurant,
            'dishes' => $dishes,
            'restricoes' => $restricoes,
            'reviews' => $reviews
        ]);
    }
}
