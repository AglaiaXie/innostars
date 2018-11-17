@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <section class="hero is-dark">
        <div class="hero-body">
            <div class="container">
                <h2 class="title">Contestants</h2>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <participant-admin :permission="permission"></participant-admin>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            components: {ParticipantAdmin},
            data: {
                permission: {
                    role: '{{ Auth::user()->roles()->first()->name }}',
                    update: {{ Auth::user()->can('update-participant') ? 'true' : 'false' }},
                    delete: {{ Auth::user()->can('delete-participant') ? 'true' : 'false' }},
                    login: {{ Auth::user()->can('login-participant') ? 'true' : 'false' }},
                    private: {{ Auth::user()->can('show-private') ? 'true' : 'false' }},
                    export: {{ Auth::user()->can('export-participant') ? 'true' : 'false' }}
                }
            }
        });
    </script>
@endsection
