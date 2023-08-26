<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                        <input class="form-control form-control-sm @error('name') is-invalid @enderror" type="text"
                            name="name" placeholder="Descrição">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="number" name="amount" placeholder="Valor">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="date" name="date" placeholder="Data">
                    </div>
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="type">
                            <option selected disabled value="">Selecione a Transação</option>
                            <option value="EXPENSE">Credito</option>
                            <option value="INCOME">Debito</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
