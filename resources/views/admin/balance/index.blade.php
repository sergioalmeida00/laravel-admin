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
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box callout callout-success">
                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Entradas</span>
                    <span class="info-box-number">R$ {{ number_format($incomeTotal, 2, '.', '') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box callout callout-danger">
                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Saidas</span>
                    <span class="info-box-number">R$ {{ number_format($expenseTotal, 2, '.', '') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3 ">
            <div class="info-box callout callout-success">
                <span class="info-box-icon "><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atual</span>
                    <span class="info-box-number">R$ {{ number_format($balance, 2, '.', '') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- <h3 class="card-title">Movimentações</h3> --}}
                        <form method="POST" action="{{ route('admin.filter') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <input type="date" name="date_inicio" class="form-control">
                                </div>
                                <div class="col-12 col-md-5">
                                    <input type="date" name="date_fim" class="form-control">
                                </div>
                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body table-responsive p-3">
                    <div class="row">
                        @foreach ($transactions as $transaction)
                            <div class="col-lg-6 col-md-12">
                                <div
                                    class="callout {{ $transaction->type === 'EXPENSE' ? 'callout-danger' : 'callout-success' }}">
                                    <h5><strong>Descrição:</strong> {{ $transaction->name }}</h5>
                                    <p><strong>Data:</strong>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created)->format('d/m/Y') }}
                                    </p>
                                    <strong>Valor: R$ {{ number_format($transaction->amount, 2, '.', '') }}</strong>
                                    <p>
                                        <small class="badge badge-{{ $transaction->type === 'EXPENSE' ? 'danger' : 'success' }}">
                                            {!! $transaction->type === 'EXPENSE' ? '<i class="fas fa-minus"></i> Saida' : '<i class="fas fa-plus"></i> Entrada' !!}
                                        </small>
                                    </p>

                                </div>
                            </div>
                        @endforeach
                    </div>
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
@section('css')
    <style>
        .callout {
            transition: transform 0.2s;
        }

        .callout:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@stop
