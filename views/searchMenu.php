<div class="menu-page">
    <div class="restaurant-header">
        <?php
        // Verifica logo do restaurante usando time() para evitar cache
        $logoPath = BASE_URL . '/uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg?v=' . time();
        $defaultLogo = BASE_URL . '/assets/img/restaurant-default.png';

        // Verifica se o arquivo existe no servidor
        $serverPath = __DIR__ . '/../uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg';
        $restaurantImage = file_exists($serverPath) ? $logoPath : $defaultLogo;
        ?>
        <div class="restaurant-info">
            <div class="restaurant-main">
                <h1><?= htmlspecialchars($restaurant['nome']) ?></h1>
                <!-- Adicionar rating do restaurante -->
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
                <div class="restaurant-details">
                    <p><i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars("{$restaurant['rua']}, {$restaurant['numero']} - {$restaurant['bairro']}") ?>
                    </p>
                    <p><i class="far fa-clock"></i>
                        <?= htmlspecialchars($restaurant['horario_funcionamento']) ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="restaurant-actions">
            <!-- Botão de favoritar igual ao search.php -->
            <button class="favorite-btn <?= isset($_SESSION['user_id']) && ($restaurant['is_favorite'] ?? false) ? 'is-favorite' : '' ?>"
                data-restaurant-id="<?= $restaurant['id_restaurante'] ?>"
                data-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
                <i class="fas fa-heart"></i>
            </button>
            <!-- Botão de avaliação igual ao search.php -->
            <button class="review-btn"
                onclick="handleReviewClick(event, <?= $restaurant['id_restaurante'] ?>, '<?= htmlspecialchars($restaurant['nome']) ?>', <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>)"
                title="Avaliar">
                <i class="fas fa-star"></i>
            </button>
        </div>
        <div class="restaurant-image">
            <img src="<?= $restaurantImage ?>" alt="<?= htmlspecialchars($restaurant['nome']) ?>">
        </div>
    </div>

    <div class="menu-content">
        <div class="menu-filters">
            <h3>Filtrar por Restrições</h3>
            <div class="filter-restrictions">
                <?php foreach ($restricoes as $restricao): ?>
                    <label class="filter-item">
                        <input type="checkbox" name="filter_restricao" value="<?= $restricao['id_restricao'] ?>"
                            data-restricao="<?= $restricao['id_restricao'] ?>">
                        <span><?= htmlspecialchars($restricao['restricao']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="menu-items">
            <?php if (empty($dishes)): ?>
                <div class="no-dishes">
                    <p>Nenhum prato cadastrado ainda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($dishes as $dish): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <?php
                            // Mesma lógica para imagens dos pratos
                            $dishImage = BASE_URL . '/uploads/partner/' . $restaurant['id_restaurante'] . '/' . $dish['id_prato'] . '.jpg?v=' . time();
                            $serverDishPath = __DIR__ . '/../uploads/partner/' . $restaurant['id_restaurante'] . '/' . $dish['id_prato'] . '.jpg';
                            $finalPath = file_exists($serverDishPath) ? $dishImage : $restaurantImage;
                            ?>
                            <img src="<?= $finalPath ?>" alt="<?= htmlspecialchars($dish['nome']) ?>">
                        </div>
                        <div class="item-info">
                            <h3><?= htmlspecialchars($dish['nome']) ?></h3>
                            <p class="item-description"><?= htmlspecialchars($dish['descricao']) ?></p>
                            <div class="item-footer">
                                <span class="price">R$ <?= number_format($dish['preco'], 2, ',', '.') ?></span>
                                <?php if (!empty($dish['restricoes'])): ?>
                                    <div class="item-restrictions">
                                        <?php foreach ($restricoes as $restricao): ?>
                                            <?php if (in_array($restricao['id_restricao'], $dish['restricoes'])): ?>
                                                <span class="restriction-tag"><?= htmlspecialchars($restricao['restricao']) ?></span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Avaliação (igual ao search.php) -->
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

    <!-- Seção de Reviews -->
    <div class="recent-activities">
        <h2>Avaliações do Restaurante</h2>
        <div class="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $review['nota'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <span class="review-date"><?php echo date('d/m/Y', strtotime($review['data_avaliacao'])); ?></span>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($review['comentario']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-reviews">Nenhuma avaliação recebida ainda</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    window.BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>/assets/js/home/menu.js"></script>
<script src="<?= BASE_URL ?>/assets/js/home/favorites.js"></script>
<script type="module" src="<?= BASE_URL ?>/assets/js/popup/mostrarpopup.js"></script>
<script type="module" src="<?= BASE_URL ?>/assets/js/home/search.js"></script>