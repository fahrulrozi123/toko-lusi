@extends('layouts.layout')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/linericon/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/nouislider/nouislider.min.css')}}">
<style>
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 50px;
    }
</style>
@endsection

@section('title')
    Order
@endsection

@section('main')
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Payment Confirmation</h1><br>
                <p class="display-5">Silahkan Lakukan Pembayaran Lewat No Rekening Berikut</p>
            </div>
        </div>
    </div>
</section>
<div class="container-fluid row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row  mb-2 text-center">
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3 " style="max-width: 18rem;">
                        <div class="card-header">BCA</div>
                            <div class="card-body">
                                <h5 class="card-title">11873724</h5>
                                <p class="card-text">Atas Nama Ega Yanuar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3 " style="max-width: 18rem;">
                        <div class="card-header">BNI</div>
                            <div class="card-body">
                                <h5 class="card-title">11873724</h5>
                                <p class="card-text">Atas Nama Ega Yanuar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3 " style="max-width: 18rem;">
                        <div class="card-header">BRI</div>
                            <div class="card-body">
                                <h5 class="card-title">11873724</h5>
                                <p class="card-text">Atas Nama Ega Yanuar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3 " style="max-width: 18rem;">
                        <div class="card-header">Mandiri</div>
                            <div class="card-body">
                                <h5 class="card-title">11873724</h5>
                                <p class="card-text">Atas Nama Ega Yanuar</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col-md-12 text-center">
                        Jumlah Transfer Sebesar <b>Rp.{{number_format($order->cost)}}</b> Ke No Rekening Di Atas
                    </div>
                </div>
                <hr>
                <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <form action="/costumer/payment/{{$order->invoice}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value=" {{$order->id}} " required>
                        <input type="hidden" name="name" value=" {{$order->customer->name}} " required>
                        <input type="hidden" name="transfer_to" value="bank" required>
                        <input type="hidden" name="amount" value=" {{$order->cost}} " required>
                        <input type="file" name="proof" required>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
