@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <section class="hero is-dark">
        <div class="hero-body">
            <div class="container">
                <h2 class="title">Events</h2>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <event-admin :permission="permission"></event-admin>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            components: {EventAdmin},
            data: {
                permission: {
                    role: '{{ Auth::user()->roles()->first()->name }}',
                    create: {{ Auth::user()->can('create-event') ? 'true' : 'false' }},
                    override: {{ Auth::user()->can('override-event') ? 'true' : 'false' }},
                    id: {{ Auth::user()->getKey() }}
                }
            }
        });
    </script>
@endsection
