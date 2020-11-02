@extends('layouts.myLayout')

@section('content')
@if (0<count($errMsg))
    @foreach ($errMsg as $msg)
        {{ $msg }}
    @endforeach
@endif
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="folderName">
        <input type="file" name="uploadedFile[]" multiple="multiple">
        <button>Submit</button>
    </form>
@endsection