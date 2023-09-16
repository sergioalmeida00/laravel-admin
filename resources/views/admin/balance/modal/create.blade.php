<style>
    .btn-check {
        position: absolute;
        left: -9999px;
    }

    #success-outlined:checked+label {
        background-color: #28a745;
        /* Cor de fundo para botão de sucesso */
        color: #fff;
        /* Cor do texto */
    }

    #danger-outlined:checked+label {
        background-color: #dc3545;
        /* Cor de fundo para botão de perigo */
        color: #fff;
        /* Cor do texto */
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
                <input type="radio" class="btn-check d-none" name="type" value="INCOME" id="success-outlined"
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

@section('modal-script')
    <script>
        const formSalvar = document.querySelector('#salvar');
        formSalvar.addEventListener('submit', handleSubmit);
        async function handleSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const responseData = await response.json();
                    console.log(responseData)

                    if (responseData.success) {
                        hideModalAndNavigate('{{ route('admin.balance') }}')
                    } else {
                        highlightInvalidFields(form, responseData.fields)
                    }
                }
            } catch (error) {
                console.error('Erro durante a requisição:', error);
            }
        }

        function hideModalAndNavigate(route) {
            $('#exampleModal').modal('hide');
            window.location.href = route;
        }

        function highlightInvalidFields(form, fieldNames) {
            for (const fieldName of fieldNames) {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.classList.add('is-invalid');
                }
            }
        }
    </script>
@endsection
