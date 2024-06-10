<!DOCTYPE html>
<html>

<head>
    @include('admin.css')

    <style type="text/css">
        .div_deg {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .table_deg {
            border: 2px solid greenyellow;
        }

        th {
            background-color: skyblue;
            color: white;
            font-size: 19px;
            font-weight: bold;
            padding: 15px;
        }

        td {
            border: 1px solid skyblue;
            text-align: center;
            color: white;
        }
        input[type="search"] {
            width: 500px;
            height: 60px;
            margin-left: 50px;
        }
    </style>
</head>

<body>
    @include('admin.header')


    @include('admin.sidebar')
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">

                <form action="{{url('product_search')}}" method="get">
                  @csrf
                    <input type="search" name="search">
                    <input type="submit" class="btn btn-secondary" value="Search">
                </form>

                <div class="div_deg">
                    <table class="table_deg">
                        <tr>
                            <th>Product Title</th>

                            <th>Description</th>

                            <th>Category</th>

                            <th>Price</th>

                            <th>Quantity</th>

                            <th>Image</th>

                            <th>Edit</th>

                            <th>Delete</th>
                        </tr>
                        @foreach ($product as $products)
                            <tr>
                                <td>{{$products->title}}</td>

                                <td>{!!Str::limit($products->description,50)!!}</td>

                                <td>{{$products->category}}</td>

                                <td>{{$products->price}}</td>

                                <td>{{$products->quantity}}</td>

                                <td>
                                    <img height="120" width="120" src="products/{{$products->image}}">
                                </td>

                                <td>
                                    <a class="btn btn-success" href="{{url('update_product',$products->id)}}">Edit</a>
                                </td>

                                <td>
                                    <a class="btn btn-danger" onclick="confirmation(event)" href="{{url('delete_product',$products->id)}}">Delete</a>
                                </td>

                            </tr>
                        @endforeach
                    </table>


                </div>
                <div class="div_deg">
                    {{$product->onEachSIde(1)->links()}}
                </div>

            </div>
        </div>
    </div>
    <!-- JavaScript files-->
    @include('admin.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        function confirmation(ev) {
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');

            console.log(urlToRedirect);

            swal({
                    title: "Are you Sure to Delete This",
                    text: "This delete will be parmanent",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })

                .then((willCancel) => {
                    if (willCancel) {
                        window.location.href = urlToRedirect;
                    }

                });
        }
    </script>
</body>

</html>
