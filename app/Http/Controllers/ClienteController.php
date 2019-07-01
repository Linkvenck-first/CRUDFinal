<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class ClienteController extends Controller{

    //Controlador index donde se obtiene el objeto request que tiene distintos datos como el critero y buscar
    //donde criterio es el campo por donde se quiere buscar, nombre, descripción, categoría, etc y buscar es el 
    //dato que se quiere buscar
    public function index(Request $request){

        //Se crea una variable personas para obtener los datos de los clientes
        //para ello se utiliza el select y posteriormente ordenarlo por el id de manera menor a mayor
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = Persona::orderBy('id', 'desc')->paginate(10);
        }
        else{
            ///En caso de que que haya buscado un cliente en concreto se realiza una concatenacion con el
            //parametro recibido de buscar
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        
        //se botienen los datos de la búsqueda
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre','num_documento')
        ->orderBy('nombre', 'asc')->get();

        return ['clientes' => $clientes];
    }

    //Función que se utiliza para guardar nuevos registros en la base de datos
    public function store(Request $request){

        //objeto articulo de la clase del modelo donde se obtiene los datos escritos en el CRUD y al final se utiliza
        //la función save() para guardarlo totalmente
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;

        $persona->save();
    }
    //Función que se utiliza para actualizar el registro que se desea a base del id
    //se realiza una busqueda con la función find or fail haciendo referencia al id escrito
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }
}
