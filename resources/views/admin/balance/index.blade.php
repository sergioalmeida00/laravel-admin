@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1>Saldo</h1>
        <button type="button" class="btn btn-success" data-toggle="modal" id="modal" data-target="#exampleModal">
            <strong><i class="fas fa-plus"></i> Adicionar</strong>
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
                    <h3 class="info-box-number">R$ {{ number_format($incomeTotal, 2, '.', '') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box callout callout-danger">
                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Saidas</span>
                    <h3 class="info-box-number">R$ {{ number_format($expenseTotal, 2, '.', '') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3 ">
            <div class="info-box callout callout-success">
                <span class="info-box-icon "><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atual</span>
                    <h3 class="info-box-number">R$ {{ number_format($balance, 2, '.', '') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="POST" action="{{ route('admin.filter') }}">
                            @csrf
                            <div class="row">
                                <div class="col-5 col-md-5">
                                    <input type="date" name="date_inicio" class="form-control">
                                </div>
                                <div class="col-5 col-md-5">
                                    <input type="date" name="date_fim" class="form-control">
                                </div>
                                <div class="col-2 col-md-2">
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
                                    <p>
                                        <strong>Descrição:</strong> {{ $transaction->name }} -
                                        <strong>Data:</strong>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created)->format('d/m/Y') }}
                                    </p>
                                    <strong>Valor: R$ {{ number_format($transaction->amount, 2, '.', '') }}</strong>
                                    <p>
                                        <small
                                            class="badge badge-small-{{ $transaction->type === 'EXPENSE' ? 'danger' : 'success' }}">
                                            {!! $transaction->type === 'EXPENSE'
                                                ? '<i class="fas fa-minus"></i> Saida'
                                                : '<i class="fas fa-plus"></i> Entrada' !!}
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
                    <h3 class="card-title">Distribuição de Gastos por Categoria</h3>
                </div>

                <div class="card-body">
                    @foreach ($amountByCategory as $categoryData)
                        <div class="skill-box">
                            <span class="title"> {{ $categoryData['categoryName'] }} - R$:
                                {{ number_format($categoryData['total'], 2) }}</span>

                            <div class="skill-bar">
                                <span class="skill-per" style="width:{{ $categoryData['percentage'] + 5 }}%">
                                    <span class="tooltip-per">{{ number_format($categoryData['percentage'], 2) }}%</span>
                                </span>
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
    <link rel="stylesheet"href="{{ asset('css/custom-styles.css') }}">
@stop
