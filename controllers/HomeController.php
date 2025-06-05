<?php
require_once __DIR__ . '/../utils/RenderView.php';
require_once __DIR__ . '/../models/RestrictionModel.php';

class HomeController
{
    public function index()
    {
        $restrictionModel = new RestrictionModel();
        $restricoes = $restrictionModel->getRestrictions();

        return RenderView::loadView('home', [
            'nav' => 'navHome',
            'footer' => 'footerDefault',
            'css' => BASE_URL . '/assets/css/home/style.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
            'cssfooter' => BASE_URL . '/assets/css/partials/footer.css',
            'restricoes' => $restricoes
        ]);
    }

    public function getPlacesAutocomplete()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['query'])) {
            echo json_encode(['error' => 'Parâmetro query é obrigatório']);
            exit;
        }

        // $query = $_GET['query'];
        // $apiKey = $_ENV['GOOGLE_MAPS_API_KEY'];

        // $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';
        // $params = http_build_query([
        //     'input' => $query,
        //     'types' => 'address',
        //     'language' => 'pt-BR',
        //     'components' => 'country:br',
        //     'key' => $apiKey
        // ]);

        // $response = file_get_contents($url . '?' . $params);
        // echo $response;
        // exit;

        $query = strtolower($_GET['query']);
        $mockData = json_decode(file_get_contents(__DIR__ . '/../data/mockPlaces.json'), true);

        // Filtra os resultados baseado na query
        $filteredPredictions = array_filter($mockData['predictions'], function ($place) use ($query) {
            return strpos(strtolower($place['description']), $query) !== false;
        });

        echo json_encode(['predictions' => array_values($filteredPredictions)]);
        exit;
    }
    public function search()
    {
        $restrictionModel = new RestrictionModel();
        $partnerModel = new PartnerModel();
        $favoriteModel = new FavoriteModel();

        // Ajuste para pegar as restrições do parâmetro correto
        $selectedRestrictions = isset($_GET['restrictions']) ? $_GET['restrictions'] : [];
        error_log("Restrições recebidas: " . print_r($selectedRestrictions, true));

        // Buscar restaurantes baseado nas restrições
        $restaurants = empty($selectedRestrictions)
            ? $partnerModel->getAllPartners()
            : $partnerModel->getPartnersByRestrictions($selectedRestrictions);

        // Adicionar informações extras para cada restaurante
        foreach ($restaurants as &$restaurant) {
            // Adiciona as restrições do restaurante
            $restaurant['restrictions'] = $restrictionModel->getRestrictionsRestaurant($restaurant['id_restaurante']);

            // Formata o endereço completo
            $restaurant['endereco'] = "{$restaurant['rua']}, {$restaurant['numero']} - {$restaurant['bairro']}";

            // Formata o horário
            $restaurant['horario'] = $restaurant['horario_funcionamento'] ?? 'Horário não informado';

            // A média já vem do banco de dados agora
            $restaurant['rating'] = number_format(floatval($restaurant['rating']), 1);
            $restaurant['total_avaliacoes'] = intval($restaurant['total_avaliacoes']);

            // Define status de favorito como falso por padrão
            $restaurant['is_favorite'] = false;

            // Verifica se o restaurante é favorito para o usuário atual
            if (isset($_SESSION['user_id'])) {
                $restaurant['is_favorite'] = $favoriteModel->isFavorite($restaurant['id_restaurante']);
                // Debug log
                error_log("Restaurante {$restaurant['id_restaurante']} favorito: " . ($restaurant['is_favorite'] ? 'sim' : 'não'));
            } else {
                $restaurant['is_favorite'] = false;
            }
        }

        return RenderView::loadView('search', [
            'title' => 'Busca de Restaurantes',
            'nav' => 'navHome',
            'footer' => 'footerDefault',
            'css' => BASE_URL . '/assets/css/home/consult/default.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
            'cssfooter' => BASE_URL . '/assets/css/partials/footer.css',
            'restricoes' => $restrictionModel->getRestrictions(),
            'restaurants' => $restaurants
        ]);
    }
}
