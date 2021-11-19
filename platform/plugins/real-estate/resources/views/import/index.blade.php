@extends('core/base::layouts.master')
@section('content')
{!! Form::open(['url' => route('real-estate.import'), 'enctype' => 'multipart/form-data']) !!}

    <div class="row">
        <div class="col-12">
            <div class=" main-form px-3">
                <h2>Importacion de Archivo Excel</h2>
                <div class="mt-2 d-flex align-items-center">
                    <input type="file" name="file" class="mr-5"/>
                    <input type="submit" name="upload" class="btn btn-primary" value="Subir">
                </div>

            </div>
        </div>
    </div>


    {!! Form::close() !!}
@endsection
