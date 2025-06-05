<div class="dashboard">
    <main class="dashboard-main">
        <header class="dashboard-header">
            <div class="welcome-message">
                <h1>Bem-vindo, <?php echo $nome_restaurante; ?></h1>
                <p>Gerencie seu restaurante de forma eficiente</p>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="<?php echo BASE_URL ?>/assets/svg/views.svg" alt="Visualizações">
                    </div>
                    <div class="stat-info">
                        <h3>Visualizações</h3>
                        <p class="stat-value"><?php echo $visualizacoes; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="<?php echo BASE_URL ?>/assets/svg/heart.svg" alt="Favoritos">
                    </div>
                    <div class="stat-info">
                        <h3>Favoritos</h3>
                        <p class="stat-value"><?php echo $favoritos; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="<?php echo BASE_URL ?>/assets/svg/food.svg" alt="Pratos">
                    </div>
                    <div class="stat-info">
                        <h3>Pratos</h3>
                        <p class="stat-value"><?php echo $total_pratos; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="<?php echo BASE_URL ?>/assets/img/star.png" alt="Nota Média">
                    </div>
                    <div class="stat-info">
                        <h3>Nota Média</h3>
                        <p class="stat-value"><?php echo number_format($rating['media'], 1); ?></p>
                    </div>
                </div>
            </div>

            <!-- Seção de Reviews -->
            <div class="recent-activities">
                <h2>Avaliações Recentes</h2>
                <div class="reviews-list">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="user-info">
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?= $i <= $review['nota'] ? 'filled' : '' ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <span class="review-date"><?= date('d/m/Y', strtotime($review['data_avaliacao'])) ?></span>
                                </div>
                                <p class="review-text"><?= htmlspecialchars($review['comentario']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-reviews">Nenhuma avaliação recebida ainda</p>
                    <?php endif; ?>
                </div>
            </div>
    </main>
</div>