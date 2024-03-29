<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actor;

use Illuminate\Support\Facades\DB;

class ActorController extends Controller
{

        /**
         * Lista TODOS los actores
         */
        public static function readActors()
        {
                $actors = Actor::select('name', 'surname', 'birthdate', 'country', 'img_url')->get();
                $actorsArray = json_decode(json_encode($actors), true);
                return $actorsArray;
        }

        public function listActors()
        {
                $title = "Listado de todos los actores";
                $actors = ActorController::readActors();

                return view('actors.list', ['actors' => $actors, 'title' => $title]);
        }
        public function listActorsByDecade()
        {
                $decadaSelect = $_GET['decada'];

                $InicioDecada = $decadaSelect;
                $FinalDecada = $InicioDecada + 10;

                $actorsList = ActorController::readActors();
                $actors = array();

                foreach ($actorsList as $actor) {
                        $añoNacimiento = $actor['birthdate'];
                        if ($añoNacimiento >= $InicioDecada && $añoNacimiento <= $FinalDecada) {
                                $actors[] = $actor;
                        }
                }
                $FinalDecada = $InicioDecada + 9;
                $title = "Listado de Actores por Década ($InicioDecada - $FinalDecada)";
                return view('actors.list', ['actors' => $actors, 'title' => $title]);
        }
        public function countActors()
        {
                $actors = ActorController::readActors();
                $totalActors = count($actors);
                return view('actors.counter', ['totalActors' => $totalActors]);
        }
        public function deleteActor($id)
        {
                // Encontrar al actor por su ID
                $actor = Actor::find($id);

                if ($actor) {
                        $actor->delete();
                        // Eliminar el actor
                        DB::table("actors")->where($id)->delete();
                        return response()->json(['acción' => 'delete', 'status' => 'true']);
                } else {
                        return response()->json(['acción' => 'delete', 'status' => 'false']);
                }
        }
}
