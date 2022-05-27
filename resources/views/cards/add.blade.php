<x-app-layout activeLink="cards">
    @section('title', 'Add New Card')
    <h1 class="mb-3">Add New Card for {{ $deck->name }}</h1>
    <div id="app">
        <card-form deck="{{ Request::get('deck') }}" form-action="{{ route('cards.store') }}"  />
    </div>
</x-app-layout>