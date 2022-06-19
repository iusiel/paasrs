<x-app-layout activeLink="cards">
    @section('title', 'Cards Index')

    <h1 class="mb-5">Cards index @if(Request::get('deck')) for {{ $cards->first()->deck->name }} @endif</h1>
    <div id="app">

        @if(Request::get('deck'))
        <a class="mb-4 btn btn-primary" href="{{ route('cards.create', ['deck' => $cards->first()->deck->id ]) }}">Add Cards</a>
        @endif
       
        <table id="cardsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Tags</th>
                    <th>Appear On</th>
                    <th></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cards as $card)
                <tr class="@if(!empty($card->marked_message)) marked-card @endif" >
                    <td>{{ $card->id }}</td>
                    <td>
                        {{ $card->question }}
                        @if(!empty($card->marked_message))
                        <span class="marked-card__icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $card->marked_message }}">?</span>
                        @endif
                    </td>
                    <td>{{ $card->answer }}</td>
                    <td>{{ $card->tags }}</td>
                    <td>{{ $card->appear_on }}</td>
                    <td>
                        @if($card->marked_message)
                        <a href="{{ route('cards.edit', ['card' => $card->id ]) }}">
                            <span class="marked-card__icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $card->marked_message }}">!</span>
                        </a>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-primary my-1" href="{{ route('cards.edit', ['card' => $card->id ]) }}">Edit</a> <a class="btn btn-primary card__delete my-1" href="{{ route('cards.destroy', ['card' => $card->id ]) }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Tags</th>
                    <th>Appear On</th>
                    <th></th>
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
    @endsection
</x-app-layout>