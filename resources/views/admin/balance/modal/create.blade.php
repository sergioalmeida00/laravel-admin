<style>
    .btn-check {
        position: absolute;
        left: -9999px;
    }

    #success-outlined:checked+label {
        background-color: #00b37e;
        color: #fff;
    }

    #danger-outlined:checked+label {
        background-color: #ce4a4a;
        color: #fff;
    }

</style>

@extends('layouts.modal.modal')
@section('modal-id', 'exampleModal')

@section('modal-title')
    Cadastro de Tranações
@endsection

@section('modal-content')
    <form method="POST" action="{{ route('admin.store') }}" id="salvar">
        {{ csrf_field() }}
        <div class="form-group">
            <select class="form-control form-control-sm" name="category_id">
                <option selected disabled value="">Selecione a Categoria</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}"> {{ $categorie->name }} </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" name="name"
                placeholder="Descrição">
        </div>
        <div class="form-group">
            <input class="form-control form-control-sm" type="number" name="amount" placeholder="Valor">
        </div>
        <div class="form-group">
            <input class="form-control form-control-sm" type="date" name="date" placeholder="Data">
        </div>
        <div class="form-group d-flex">
            <div class="flex-fill mx-1">
                <input type="radio" class="btn-check d-none success-card" name="type" value="INCOME" id="success-outlined"
                    autocomplete="off">
                <label class="btn btn-outline-success w-100" for="success-outlined">Credito</label>
            </div>

            <div class="flex-fill mx-1">
                <input type="radio" class="btn-check d-none" name="type" value="EXPENSE" id="danger-outlined"
                    autocomplete="off">
                <label class="btn btn-outline-danger w-100" for="danger-outlined">Debito</label>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
@endsection

@section('js')
    @include('admin.balance.scripts')
@stop

