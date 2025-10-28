@extends('layouts.app')
@section('title', 'AUSTRALIA - MARA Agents')
@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>AUSTRALIA</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">AUSTRALIA</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="category-wrap innerseeker popular-items mt-5">
    <div class="container">
        <div class="main_title">MARA AGENTS (AUSTRALIA)</div>
        <div class="row">
            <div class="col-lg-12 fadeInLeft">
                <div class="text-center py-5">
                    <h3>MARA Registered Migration Agents for Australia</h3>
                    <p>Find qualified MARA agents to help with your Australian immigration process.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


