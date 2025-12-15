<form action="" method="post" class="row g-2">
    <div class="col-md-6">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="col-md-6">
        <label for="typeBatch" class="form-label">Type de Batch</label>
        <select class="form-select" id="typeBatch" name="typeBatch" required>
            <option value="">Sélectionner un type</option>
            <option value="API">API</option>
            <option value="RH">RH</option>
            <option value="INTERNE">INTERNE</option>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <button type="reset" class="btn btn-secondary">Annuler</button>
    </div>
</form>