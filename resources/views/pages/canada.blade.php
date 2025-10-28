@extends('layouts.app')
@section('title', 'CANADA - RCIC Consultants')
@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>(RCIC)</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">(RCIC)</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="category-wrap innerseeker popular-items mt-5">
    <div class="container">
        <div class="main_title">Regulated Canadian Immigration Consultant (RCIC)</div>
        <div class="row">
            <div class="col-lg-12 fadeInLeft">
                <div class="text-center py-5">
                    <h3>RCIC Registered Immigration Consultants for Canada</h3>
                    <p>Find qualified RCIC consultants to help with your Canadian immigration process.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


