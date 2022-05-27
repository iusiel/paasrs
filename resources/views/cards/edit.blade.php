<x-app-layout activeLink="cards">
    @section('title', 'Edit Card Id: ' . $card->id)
    <h1 class="mb-3">Edit Card Id: {{ $card->id }}</h1>

    <div id="app">
        <card-form v-bind:card="card" v-bind:decks="decks" deck="{{ $card->deck->id }}" form-action="{{ route('cards.update', ['card' => $card->id]) }}" editmode="true" />
    </div>

    <script>
        var card = `{!! base64_encode($card->toJson()) !!}`;
        var decks = `{!! base64_encode($decks->toJson()) !!}`;
    </script>
</x-app-layout>