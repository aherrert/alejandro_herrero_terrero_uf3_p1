<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;

class FilmController extends Controller
{

    /**
     * Read films from storage
     */
    public static function readFilms(): array
    {
        $films_json = Storage::json('/public/films.json');
        $films_bbdd = Film::select('name', 'year', 'genre', 'country', 'duration', 'img_url')->get();
        $actorsArray = json_decode(json_encode($films_bbdd), true);
        $films = array_merge($films_json, $actorsArray);
        return $films;
    }
    /**
     * List films older than input year 
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listOldFilms($year = null)
    {
        $old_films = [];
        if (is_null($year))
            $year = 2000;

        $title = "Listado de Pelis Antiguas (Antes de $year)";
        $films = FilmController::readFilms();

        foreach ($films as $film) {
            //foreach ($this->datasource as $film) {
            if ($film['year'] < $year)
                $old_films[] = $film;
        }
        return view('films.list', ["films" => $old_films, "title" => $title]);
    }
    /**
     * List films younger than input year
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listNewFilms($year = null)
    {
        $new_films = [];
        if (is_null($year))
            $year = 2000;

        $title = "Listado de Pelis Nuevas (Después de $year)";
        $films = FilmController::readFilms();

        foreach ($films as $film) {
            if ($film['year'] >= $year)
                $new_films[] = $film;
        }
        return view('films.list', ["films" => $new_films, "title" => $title]);
    }
    /**
     * Lista TODAS las películas o filtra x año o categoría.
     */
    public function listFilms($year = null, $genre = null)
    {
        $films_filtered = [];

        $title = "Listado de todas las pelis";
        $films = FilmController::readFilms();

        //if year and genre are null
        if (is_null($year) && is_null($genre))
            return view('films.list', ["films" => $films, "title" => $title]);

        //list based on year or genre informed
        foreach ($films as $film) {
            if ((!is_null($year) && is_null($genre)) && $film['year'] == $year) {
                $title = "Listado de todas las pelis filtrado x año";
                $films_filtered[] = $film;
            } else if ((is_null($year) && !is_null($genre)) && strtolower($film['genre']) == strtolower($genre)) {
                $title = "Listado de todas las pelis filtrado x categoria";
                $films_filtered[] = $film;
            } else if (!is_null($year) && !is_null($genre) && strtolower($film['genre']) == strtolower($genre) && $film['year'] == $year) {
                $title = "Listado de todas las pelis filtrado x categoria y año";
                $films_filtered[] = $film;
            }
        }
        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }

    public function listByYear($year)
    {
        $films_filtered = [];

        $title = "Listado de todas las pelis por año";
        $films = FilmController::readFilms();

        if (is_null($year))
            return view('films.list', ["films" => $films, "title" => $title]);
        //list based on year or genre informed
        foreach ($films as $film) {
            if (!is_null($year) && $film['year'] == $year) {
                $title = "Listado de todas las pelis filtrado x año";
                $films_filtered[] = $film;
            }
        }

        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }

    public function listByGenre($genre = null)
    {
        $films_filtered = [];

        $title = "Listado de todas las pelis";
        $films = FilmController::readFilms();

        //if year and genre are null
        if (is_null($genre))
            return view('films.list', ["films" => $films, "title" => $title]);

        //list based on year or genre informed
        foreach ($films as $film) {
            if ((!is_null($genre)) && strtolower($film['genre']) == strtolower($genre)) {
                $title = "Listado de todas las pelis filtrado x categoria";
                $films_filtered[] = $film;
            }
        }
        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }

    public function sortByYear()
    {
        $title = "Listado ordenado por año";

        $films = FilmController::readFilms();

        // Sort films by year using usort
        usort($films, function ($a, $b) {
            return $a['year'] - $b['year'];
        });

        return view('films.list', ["films" => $films, "title" => $title]);
    }

    public function countFilms()
    {

        $title = "Number of movies";
        $films = FilmController::readFilms();

        $films = count($films);


        return view('films.count', ["films" => $films, "title" => $title]);
    }

    public function getFilmsFromJson()
    {
        $path = storage_path('app/public/films.json');

        if (!file_exists($path)) {
            return []; // Return an empty array or handle the missing file case as needed
        }

        $content = file_get_contents($path);
        $films = json_decode($content, true);

        return $films ?: []; // Return decoded films or an empty array if decoding fails
    }

    private function saveFilmsToJson($films)
    {
        $jsonContent = json_encode($films, JSON_PRETTY_PRINT);
        $filePath = storage_path('app/public/films.json');

        // Write the JSON content to the file
        if ($filePath !== false) {
            Storage::disk('public')->put('films.json', $jsonContent);
        }
    }

    public function checkAndAddFilm(Request $request)
    {
        $filmName = $request->input('name');

        if ($this->isFilm($filmName)) {
            return redirect('/')->with('error', 'Película ya existente.');
        } else {
            // Add the film if it doesn't exist
            $this->addFilm($request);
            return $this->listFilteredFilms();
        }
    }

    public function isFilm($filmName)
    {
        $films = $this->getFilmsFromJson();
        foreach ($films as $film) {
            if ($film['name'] === $filmName) {
                return true;
            }
        }
        return false;
    }

    private function addFilm(Request $request)
    {
        $title = "Listado de películas";
        $filmName = $request->input('name');

        // Verificar si la película ya existe en la base de datos
        $filmExistInDB = DB::table('films')->where('name', $filmName)->exists();

        // Verificar si la película ya existe en el archivo JSON
        $films = $this->getFilmsFromJson();
        $filmExistInJSON = collect($films)->contains('name', $filmName);

        if ($filmExistInDB || $filmExistInJSON) {
            return view('welcome', ["Error" => "Lo siento, pero esta película ya existe"]);
        } else {
            $newFilm = Film::create([
                'name' => $filmName,
                'year' => $request->input('year'),
                'genre' => $request->input('genre'),
                'country' => $request->input('country'),
                'duration' => $request->input('duration'),
                'img_url' => $request->input('url_image'), // renaming 'url_image' to 'img_url'
            ]);

            // Insertar en la base de datos SQL
            // DB::table('films')->insert($newFilm);

            // Agregar el nuevo film al arreglo y escribirlo al archivo JSON
            $films[] = $newFilm;
            $this->saveFilmsToJson($films);

            // Obtener las películas actualizadas y mostrar la lista
            $films = $this->getFilmsFromJson(); // O cualquier método correspondiente para obtener datos de SQL
            return view('films.list', ["films" => $films, "title" => $title]);
        }
    }


    public function listFilteredFilms($year = null, $genre = null)
    {
        $films_filtered = [];
        $title = "Listado de todas las pelis";
        $films = $this->getFilmsFromJson();

        // Filtrar películas basadas en el año y el género
        foreach ($films as $film) {
            if ((is_null($year) || $film['year'] == $year) && (is_null($genre) || strtolower($film['genre']) == strtolower($genre))) {
                $films_filtered[] = $film;
            }
        }
        // Devolver la vista con las películas filtradas
        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }
}
