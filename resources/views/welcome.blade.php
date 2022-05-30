<x-layout.home>
    
    @if($movies->total() > 0)

    @foreach($movies as $movie)

    <div class='card' style='border-radius:30px;'>
        <h5 class='card-header d-flex justify-content-center bg-dark text-light' style='border-radius:30px;'>
            {{ucwords($movie->cinema->user->cinema_name)}}
        </h5>

        <div class='list-group list-group-flush'>

            <div class='list-group-item d-flex w-100 justify-content-between'>
                <p class='mb-1 fw-bold'>Movie Name:</p>
                <p>{{$movie->movie}}</p>
            </div>

            <div class='list-group-item d-flex w-100 justify-content-between'>
                <p class='mb-1 fw-bold'>Gate fee:</p>
                <p>NGN{{number_format($movie->ticket, 2)}}</p>
            </div>

            <div class='list-group-item d-flex w-100 justify-content-between'>
                <p class='mb-1 fw-bold'>Date:</p>
                <p>{{carbon($movie->showtime)->toFormattedDateString()}}</p>
            </div>

            <div class='list-group-item d-flex w-100 justify-content-between'>
                <p class='mb-1 fw-bold'>Address:</p>
                <p>NGN{{$movie->cinema->address}}</p>
            </div>

            <a class="text-decoration-none bg-dark text-light list-group-item list-group-item-action"
                data-bs-toggle="collapse" href="#movie_{{$movie->movie_id}}" role="button" aria-expanded="false"
                aria-controls="collapseExample">Description </a>

            <div class="collapse" id="movie_{{$movie->movie_id}}">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-4">
                            @foreach($movie->images as $image)
                            <div class='me-2 '>
                            <img class='card-img-top' src="{{asset("storage/uploads/movies/{$image}")}}" />
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center">
                        {{$movie->description}}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <hr />
    @endforeach

    {{ $movies->links() }}

    @else

    <div class='card'>
        <h5 class='card-header d-flex justify-content-center'>
            Welcome to {{config("app.name")}}
        </h5>
        <div class='card-body d-flex justify-content-center'>
            <p>Your Cinema Of Quality</p>
        </div>
    </div>

    @endif



</x-layout.home>