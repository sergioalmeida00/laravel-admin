@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1>Saldo</h1>
        <button type="button" class="btn btn-success" data-toggle="modal" id="modal" data-target="#exampleModal">
            + Adicionar
        </button>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($incomeTotal, 2, '.', '') }} </h3>
                    <p>Entradas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-money-bill"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>R$ {{ number_format($expenseTotal, 2, '.', '') }} </h3>
                    <p>Saidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-money-bill"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>R$ {{ number_format($balance, 2, '.', '') }} </h3>
                    <p>Atual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-money-bill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movimentações</h3>
                </div>

                <div class="card-body table-responsive p-0" style="height: 100%;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td> {{ $transaction->id }} </td>
                                    <td> {{ $transaction->name }} </td>
                                    <td> R$ {{ number_format($transaction->amount, 2, '.', '') }} </td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created)->format('d/m/Y') }}
                                    </td>
                                    <td><span
                                            class="badge {{ $transaction->type === 'EXPENSE' ? 'badge-danger' : 'badge-success' }}">
                                            {{ $transaction->type }} </span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Percentual por Categoria</h3>
                </div>

                <div class="card-body">
                    @foreach ($amountByCategory as $categoryData)
                        <strong>
                            {{ $categoryData['categoryName'] }}
                            <small> - R$: {{ number_format($categoryData['total'], 2) }} </small>
                        </strong>

                        <div class="progress mb-2">
                            <div class="progress-bar bg-primary" style="width: {{ $categoryData['percentage'] + 5 }}%">
                                {{ number_format($categoryData['percentage'], 2) }}%
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
    @include('admin.balance.modal.create')

@stop
