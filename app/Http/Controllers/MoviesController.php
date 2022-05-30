<?php

namespace App\Http\Controllers;

use App\Interfaces\MovieRepositoryInterface;

class MoviesController extends Controller
{
    private MovieRepositoryInterface $movieRepositoryInterface;

    public function __construct(MovieRepositoryInterface $movieRepositoryInterface) 
    {
        $this->movieRepositoryInterface = $movieRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function getAllMovies()
    {
        $movies = $this->movieRepositoryInterface->getAllMovies();

        //dd($movies[0]->cinema->user);

        return view("welcome", ["movies"=>$movies]);
    }

   
}
