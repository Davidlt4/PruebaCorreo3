@extends('layouts.app')

@section('content')

<div class="mt-2 text-gray-600 dark::text-gray-400 text-sm">

<h3>Formulario</h3>
<p>Rellena este formulario para poder enviar correos a través de la api de Gmail.</p>

</div>

    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm flex justify-between">

        <form action="{{ route('generate.token') }}" method="post" class="mr-2">
            @csrf

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" class="form-input"><br><br>
            
            <label for="client_id">Usuario ID: </label>
            <input type="text" name="client_id" id="client_id" class="form-input"><br><br>

            <label for="client_secret">Secreto: </label>
            <input type="text" name="client_secret" id="client_secret" class="form-input"><br><br>

            <button type="submit" class="cursor p-2 px-6 bg-gray-900 text-gray-600 font-semibold">Enviar petición</button>

        </form>
        
    </div>

</div>

@endsection