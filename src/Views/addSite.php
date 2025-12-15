<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center">Ajouter un site au batch</h3>
            <form method="post" action="/addSite" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="idSite" value="<?= htmlspecialchars($idSite) ?>">
                    <label for="nom">Nom du site</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="image">Site</label>
                    <input type="file" class="form-control-file" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter le site</button>
            </form>
        </div>
    </div>
</div>