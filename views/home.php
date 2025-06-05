<section class="hero">
    <div class="hero-img">
        <img src="<?php echo BASE_URL ?>/assets/img/comidas.png" />
    </div>
    <div class="hero-main">
        <h1>
            Descubra restaurantes que atendem às suas restrições alimentares
        </h1>
        <p>
            Encontre locais que oferecem opções sem glúten, sem lactose,
            vegetarianas, veganas e muito mais, perto de você!
        </p>
        <div class="search">
            <form action="<?= BASE_URL ?>/search" method="GET" class="search-form">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
                <div class="search-inputs">
                    <input type="text"
                        id="searchAddress"
                        name="address"
                        placeholder="Em qual endereço você está?"
                        autocomplete="off"
                        required>

                    <input type="submit" value="Ver restaurantes próximos">
                </div>
                <h3>Adicionar filtros de restrições:</h3>
                <div class="restrictions">
                    <?php foreach ($restricoes as $restricao): ?>
                        <input type="checkbox" id="restricao_<?= $restricao['id_restricao'] ?>"
                            name="restrictions[]"
                            value="<?= $restricao['id_restricao'] ?>">
                        <label for="restricao_<?= $restricao['id_restricao'] ?>"><?= $restricao['restricao'] ?></label>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </div>
    <div class="hero-img">
        <img src="<?php echo BASE_URL ?>/assets/img/comidas.png" />
    </div>
</section>
<section class="sobre-nutrinfo" id="sobre-nos">
    <div class="container">
        <div class="item-esquerda">
            <h2>Comer bem, sem preocupações</h2>
            <p>
                Todos merecem desfrutar de uma refeição fora de casa sem
                medo ou dificuldades. O Nutrinfo nasce para conectar
                pessoas com restrições alimentares, intolerâncias, alergias
                ou dietas específicas a estabelecimentos seguros e
                confiáveis. Nossa plataforma facilita a busca por
                restaurantes e mercados que atendam às suas necessidades,
                garantindo mais segurança e praticidade na sua alimentação.
            </p>
        </div>
        <div class="item-direita">
            <img src="<?php echo BASE_URL ?>/assets/img/prato1.jpg" class="img-rounded" />
        </div>
    </div>

    <div class="container">
        <div class="item-esquerda">
            <img src="<?php echo BASE_URL ?>/assets/img/pessoasRestaurante.jpg" class="img-rounded" />
        </div>
        <div class="item-direita">
            <h2>Uma solução inspirada na vida real</h2>
            <p>
                Nossa ideia surgiu da experiência de amigos e familiares que
                enfrentam desafios diários para encontrar locais com opções
                adequadas. Sabemos o quanto essa busca pode ser cansativa
                e limitada. Por isso, desenvolvemos um sistema pensado para
                tornar essa escolha mais fácil, acessível e sem preocupações.
                Com o Nutrinfo, sua alimentação está sempre em boas mãos.
            </p>
        </div>
    </div>
</section>
<section class="parceiro" id="parceiro">
    <div class="parceiro-container">
        <div class="parceiro-content">
            <div class="parceiro-header">
                <h2>Torne-se parceiro e <span class="highlight">alcance mais clientes!</span></h2>
                <p class="parceiro-description">
                    Você possui um restaurante? Cada vez mais pessoas buscam restaurantes que atendam às suas dietas
                    restritivas e/ou restrições alimentares.
                </p>
            </div>

            <div class="parceiro-benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <img src="<?php echo BASE_URL ?>/assets/img/destaque.png" />
                    </div>
                    <div class="benefit-text">
                        <h3>Maior visibilidade</h3>
                        <p>Seu estabelecimento ganha destaque para um público que realmente valoriza o que você oferece.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <img src="<?php echo BASE_URL ?>/assets/img/alvo.png" />
                    </div>
                    <div class="benefit-text">
                        <h3>Público direcionado</h3>
                        <p>Conecte-se diretamente com pessoas que buscam opções confiáveis para suas restrições alimentares.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <img src="<?php echo BASE_URL ?>/assets/img/aperto-de-mao.png" />
                    </div>
                    <div class="benefit-text">
                        <h3>Construa confiança</h3>
                        <p>Mostre que seu restaurante se preocupa com alimentação acessível e segura para todos.</p>
                    </div>
                </div>
            </div>

            <div class="parceiro-cta">
                <a href="<?php echo CURRENT_URL ?>partner/register" class="btn-parceiro">Cadastre-se como parceiro</a>
            </div>
        </div>

        <div class="parceiro-visual">
            <div class="image-container">
                <img src="<?php echo BASE_URL ?>/assets/img/cliente.jpg" />
                <div class="image-overlay">
                    <div class="parceiro-stat">
                        <span class="stat-number">+80% </span>
                        <span class="stat-text"> das pessoas que comem fora de casa estão mais atentas à sua saúde</span>
                    </div>
                </div>
            </div>
            <div class="accent-shape"></div>
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/home/searchAddress.js"></script>
<script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        const selectedRestrictions = Array.from(document.querySelectorAll('input[name="restrictions[]"]:checked'))
            .map(cb => cb.value);
        document.getElementById('selectedRestrictions').value = selectedRestrictions.join(',');
    });
</script>