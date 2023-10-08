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

            // if (response.ok) {
                const responseData = await response.json();

                if (responseData.success) {
                    hideModalAndNavigate('{{ route('admin.balance') }}')
                } else {
                    highlightInvalidFields(form, responseData.fields)
                }
            // }
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
