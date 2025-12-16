<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Site - Website Batch Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Ajouter un Site au Batch: <?= htmlspecialchars($batch->name) ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/batch/<?= $batch->id ?>/website/add">
                            <div class="mb-3">
                                <label for="url" class="form-label">URL du Site Web *</label>
                                <input type="url" class="form-control" id="url" name="url" 
                                       placeholder="https://example.com" required>
                                <div class="form-text">L'URL doit inclure le protocole (http:// ou https://)</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="/batch/<?= $batch->id ?>" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
