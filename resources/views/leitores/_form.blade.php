<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nome completo *</label>
        <input name="nome" class="form-control" value="{{ old('nome', $leitor->nome ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">CPF *</label>
        <input name="cpf" class="form-control" placeholder="000.000.000-00" maxlength="14"
               value="{{ old('cpf', $leitor->cpf ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Telefone *</label>
        <input name="telefone" class="form-control" placeholder="(38) 99999-9999"
               value="{{ old('telefone', $leitor->telefone ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">E-mail *</label>
        <input name="email" class="form-control" value="{{ old('email', $leitor->email ?? '') }}">
    </div>
</div>