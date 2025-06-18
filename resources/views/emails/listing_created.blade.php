<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>Listing Created | {{ config('app.name') }}</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9ff;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    .container {
      width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px 40px;
    }
    .header {
      background-color: #67bbe2de;
      padding: 20px;
      border-radius: 20px 20px 0 0;
      color: #fff;
    }
    .product-image {
      max-width: 100%;
      height: auto;
      margin: 20px 0;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>🎉 Listing Created Successfully!</h1>
    </div>

    <p>Hi {{ $listing->user->first_name ?? 'User' }},</p>

    <p>Your product listing has been successfully created on <strong>{{ config('app.name') }}</strong>.</p>

    <h3>📦 Product Details:</h3>
    <p><strong>Name:</strong> {{ $listing->name }}</p>
    <p><strong>Price:</strong> ${{ number_format($listing->price, 2) }}</p>

    @if($listing->images && $listing->images->first())
      <img class="product-image" src="{{ Storage::url($listing->images->first()->image_path) }}" alt="{{ $listing->name }}">
    @else
      <p><em>[Product Photo]</em></p>
    @endif

    <p>Thank you for using {{ config('app.name') }}.</p>

    <footer style="margin-top: 30px; font-size: 12px; color: #999;">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>
  </div>

</body>
</html>
