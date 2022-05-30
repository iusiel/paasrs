<x-app-layout activeLink="decks">
    @section('title', 'Server Error - 403')

    <h1>43 | Server Error</h1>
    <p class="my-5">There has been an error on this page.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Go back to homepage</a>
</x-app-layout>