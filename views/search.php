<div class="search-page">
    <header class="search-header">
        <div class="location-info">
            <i class="fas fa-map-marker-alt"></i>
            <div class="location-text">
                <span>Restaurantes próximos a</span>
                <h2><?= htmlspecialchars($_GET['address'] ?? '') ?></h2>
            </div>
            <a href="<?= BASE_URL ?>/" class="change-location">
                <i class="fas fa-pencil-alt"></i>
                <span>Alterar</span>
            </a>
        </div>
    </header>

    <div class="search-content">
        <div class="map-container">
            <div id="map"></div>
        </div>

        <div class="restaurants-container">
            <div class="results-info">
                <h3>Encontramos <?= count($restaurants) ?> restaurantes</h3>
            </div>

            <div class="restaurants-list">
                <?php if (empty($restaurants)): ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Nenhum restaurante encontrado</h3>
                        <p>Tente buscar em outra região</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <div class="restaurant-card" data-lat="<?= $restaurant['latitude'] ?>" data-lng="<?= $restaurant['longitude'] ?>">
                            <div class="restaurant-image">
                                <?php
                                // Verifica logo do restaurante usando time() para evitar cache
                                $logoPath = BASE_URL . '/uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg?v=' . time();
                                $defaultLogo = BASE_URL . '/assets/img/restaurant-default.png';

                                // Verifica se o arquivo existe no servidor
                                $serverPath = __DIR__ . '/../uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg';
                                $restaurantImage = file_exists($serverPath) ? $logoPath : $defaultLogo;
                                ?>
                                <img src="<?= $restaurantImage ?>" alt="<?= htmlspecialchars($restaurant['nome']) ?>">
                                <div class="image-actions">
                                    <button class="favorite-btn <?= isset($_SESSION['user_id']) && $restaurant['is_favorite'] ? 'is-favorite' : '' ?>"
                                        data-restaurant-id="<?= $restaurant['id_restaurante'] ?>"
                                        data-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="review-btn"
                                        onclick="handleReviewClick(event, <?= $restaurant['id_restaurante'] ?>, '<?= htmlspecialchars($restaurant['nome']) ?>', <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>)"
                                        title="Avaliar">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="restaurant-info">
                                <div class="restaurant-header">
                                    <h3><?= htmlspecialchars($restaurant['nome']) ?></h3>
                                    <div class="rating">
                                        <?php
                                        $rating = floatval($restaurant['rating']);
                                        for ($i = 1; $i <= 5; $i++):
                                        ?>
                                            <?php if ($i <= $rating): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif ($i - 0.5 <= $rating): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="rating-count">(<?= $restaurant['total_avaliacoes'] ?>)</span>
                                    </div>
                                </div>

                                <p class="restaurant-address">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($restaurant['endereco']) ?>
                                </p>

                                <div class="restaurant-schedule">
                                    <i class="far fa-clock"></i>
                                    <span><?= htmlspecialchars($restaurant['horario']) ?></span>
                                </div>

                                <div class="restaurant-restrictions">
                                    <?php foreach ($restaurant['restrictions'] as $restriction): ?>
                                        <span class="restriction-tag">
                                            <?= htmlspecialchars($restriction) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <a href="<?= BASE_URL ?>/menu/<?= $restaurant['id_restaurante'] ?>" class="view-menu">
                                    <i class="fas fa-utensils"></i>
                                    Ver cardápio
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Avaliação -->
<div class="review-modal" id="reviewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Avaliar <span id="restaurantName"></span></h2>
            <button class="modal-close" onclick="closeReviewModal()">&times;</button>
        </div>
        <form id="reviewForm" onsubmit="submitReview(event)">
            <input type="hidden" id="restaurantId" name="restaurantId">
            <div class="rating-select">
                <label>Sua avaliação:</label>
                <div class="stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="reviewText">Comentário:</label>
                <textarea id="reviewText" name="reviewText" required></textarea>
            </div>
            <button type="submit" class="submit-review">Enviar Avaliação</button>
        </form>
    </div>
</div>

<!-- No final do arquivo, antes de fechar o body -->
<script>
    // Definir variáveis globais necessárias para o mapa
    window.centerLat = <?= $_GET['lat'] ?? 'null' ?>;
    window.centerLng = <?= $_GET['lng'] ?? 'null' ?>;
    window.BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>"></script>
<script type="module" src="<?= BASE_URL ?>/assets/js/popup/mostrarpopup.js"></script>
<script type="module" src="<?= BASE_URL ?>/assets/js/home/search.js"></script>
<script src="<?= BASE_URL ?>/assets/js/home/favorites.js"></script>
<script>
    // Inicializar o mapa após o carregamento do módulo
    window.addEventListener('load', () => {
        if (typeof initMap === 'function') {
            initMap();
        }
    });
</script>