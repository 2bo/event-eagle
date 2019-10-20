@extends('layouts.app')

@section('content')
    <events-index-component :workshops="{{$workshops}}"></events-index-component>
@endsection
