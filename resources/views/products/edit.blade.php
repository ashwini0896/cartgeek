<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel CRUD With Multiple Image Upload</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- Font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>

    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-lg-3">
                @if (count($products->images)>0)
                <p>Images:</p>
                @foreach ($products->images as $img)
                <form action="/deleteimage/{{ $img->id }}" method="post">
                    <button class="btn text-danger">X</button>
                    @csrf
                    @method('delete')
                </form>
                <img src="/images/{{ $img->image }}" class="img-responsive" style="max-height: 100px; max-width: 100px;" alt="" srcset="">
                @endforeach
                @endif
            </div>
            <div class="col-lg-6">
                <h3 class="text-center text-danger"><b>Edit Product</b> </h3>
                <div class="form-group">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <form action="{{route('products.update',$products->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <input type="text" name="product_name" class="form-control m-2" placeholder="Product Name" value="{{ $products->product_name }}">
                        <input type="text" name="product_price" class="form-control m-2" placeholder="Product Price" value="{{ $products->product_price }}">
                        <textarea name="product_description" cols="20" rows="4" class="form-control m-2"
                            placeholder="Product Description">{{ $products->product_description }}</textarea>

                        <label class="m-2">Product Images</label>
                        <input type="file" id="input-file-now-custom-3" class="form-control m-2" name="images[]" multiple>

                        <button type="submit" class="btn btn-danger mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>



</body>

</html>