<x-app-layout activeLink="cards">
    @section('title', 'Cards Index')

    <h1 class="mb-5">Cards index @if(Request::get('deck')) for {{ $cards->first()->deck->name }} @endif</h1>
    <div id="app">
       
        <table id="cardsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Tags</th>
                    <th>Appear On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cards as $card)
                <tr>
                    <td>{{ $card->question }}</td>
                    <td>{{ $card->answer }}</td>
                    <td>{{ $card->tags }}</td>
                    <td>{{ $card->appear_on }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('cards.edit', ['card' => $card->id ]) }}">Edit</a> <a class="btn btn-primary card__delete" href="{{ route('cards.destroy', ['card' => $card->id ]) }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Tags</th>
                    <th>Appear On</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>

    @section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/DataTables/datatables.min.css') }}"/>
 
    @endsection

    @section('scripts')
    <script type="text/javascript" src="{{ asset('/js/DataTables/datatables.min.js') }}"></script>
    <script>

    </script>
    @endsection
</x-app-layout>