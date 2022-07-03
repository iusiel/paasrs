<x-app-layout activeLink="cards">
    @section('title', 'Edit Card Id: ' . $card->id)
    <h1 class="mb-3">Edit Card Id: {{ $card->id }}</h1>

    <div id="app">
        <card-form v-bind:card="card" v-bind:decks="decks" v-bind:tags="tags"  deck="{{ $card->deck->id }}" form-action="{{ route('cards.update', ['card' => $card->id]) }}" editmode="true" />
    </div>

    <script>
        var card = `{!! base64_encode($card->toJson()) !!}`;
        var decks = `{!! base64_encode($decks->toJson()) !!}`;
        var tags = `{!! base64_encode(json_encode($tags)) !!}`;
    </script>

    @section('scripts')
    <script type="text/javascript" src="{{ asset('/js/DataTables/jQuery-3.6.0/jquery-3.6.0.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/simplemde.min.js') }}"></script>
    @endsection
</x-app-layout>