<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller{
  

     //Controlador index donde se obtiene el objeto request que tiene distintos datos como el critero y buscar
    //donde criterio es el campo por donde se quiere buscar, nombre, descripción, categoría, etc y buscar es el 
    //dato que se quiere buscar
    public function index(Request $request)
    {

        //Se crea una variable categorias para obtener los datos de las categorias
        //para ello se utiliza el select y posteriormente ordenarlo por el id de manera menor a mayor
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
        }
        ///En caso de que que haya buscado una categoría en concreto se realiza una concatenacion con el
            //parametro recibido de buscar
        else{
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        
        //se botienen los datos de la búsqueda
        return [
            'pagination' => [
                'total'        => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page'     => $categorias->perPage(),
                'last_page'    => $categorias->lastPage(),
                'from'         => $categorias->firstItem(),
                'to'           => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }

    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['categorias' => $categorias];
    }

   

    //Función que se utiliza para guardar nuevos registros en la base de datos
    public function store(Request $request){
        //objeto articulo de la clase del modelo donde se obtiene los datos escritos en el CRUD y al final se utiliza
        //la función save() para guardarlo totalmente
        if (!$request->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }
  

    //Función que se utiliza para actualizar el registro que se desea a base del id
    //se realiza una busqueda con la función find or fail haciendo referencia al id escrito
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }
    //Función que cambia el estado a desactivado del producto junto con el id
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }
     //Función que cambia el estado a activado del producto junto con el id
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }

    
}
