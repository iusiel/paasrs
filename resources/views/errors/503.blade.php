<x-app-layout activeLink="decks">
    @section('title', 'Under Maintenance')

    <h1>Under Maintenance</h1>
    <p class="my-5">Website is currently under maintenance. Please come back later.</p>
    <a class="btn btn-primary" href="{{ url('/') }}">Go back to homepage</a>
</x-app-layout>