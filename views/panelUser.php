<section class="fav">
  <div class="fav-container">
    <div class="fav-content">
      <h1>Olá, <?php echo ucfirst(mb_strtolower(explode(' ', trim($_SESSION['nome']))[0])); ?></h1>

      <div class="fav-section">
        <h2>Seus Restaurantes Favoritos</h2>
        <div class="favoritos-list">
          <?php if (empty($favoriteRestaurants)): ?>
            <p class="no-favorites">Você ainda não tem restaurantes favoritos.</p>
          <?php else: ?>
            <?php foreach ($favoriteRestaurants as $restaurant): ?>
              <div class="favorito-item">
                <a href="<?= BASE_URL ?>/menu/<?= $restaurant['id_restaurante'] ?>" class="favorito-link">
                  <img src="<?= $restaurant['image'] ?>" alt="<?= htmlspecialchars($restaurant['nome']) ?>" />
                  <h3><?= htmlspecialchars($restaurant['nome']) ?></h3>
                  <button class="favorite-btn is-favorite"
                    data-restaurant-id="<?= $restaurant['id_restaurante'] ?>"
                    data-logged-in="true">
                    <i class="fas fa-heart"></i>
                  </button>
                </a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/" class="btn">Buscar Mais Restaurantes</a>
    </div>
  </div>
</section>
<script src="<?= BASE_URL ?>/assets/js/home/favorites.js"></script>
<section class="premium">
  <div class="container">
    <div class="header-premium">
      <h1>Nutrinfo Premium</h1>
    </div>
    <div class="features">
      <div class="feature-card">
        <div class="icon">
          <svg viewBox="0 0 24 24">
            <path
              d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-5h-2v-6h2v6zm0-8h-2v-2h2v2z" />
          </svg>
        </div>
        <h3>Informações Nutricionais Detalhadas</h3>
        <p>
          Acesse dados completos sobre ingredientes, calorias e alérgenos de
          cada prato.
        </p>
      </div>
      <div class="feature-card">
        <div class="icon">
          <svg viewBox="0 0 24 24">
            <path
              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
          </svg>
        </div>
        <h3>Restaurantes Favoritos</h3>
        <p>
          Salve seus restaurantes preferidos para acessá-los
          facilmente a qualquer momento.
        </p>
      </div>
      <div class="feature-card">
        <div class="icon">
          <svg viewBox="0 0 24 24">
            <path
              d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
          </svg>
        </div>
        <h3>Descontos em Restaurantes Parceiros</h3>
        <p>
          Aproveite ofertas exclusivas e descontos especiais em restaurantes
          cadastrados na Nutrinfo.
        </p>
      </div>
    </div>
    <div class="cta">
      <button class="btn-upgrade">Ter meu upgrade</button>
      <span class="price">Apenas <span>R$ 9,90</span>/mês</span>
    </div>
  </div>
</section>