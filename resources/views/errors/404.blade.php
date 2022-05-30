<x-app-layout activeLink="decks">
    @section('title', 'Page not found - 404')

    <h1>404 | Page not found</h1>
    <p class="my-5">Please make sure that the page that you are trying to visit is correctly typed.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Go back to homepage</a>
</x-app-layout>