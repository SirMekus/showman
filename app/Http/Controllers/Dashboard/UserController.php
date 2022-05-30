<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Catalogue;
use App\Interfaces\CinemaRepositoryInterface;
use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Custom\Filesystem\UploadFiles;

class UserController extends Controller
{
    private CinemaRepositoryInterface $cinemaRepositoryInterface;
    private MovieRepositoryInterface $movieRepositoryInterface;

    public function __construct(CinemaRepositoryInterface $cinemaRepositoryInterface, MovieRepositoryInterface $movieRepositoryInterface) 
    {
        $this->cinemaRepositoryInterface = $cinemaRepositoryInterface;
        $this->movieRepositoryInterface = $movieRepositoryInterface;
    }

    public function myCinemas()
    {
        $branch = $this->cinemaRepositoryInterface->getAllCinemas();

        return view("dashboard.branch", ["branch"=>$branch->cinema]);
    }

    public function moviesInCinema()
    {
        $movies = $this->movieRepositoryInterface->getMoviesInCinema(request()->id);

        return view("dashboard.movies", ["movies"=>$movies]);
    }

    public function createMovie()
    {
        $movie = null;

        if(request()->filled("movie_id"))
        {
            $movie = $this->movieRepositoryInterface->getMovieById(request()->movie_id);
        }

        return view("dashboard.new_movie", ['movie'=>$movie]);
    }

    public function createMovieSubmit(UploadFiles $upload)
    {
        $message =  [
            'image.max' => 'You can only upload a maximum number of 5 images',
            'image.required_if' => 'Please add at least one image file of this movie',
        ];
        request()->validate([
            'movie' => ['bail', 'required', 'string'],
            'date' => ['bail', 'required', 'date_format:Y-m-d'],
            'time' => ['bail', 'required', 'string'],
            'ticket' => ['bail', 'required', 'numeric'],
            'description' => ['bail', 'required', 'string'],
            'movie_id' => 'sometimes|nullable|string|exists:App\Models\Catalogue',
            'cinema_id' => 'required|string|exists:App\Models\Cinema,id',
            'image' => ['array', 'max:5', Rule::requiredIf(function ()  {
                return !request()->filled('movie_id');
            })],
            ], $message);//'nullable', 'sometimes', 
            
            $movie = Catalogue::where('movie_id', request()->movie_id)->where('user_id', request()->user()->id)->first();
            //dd(!request()->hasFile('image'));
            if((empty($movie) or empty($movie->movie)) and (!request()->hasFile('image')))
            {
                return response("Please add at least one image file of this movie", 422);
                //abort(422, "Please add at least one image file of this movie");
            }

            if((request()->hasFile('image')) and (request()->filled('movie_id')))
            {
                if(!empty($movie))
                {
                    $upload->fileDelete($movie->images);
                }
                
            }

            $stock = $upload->upload('image', true);

            //dd($stock);
            
            $data = ['user_id' => request()->user()->id, 'movie_id' => request()->movie_id ?? Str::random(5), 'cinema_id' => request()->cinema_id, 'movie' => request()->movie, 'ticket' => request()->ticket, 'description' => request()->description, 'showtime' => request()->date." ".request()->time];

            if(!empty($stock))
            {
                $data['images'] = $stock;
            }
    
            $this->movieRepositoryInterface->updateOrCreate($data);
    
            $verb = request()->filled('movie_id') ? 'updated' : 'created';
            
            return response("Movie was $verb successfully");
    }
}
