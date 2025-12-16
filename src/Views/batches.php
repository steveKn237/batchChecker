<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Batch Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Liste des Batches</h2>
            <a href="/batch/create" class="btn btn-primary">+ Nouveau batch</a>
        </div>

        <?php if (empty($batches)): ?>
            <div class="alert alert-info">
                Aucun batch trouvé. Créez votre premier batch pour commencer!
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Créé le</th>
                            <th>Sites</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($batches as $batch): ?>
                            <tr>
                                <td><?= htmlspecialchars($batch->id) ?></td>
                                <td><?= htmlspecialchars($batch->name) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($batch->type) ?></span></td>
                                <td><?= htmlspecialchars($batch->createdAt) ?></td>
                                <td><?= count($batch->getAllWebsites()) ?></td>
                                <td>
                                    <a href="/batch/<?= $batch->id ?>" class="btn btn-sm btn-info">Ouvrir</a>
                                    <a href="/batch/<?= $batch->id ?>/delete" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce batch?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
