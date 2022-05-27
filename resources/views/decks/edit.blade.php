<x-app-layout activeLink="decks">
    @section('title', 'Edit Settings for Deck Id: ' . $deck->id)

    <h1 class="mb-3">Edit Settings for Deck Id: {{ $deck->id }}</h1>
    <div id="app">
        <deck-settings-form v-bind:deck="deck" form-action="{{ route('decks.update', ['deck' => $deck->id]) }}" delete-action="{{ route('decks.destroy', ['deck' => $deck->id ]) }}"/>
    </div>
    <script>
        var deck = `{!! base64_encode($deck->toJson()) !!}`;
    </script>
</x-app-layout>