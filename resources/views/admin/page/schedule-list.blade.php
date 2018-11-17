@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <section class="hero is-dark">
        <div class="hero-body">
            <div class="container">
                <h2 class="title">Schedules</h2>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <schedule-admin :permission="permission"></schedule-admin>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            components: {ScheduleAdmin},
            data: {
                permission: {
                    role: '{{ Auth::user()->roles()->first()->name }}',
                    id: {{ Auth::user()->getKey() }}
                }
            }
        });
    </script>
@endsection
