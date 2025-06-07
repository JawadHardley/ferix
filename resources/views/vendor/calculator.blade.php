@extends('layouts.admin.main')
@section('content')

<x-calculator :eur="$rates->eur->amount" />


@endsection