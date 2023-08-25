@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
    <h1>Saldo</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" id="modal" data-target="#exampleModal">
        Launch demo modal
    </button>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($amount, 2, '.', '') }} </h3>
                    <p>Atual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-money-bill"></i>
                </div>
            </div>
        </div>
    </div>

    @include('admin.balance.modal.create')

@stop
