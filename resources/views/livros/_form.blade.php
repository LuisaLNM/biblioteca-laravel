<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Título *</label>
        <input name="titulo" class="form-control" value="{{ old('titulo', $livro->titulo ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Autor *</label>
        <input name="autor" class="form-control" value="{{ old('autor', $livro->autor ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Editora</label>
        <input name="editora" class="form-control" value="{{ old('editora', $livro->editora ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Ano de publicação</label>
        <input type="number" name="ano_publicacao" class="form-control"
               value="{{ old('ano_publicacao', $livro->ano_publicacao ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">ISBN *</label>
        <input name="isbn" class="form-control" value="{{ old('isbn', $livro->isbn ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Quantidade *</label>
        <input type="number" name="quantidade" min="0" class="form-control"
               value="{{ old('quantidade', $livro->quantidade ?? 0) }}">
    </div>
</div>