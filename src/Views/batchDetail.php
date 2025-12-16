<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($batch->name) ?> - Website Batch Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <div class="container mt-4">
        <div class="mb-3">
            <a href="/" class="btn btn-secondary">← Retour</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2><?= htmlspecialchars($batch->name) ?> 
                    <span class="badge bg-secondary"><?= htmlspecialchars($batch->type) ?></span>
                </h2>
                <p class="mb-0 text-muted">Créé le: <?= htmlspecialchars($batch->createdAt) ?></p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Sites Web</h3>
            <div>
                <a href="/batch/<?= $batch->id ?>/website/add" class="btn btn-success">+ Ajouter un site</a>
                <a href="/batch/<?= $batch->id ?>/check" class="btn btn-primary">Vérifier tous</a>
            </div>
        </div>

        <?php $websites = $batch->getAllWebsites(); ?>
        <?php if (empty($websites)): ?>
            <div class="alert alert-info">
                Aucun site web dans ce batch. Ajoutez votre premier site!
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th>Statut</th>
                            <th>Dernier check</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($websites as $website): ?>
                            <tr>
                                <td><?= htmlspecialchars($website->id) ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($website->url) ?>" target="_blank" class="text-decoration-none">
                                        <?= htmlspecialchars($website->url) ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                            <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($website->status === 'UP'): ?>
                                        <span class="badge bg-success">UP</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">DOWN</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $website->lastChecked ? htmlspecialchars($website->lastChecked) : 'Jamais' ?></td>
                                <td>
                                    <a href="/batch/<?= $batch->id ?>/website/<?= $website->id ?>/edit" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="/batch/<?= $batch->id ?>/website/<?= $website->id ?>/delete" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce site?')">Supprimer</a>
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
