<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Pareceres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media screen {
            body {
                padding: 20px;
            }
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?= $content ?>
</body>
</html>