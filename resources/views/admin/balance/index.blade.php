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
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box callout callout-danger">
                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Saidas</span>
                    <span class="info-box-number">R$ {{ number_format($expenseTotal, 2, '.', '') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3 ">
            <div class="info-box callout callout-success">
                <span class="info-box-icon "><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atual</span>
                    <span class="info-box-number">R$ {{ number_format($balance, 2, '.', '') }}</span>
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
                                    <h5><strong>Descrição:</strong> {{ $transaction->name }}</h5>
                                    <p><strong>Data:</strong>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created)->format('d/m/Y') }}
                                    </p>
                                    <strong>Valor: R$ {{ number_format($transaction->amount, 2, '.', '') }}</strong>
                                    <p>
                                        <small
                                            class="badge badge-{{ $transaction->type === 'EXPENSE' ? 'danger' : 'success' }}">
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
                    <h3 class="card-title">Percentual por Categoria</h3>
                </div>

                <div class="card-body">
                    @foreach ($amountByCategory as $categoryData)
                        <div class="skill-box">
                            <span class="title"> {{ $categoryData['categoryName'] }} - R$: {{ number_format($categoryData['total'], 2) }}</span>

                            <div class="skill-bar">
                                <span class="skill-per" style="width:{{ $categoryData['percentage'] + 5}}%">
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
    <style>
        :root{
            --primary-shape:#734bd1;
        }
        .callout {
            transition: transform 0.2s;
        }

        .callout:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* STYLE PROGRESS BAR */
        .skill-box {
            width: 100%;
            margin-bottom: 1.7rem;
        }

        .skill-box .title {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .skill-box .skill-bar {
            height: 8px;
            border-radius: 6px;
            width: 100%;
            background: rgba(0, 0, 0, 0.1);
        }

        .skill-bar .skill-per {
            position: relative;
            display: block;
            height: 100%;
            border-radius: 6px;
            background: var(--primary-shape);
            animation: progress 0.4s ease-out forwards;
            opacity: 0;
        }


        @keyframes progress {
            0% {
                width: 0;
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .skill-per .tooltip-per {
            position: absolute;
            right: -22px;
            top: 14px;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            background: var(--primary-shape);
            z-index: 1;
        }

        .tooltip-per::before {
            content: '';
            position: absolute;
            top: -2px;
            height: 10px;
            width: 10px;
            z-index: -1;
            background: var(--primary-shape);
            transform: translateX(20%) rotate(45deg);
        }
    </style>
@stop
