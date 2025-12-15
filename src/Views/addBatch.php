<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center">Ajouter un Batch</h3>
            <form method="post" action="/add_batch" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom">Nom du Batch</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="image">Type de Batch</label>
                    <select name="typeBatch" id="typeBatch"></select>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button type="submit" class="btn btn-secondary">Annuler</button>
            </form>
        </div>
    </div>
</div>