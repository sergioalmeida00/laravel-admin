<script>
    // Seletores
    const btnEditDelete = document.querySelector('.rendering-result');
    const addButton = document.querySelector('button[data-action="add"]');
    const inputName = document.querySelector('input[name="name"]');
    const formCategory = document.querySelector('#form-category');

    addButton.addEventListener('click', () => {
        inputName.value = '';
        formCategory.action = `{{ url('category') }}`
    })

    formCategory.addEventListener('submit', handleSubmit)

    // Função para processar o envio do formulário
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

    async function renderCategory() {
        try {
            const response = await fetch("{{ url('category') }}");
            if (!response.ok) {
                throw new Error("Erro ao buscar categorias.");
            }

            const html = await response.text();
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            const table = tempDiv.querySelector('.table');
            btnEditDelete.querySelector('.table').replaceWith(table);

            // Reconfigure os eventos de botões de edição/exclusão
            setupEditDeleteButtons();
        } catch (error) {
            console.error(error);
        }
    }

    // Função para configurar eventos de botões de edição/exclusão
    function setupEditDeleteButtons() {
        const btnEdit = btnEditDelete.querySelectorAll('.btn-edit');
        const btnDelete = btnEditDelete.querySelectorAll('.btn-delete');

        btnEdit.forEach((btn) => {
            btn.addEventListener('click', () => {
                const idCategory = btn.value;
                handleEditButtonClick(idCategory);
            });
        });

        btnDelete.forEach((btn) => {
            btn.addEventListener('click', () => {
                const idCategory = btn.value;
                handleDelete(idCategory);
            });
        });
    }

    // Configurar eventos de botões de edição/exclusão inicialmente
    setupEditDeleteButtons();


    // Função para lidar com a exclusão
    async function handleDelete(idCategory) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(`{{ url('category') }}/${idCategory}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })

        if (response.status === 200) {
            renderCategory();
        }
    }

    // Função para lidar com o botão de edição
    async function handleEditButtonClick(categoryID) {
        const categoryData = await getCategoryId(categoryID);
        inputName.value = categoryData.name;
        formCategory.action = `{{ url('category/update/') }}/${categoryID}`;
    }

    // Função para buscar dados de categoria por ID
    async function getCategoryId(idCategory) {
        try {
            const response = await fetch(`{{ url('category') }}/${idCategory}`);
            if (!response.ok) {
                throw new Error('Erro na requisição');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Erro durante a requisição:', error);
            return {};
        }
    }
</script>
