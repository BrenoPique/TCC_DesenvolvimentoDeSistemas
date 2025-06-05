<section class="upgrade">
    <div class="upgrade-container">
        <div class="upgrade-header">
            <h1>Upgrade para Premium</h1>
            <p>Aproveite todos os benefícios do Nutrinfo Premium</p>
        </div>

        <div class="plan-details">
            <div class="plan-price">
                <span class="price">R$ 9,90</span>
                <span class="period">/mês</span>
            </div>

            <div class="features-list">
                <div class="feature-item">
                    <img src="/assets/svg/check.svg" alt="check" />
                    <span>Informações nutricionais detalhadas</span>
                </div>
                <div class="feature-item">
                    <img src="/assets/svg/check.svg" alt="check" />
                    <span>Salve restaurantes favoritos ilimitados</span>
                </div>
                <div class="feature-item">
                    <img src="/assets/svg/check.svg" alt="check" />
                    <span>Descontos exclusivos em restaurantes parceiros</span>
                </div>
                <div class="feature-item">
                    <img src="/assets/svg/check.svg" alt="check" />
                    <span>Acesso prioritário a novos recursos</span>
                </div>
            </div>

            <form id="upgrade" method="POST" action="/user/process-upgrade">
                <div class="payment-details">
                    <h2>Dados do Pagamento</h2>
                    <div class="form-group">
                        <label>Número do Cartão</label>
                        <input type="text" name="cardNumber" required placeholder="0000 0000 0000 0000" />
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Validade</label>
                            <input type="text" name="expiry" required placeholder="MM/AA" />
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="text" name="cvv" required placeholder="000" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nome no Cartão</label>
                        <input type="text" name="cardName" required placeholder="Nome como está no cartão" />
                    </div>
                </div>
                <button type="submit" class="btn-upgrade">Fazer Upgrade Agora</button>
            </form>
        </div>
    </div>
</section>
<script type="module" src="/assets/js/user/upgrade.js"></script>