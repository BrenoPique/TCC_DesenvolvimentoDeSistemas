<?php
require_once __DIR__ . '/core/Core.php';
require_once __DIR__ . '/routes/routes.php';
require_once __DIR__ . '/utils/init.php';

spl_autoload_register(function ($file) {
    if (file_exists(__DIR__ . "/utils/$file.php")) {
        require_once __DIR__ . "/utils/$file.php";
    } else if (file_exists(__DIR__ . "/models/$file.php")) {
        require_once __DIR__ . "/models/$file.php";
    } else if (file_exists(__DIR__ . "/controllers/$file.php")) {
        require_once __DIR__ . "/controllers/$file.php";
    }
});

$core = new Core();
$viewData = $core->run($routes);

// Extract view data
if (is_array($viewData)) {
    extract($viewData);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nutrinfo' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?php echo $cssnav ?>">
    <?php if (isset($cssfooter)) {
        echo '<link rel="stylesheet" href="' . $cssfooter . '">';
    } ?>
    <link rel="stylesheet" href="<?php echo $css ?>">
</head>
<script>
    window.BASE_URL = "<?php echo BASE_URL ?>";
</script>

<body>
    <div id="popup-container"></div>
    <?php
    if (isset($nav)) {
        require_once __DIR__ . "/views/components/$nav.php";
    }
    ?>

    <?= $content ?>

    <?php
    if (isset($footer)) {
        require_once __DIR__ . "/views/components/$footer.php";
    }
    ?>
</body>

</html>