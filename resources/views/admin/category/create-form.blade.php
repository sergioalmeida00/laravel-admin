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

@section('modal-script')
    <script>
        const formCategory = document.querySelector('#form-category');
        formCategory.addEventListener('submit', handleSubmit)

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

                    if (responseData.success) {
                        hideModalAndNavigate('{{ route('category.index') }}')
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
            renderCategory()
            // window.location.href = route;
        }

        function highlightInvalidFields(form, fieldNames) {
            for (const fieldName of fieldNames) {
                const field = form.querySelector(`[name=${fieldName}]`);

                if (field) {
                    field.classList.add('is-invalid')
                }
            }
        }
    </script>
@endsection
