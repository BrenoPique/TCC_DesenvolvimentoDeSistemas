<?php
// Verifica se o título da página foi definido
$title = $title ?? 'Nutrinfo';

// Inclui o cabeçalho HTML comum
include_once __DIR__ . '/head.php';

// Inclui a navegação
include_once __DIR__ . '/nav.php';
?>
<body>
    
    <!-- Conteúdo específico da página -->
    <main>
        <?php 
    if (isset($content_path) && file_exists($content_path)) {
        include_once $content_path;
    } else {
        echo '<p style="font-size: 4rem;display: flex;align-items:center;justify-content:center;height: 50vh">Página não encontrada</p>';
    }
    ?>
</main>

<?php
// Inclui o rodapé
include_once __DIR__ . '/footer.php';
?>
</body>
</html>