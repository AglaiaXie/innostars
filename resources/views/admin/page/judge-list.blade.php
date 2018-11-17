@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <section class="hero is-dark">
        <div class="hero-body">
            <div class="container">
                <h2 class="title">Judges</h2>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <judge-admin :permission="permission"></judge-admin>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            components: {JudgeAdmin},
            data: {
                permission: {
                    role: '{{ Auth::user()->roles()->first()->name }}',
                    update: {{ Auth::user()->can('update-judge') ? 'true' : 'false' }},
                    delete: {{ Auth::user()->can('delete-judge') ? 'true' : 'false' }},
                    login: {{ Auth::user()->can('login-judge') ? 'true' : 'false' }},
                    private: {{ Auth::user()->can('show-private') ? 'true' : 'false' }},
                    export: {{ Auth::user()->can('export-judge') ? 'true' : 'false' }}
                }
            }
        });
    </script>
@endsection
