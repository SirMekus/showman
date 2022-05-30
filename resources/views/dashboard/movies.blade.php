<x-layout.dashboard>
    <a href="{{route('create_movie', ['id'=>request()->id])}}"><i style="float:right;"
            class="fas fa-pen bg-dark text-light btn fa-lg"></i></a>


    @if($movies->total() > 0)

    @foreach($movies as $movie)

    <div class='card' style='border-radius:30px;'>
        <h5 class='card-header d-flex justify-content-center bg-dark text-light' style='border-radius:30px;'>
            {{ucwords($movie->movie)}}
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

            @php
                $random = Str::random(5);
            @endphp

            <a class="text-decoration-none bg-dark text-light list-group-item list-group-item-action"
                data-bs-toggle="collapse" href="#movie_{{$random}}" role="button" aria-expanded="false"
                aria-controls="collapseExample">Description </a>

            <div class="collapse" id="movie_{{$random}}">
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


            <div class='card-footer'>
                <div class='btn-group d-flex justify-content-center'>
                    <a class='nextPageViaAjaxOnly btn btn-sm btn-primary' href="{{route('create_movie', ['id'=>request()->id, 'movie_id'=>$movie->movie_id])}}">Update</a>
                    <a class='btn btn-danger del' href='#'>Delete</a>
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
            Nothing Yet!
        </h5>
        <div class='card-body d-flex justify-content-center'>
            <p>Let's create our first movie for this cinema <a
                    href="{{route('create_movie', ['id'=>request()->id])}}">here</a></p>
        </div>
    </div>

    @endif



</x-layout.dashboard>