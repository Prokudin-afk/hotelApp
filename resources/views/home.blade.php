@extends('layouts.main')

@section('content')
    <p>hello</p>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log('hello');
        })
    </script>
@endsection