<x-layout.dashboard>
    <div class="card" style='border-radius:30px;'>
        <div class="card-header bg-dark d-flex justify-content-center" style='border-radius:30px;'>
            <h3 class='text-light'><b>Create/Update Movie</b></h3>
        </div>

        <div class="card-body">
            <form action="{{route('create_movie.submit')}}" id="form" method="post">

                <div class="form-group mt-3">
                    <label>Name of Movie<span style="color:red;">*</span></label>
                    <input type="text" class="form-control form-control-lg borderless-input" autocomplete name="movie" @if(!empty($movie)) value="{{$movie->movie}}" @endif />
                </div>


                <div class="form-group mt-3 ">
                    <label>Date<span style="color:red;">*</span></label>
                    <input type="date" class="form-control form-control-lg borderless-input" id="date" name="date" @if(!empty($movie)) value="{{carbon($movie->showtime)->toDateString()}}" @endif/>
                </div>
                
                <div class="form-group mt-3 ">
                    <label>Time<span style="color:red;">*</span></label>
                    <input type="time" class="form-control form-control-lg borderless-input" id="date" name="time" @if(!empty($movie)) value="{{carbon($movie->showtime)->toTimeString()}}" @endif/>
                </div>

                <div class="form-group mt-3">
                    <label>Ticket Fee</label>
                    <input type="number" step=".01" name='ticket' class="form-control form-control-lg borderless-input" @if(!empty($movie)) value="{{$movie->ticket}}" @endif>
                </div>

                <div class="form-group mt-3">
                    <label>Description<span style="color:red;">*</span></label>
                    <textarea name='description' placeholder="give a brief synopsis of this movie" class="form-control form-control-lg" style="border-radius:10px;" col="2" rows="2">@if(!empty($movie)){{$movie->description}}@endif</textarea>
                </div>

                <div class="rounded border border-dark overflow-scroll mt-3">

                    <div class="d-flex mb-4 preview-upload">
                        @if(!empty($movie))
                        @foreach($movie->images as $image)
                            <div class='me-2 '>
                            <img class='card-img-top' src="{{asset("storage/uploads/movies/{$image}")}}" />
                            </div>
                        @endforeach
                        @endif

                    </div>

                    @empty($movie)
                    <div class="d-flex justify-content-center">
                        <a  href="#" data-targetClass="image" class="btn btn-secondary btn-lg select-photo d-flex justify-content-center">
                            <i class="fas fa-plus-circle fa-sm"></i>
                        </a>

                        <input style="display:none;" id='image' type="file" class="image" data-preview="preview-upload" multiple
                            accept="image/*" name='image[]' />
                    </div>
                    @endempty

                </div>

                <input type="hidden" name='cinema_id' value="{{request()->id}}">

                @if(request()->filled('movie_id'))
                <input type="hidden" name='movie_id' value="{{request()->movie_id}}">
                @endif

                <div class="form-group mt-3">
                    <input class="btn btn-block btn-primary btn-lg w-100" type="submit" value="Submit" />
                </div>

            </form>
        </div>
    </div>
</x-layout.dashboard>
