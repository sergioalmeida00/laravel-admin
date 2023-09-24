@extends('adminlte::page')

@section('title', 'Categoria')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1>Categoria</h1>
        <button type="button" class="btn btn-success" data-toggle="modal" id="modal" data-target="#exampleModal"
            data-action="add">
            <strong><i class="fas fa-plus"></i> Adicionar</strong>
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
    <div class="row rendering-result">
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

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
@stop

@section('js')
    @include('admin.category.scripts')
@stop
