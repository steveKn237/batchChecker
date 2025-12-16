<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Batch - Website Batch Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Ajouter un Nouveau Batch</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/batch/create">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du Batch *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type de Batch *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="API">API</option>
                                    <option value="Interne">Interne</option>
                                    <option value="RH">RH</option>
                                    <option value="Production">Production</option>
                                    <option value="Développement">Développement</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="/" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Créer</button>
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
