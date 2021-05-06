<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\PostRequest;
// use Illuminate\Http\Request;

// Clase para eliminar imagen 
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $posts = Post::latest()->get();
        return view("posts.index", compact('posts'));
    } //envia los datos a la 		vista //

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        
        // dd($request->all());
        //salvar información
        $post = Post::create([
            // El user_id se rellena con el id del usuario logueado
            'user_id' => auth()->user()->id
        ] + $request->all());

        // trabajar con imagenes
        /* 
            Si recibimos un archivo debemos salvarlos en nuestro proyecto
            para despues almacenar la url de ese archivo en la base de datos.
            Nunca guardamos un archivo como tal en la base de datos.

         */

        if($request->file('file')){
            /*
             Enviamos la imagen al servidor con el método store
             store() -> Store the uploaded file on a filesystem disk.
             El orden de store es el siguiente guardame dentro de public
             en la acrpeta store la imagen y almacena la ruta en el campo image del post.
            */
            $post->image = $request->file('file')->store('posts','public');

            // Salvamos la información
            $post->save();
        }
        
        // retornar resultado
        // EL método back crea una solicitud de redirección 
        // hacia la ultima ubicación antes de disparar este método
        return back()->with('status','Creado con éxito');
       
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    // public function show(Post $post)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        // dd($request->all());
        // Paso 1. Editamos
        $post->update($request->all());
        
        // Paso 2. Tratamos la imagen
            if ($request->file('file')) {
            //eliminar imagen
            Storage::disk('public')->delete($post->image);
            $post->image = $request->file('file')->store('posts', 'public');
            $post->save();
        }
        
        return back()->with('status', 'Actualizado con exito');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //eliminacion de imagen
        $storage::disk('public')->delete($post->image);
        $post->delete();

        return back()->with('status', 'Eliminado con éxito');
    }
}
