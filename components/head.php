<?php
// Set default values if not provided
$title = $title ?? "Japura Events";
$description = $description ?? "Your central hub for all events at the University of Sri Jayewardenepura. Discover, engage, and be part of the vibrant campus life.";
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= htmlspecialchars($title) ?></title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="./global.css" type="text/css" />
    

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- SEO meta -->
    <meta name="description" content="<?= htmlspecialchars($description) ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Canonical URL -->
    <?php
    if (!empty($canonical)) {
        echo '<link rel="canonical" href="' . htmlspecialchars($canonical) . '" />';
    }
    ?>
</head>
