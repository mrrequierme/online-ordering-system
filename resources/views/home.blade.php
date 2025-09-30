@extends('layouts.authenticated')

@section('content')
    <div class="container" id="home">
        <div class="row align-items-center h-100">
            <div class="col-md-12 col-lg-6 ps-lg-5 Autoshow">
                <h1 class="display-2 text-center text-lg-start">Putomaya sa Banawa</h1>
                <p class="text-muted featured-description">
                    It is a type of Filipino delicacy made from glutinous rice, usually cooked with coconut milk and a bit
                    of ginger, then often topped with sugar or paired with ripe mangoes and sikwate (hot chocolate drink).
                </p>
                <div class="text-center text-lg-start">
                    <a href="{{ route('login') }}" class="btn btn-primary AutoshowBtn">Buy Now</a>
                </div>

            </div>
            <div class="col-md-12 col-lg-6 d-flex justify-content-center justify-content-lg-start AutoshowImg">
                <img src="{{ asset('images/puto.jpg') }}" alt="puto" class="featured-img rounded">
            </div>
        </div>
    </div>
    <div class="container" id="products">
        <h3 class="text-center py-2">Our Latest Products</h3>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-sm-12 col-md-6 col-lg-3 mx-auto mb-3">
                    <div class="card h-100 showcard">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product_img"
                            alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title text-capitalize">{{ $product->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">₱{{ number_format($product->price, 2) }}</h6>
                            <p class="card-text text-start">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container" id="aboutUs">
        <h3 class="text-center py-3">About Us</h3>
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <p class="text-muted">Puto Maya is a type of Filipino delicacy made from glutinous rice, usually cooked with
                    coconut milk and a bit of ginger, then often topped with sugar or paired with ripe mangoes and sikwate
                    (hot chocolate drink). This sticky rice treat is popular in the Visayas and Mindanao regions, especially
                    as a traditional breakfast or snack. Unlike the more common steamed puto, puto maya is not fluffy but
                    sticky and rich, offering a comforting and satisfying taste. Its combination with sweet mangoes and warm
                    sikwate highlights the unique and beloved flavors of Filipino heritage cuisine.</p>
            </div>
            <div class="col-md-12 col-lg-4 d-flex justify-content-center">
                <img src="{{ asset('images/imgAboutus.jpg') }}" alt="puto mango">
            </div>
        </div>
    </div>
@endsection
