<div class="dashboard">
    <main class="dashboard-main">
        <header class="menu-header">
            <div class="header-content">
                <h1>Cardápio</h1>
                <p>Gerencie os pratos do seu restaurante</p>
            </div>
            <button class="btn-add-prato">
                <img src="/assets/svg/plus.svg" alt="Adicionar">
                Adicionar Prato
            </button>
        </header>
        <?php

        $logoPath = __DIR__ . '/../uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg';

        if (file_exists($logoPath)) {
            $defaultImage = BASE_URL . '/uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg';
        } else {
            $defaultImage = BASE_URL . '/assets/img/restaurant-default.png';
        }


        ?>

        <div class="pratos-grid">
            <?php if (empty($pratos)): ?>
                <div class="no-pratos">
                    <img src="/assets/svg/empty-plate.svg" alt="Sem pratos">
                    <p>Nenhum prato cadastrado ainda</p>
                    <button class="btn-add-prato">Adicionar Primeiro Prato</button>
                </div>
            <?php else: ?>
                <?php foreach ($pratos as $prato): ?>
                    <div class="prato-card">
                        <div class="prato-img">
                            <img src="<?php
                                        $dishImagePath = BASE_URL . '/uploads/partner/' . $_SESSION['partner_id'] . '/' . $prato['id_prato'] . '.jpg';

                                        $serverPath = __DIR__ . '/../uploads/partner/' . $_SESSION['partner_id'] . '/' . $prato['id_prato'] . '.jpg';

                                        // Adiciona timestamp para evitar cache da imagem
                                        $timestamp = time();
                                        $finalPath = file_exists($serverPath) ? $dishImagePath . "?t={$timestamp}" : $defaultImage;
                                        echo $finalPath;
                                        ?>" alt="<?php echo $prato['nome']; ?>">
                        </div>
                        <div class="prato-info">
                            <h3><?php echo $prato['nome']; ?></h3>
                            <p class="prato-descricao"><?php echo $prato['descricao']; ?></p>
                            <div class="prato-restrictions">
                                <?php
                                $pratoRestricoes = $restrictionModel->getRestrictionsDish($prato['id_prato']);
                                foreach ($restricoes as $restricao):
                                    if (in_array($restricao['id_restricao'], $pratoRestricoes)):
                                ?>
                                        <span class="restriction-tag"><?= $restricao['restricao'] ?></span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                            <div class="prato-preco">R$ <?php echo number_format($prato['preco'], 2, ',', '.'); ?>
                                <div class="prato-actions">
                                    <!-- Modificar o botão de edição dentro do foreach -->
                                    <button class="btn-edit" onclick="document.getElementById('editForm<?= $prato['id_prato'] ?>').submit();">
                                        <img src="/assets/svg/edit.svg" alt="Editar">
                                    </button>
                                    <form id="editForm<?= $prato['id_prato'] ?>" action="<?= BASE_URL ?>/partner/menu" method="GET" style="display: none;">
                                        <input type="hidden" name="edit" value="<?= $prato['id_prato'] ?>">
                                    </form>
                                    <button class="btn-delete" data-id="<?php echo $prato['id_prato']; ?>">
                                        <img src="/assets/svg/trash.svg" alt="Excluir">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Modal de Adição -->
        <div class="modal" id="addModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Adicionar Novo Prato</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <form action="<?= BASE_URL ?>/partner/menu" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="addNome">Nome do Prato</label>
                        <input type="text" id="addNome" name="nome" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="addDescricao">Descrição</label>
                        <textarea id="addDescricao" name="descricao" maxlength="255" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="addPreco">Preço</label>
                        <input type="number" id="addPreco" name="preco" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="addImagem">Imagem</label>
                        <input type="file" id="addImagem" name="imagem" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Restrições Alimentares</label>
                        <div class="restrictions-grid">
                            <?php foreach ($restricoes as $restricao): ?>
                                <label class="restriction-item">
                                    <input type="checkbox" name="restricoes[]" value="<?= $restricao['id_restricao'] ?>">
                                    <span><?= $restricao['restricao'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn-save">
                        <span class="button-text">Adicionar Prato</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar Prato</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <form action="<?= BASE_URL ?>/partner/menu" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= isset($pratoEdit) ? $pratoEdit['id_prato'] : '' ?>">
                    <div class="form-group">
                        <label for="editNome">Nome do Prato</label>
                        <input type="text" id="editNome" name="nome" maxlength="50" value="<?= isset($pratoEdit) ? $pratoEdit['nome'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescricao">Descrição</label>
                        <textarea id="editDescricao" name="descricao" maxlength="255" required><?= isset($pratoEdit) ? $pratoEdit['descricao'] : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editPreco">Preço</label>
                        <input type="number" id="editPreco" name="preco" step="0.01" value="<?= isset($pratoEdit) ? $pratoEdit['preco'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editImagem">Imagem</label>
                        <input type="file" id="editImagem" name="imagem" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Restrições Alimentares</label>
                        <div class="restrictions-grid">
                            <?php
                            // Garantir que temos as restrições do prato atual
                            $restricoesAtuais = isset($pratoEdit) ? $restrictionModel->getRestrictionsDish($pratoEdit['id_prato']) : [];
                            foreach ($restricoes as $restricao):
                            ?>
                                <label class="restriction-item">
                                    <input type="checkbox"
                                        name="restricoes[]"
                                        value="<?= $restricao['id_restricao'] ?>"
                                        <?= in_array($restricao['id_restricao'], $restricoesAtuais) ? 'checked' : '' ?>>
                                    <span><?= $restricao['restricao'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn-save">
                        <span class="button-text">Salvar Alterações</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div class="modal" id="deleteModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Confirmar Exclusão</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <p>Tem certeza que deseja excluir este prato?</p>
                <div class="modal-actions">
                    <button class="btn-cancel">Cancelar</button>
                    <button class="btn-confirm-delete">Confirmar</button>
                </div>
            </div>
        </div>
    </main>
</div>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/partner/menu.js"></script>