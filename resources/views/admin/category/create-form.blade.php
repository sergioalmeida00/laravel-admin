@extends('layouts.modal.modal')
@section('modal-id', 'exampleModal')
@section('modal-title')
    Cadastro de Categoria
@endsection

@section('modal-content')
    <form action="{{ route('category.store') }}" method="POST" id="form-category">

        <div class="form-group">
            <input class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" name="name"
                placeholder="Descrição da Categoria">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
@endsection

