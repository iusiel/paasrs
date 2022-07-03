<x-app-layout activeLink="cards">
    @section('title', 'Add New Card')
    <h1 class="mb-3">Add New Card for {{ $deck->name }}</h1>
    <div id="app">
        <card-form v-bind:tags="tags" deck="{{ Request::get('deck') }}" form-action="{{ route('cards.store') }}"  />
    </div>

    <script>
        var tags = `{!! base64_encode(json_encode($tags)) !!}`;
    </script>

    @section('scripts')
    <script type="text/javascript" src="{{ asset('/js/DataTables/jQuery-3.6.0/jquery-3.6.0.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/simplemde.min.js') }}"></script>
    @endsection
</x-app-layout>