@extends('layouts.userlayout')
@section('content')

<x-calculator :eur="$rates->eur->amount" />

@endsection