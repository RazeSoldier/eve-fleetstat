@extends('layouts.app')

@section('title', "$fleetHash - 舰队分析")

@section('content')
    <view-fleet-member-list v-bind:is-done="{{$isDone}}" fleet-hash="{{$fleetHash}}">
    </view-fleet-member-list>
@endsection
