@extends('adminlte::page')

@section('title', 'Categoria')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1>Categoria</h1>
        <button type="button" class="btn btn-success" data-toggle="modal" id="modal" data-target="#exampleModal"
            data-action="add">
            + Adicionar
        </button>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-dismissible bg-teal color-palette">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-check"></i>
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Categorias</h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td> {{ $category->id }} </td>
                                    <td> {{ $category->name }} </td>
                                    <td>
                                        <button value="{{ $category->id }}" class="btn btn-warning btn-edit"
                                            data-toggle="modal" data-target="#exampleModal" data-action="edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button value="{{ $category->id }}" class="btn btn-danger btn-delete"
                                            data-action="delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.category.create-form')

    <script>
        const btnEditDelete = document.querySelectorAll('.btn-edit, .btn-delete');
        const addButton = document.querySelector('button[data-action="add"]');
        const inputName = document.querySelector('input[name="name"]');


        addButton.addEventListener('click', () => {
            inputName.value = '';
        })

        btnEditDelete.forEach((btn) => {
            const action = btn.getAttribute('data-action');
            const idCategory = btn.value;

            btn.addEventListener('click', () => {
                if (action === 'edit') {
                    handleEditButtonClick(idCategory);
                } else if (action === 'delete') {
                    handleDelete(idCategory);
                }
            })
            // if (btn.getAttribute('data-action') === 'edit') {
            //     btn.addEventListener('click', () => handleEditButtonClick(btn));
            // }
        });


        async function handleDelete(idCategory) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`{{ url('category') }}/${idCategory}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })

            if(response.status === 200){
                window.location.href = `{{route('category.index')}}`
            }
        }

        async function handleEditButtonClick(categoryID) {
            // const categoryID = btn.value;
            const categoryData = await getCategoryId(categoryID);
            inputName.value = categoryData.name;
            formCatgory.action = `{{ url('category/update/') }}/${categoryID}`;
        }

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
@stop