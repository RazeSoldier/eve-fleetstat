@extends('layouts.app')

@section('title', "$fleetHash - 舰队分析")

@section('content')
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">舰队成员列表</div>
            <div class="card-body">
                <view-fleet-member-list v-bind:is-done="{{$isDone}}" fleet-hash="{{$fleetHash}}">
                </view-fleet-member-list>
            </div>
        </div>
    </div>
@endsection
