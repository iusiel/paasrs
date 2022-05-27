<x-app-layout activeLink="decks">
    @section('title', 'Decks Index')

    <h1 class="mb-3">Decks index</h1>
    <div id="app">
        <create-deck-form></create-deck-form>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th scope="col">Deck name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($decks as $deck)
                <tr>
                    <th scope="row">{{ $deck->name }}</th>
                    <td>
                        <a class="me-4 @if($deck->cards->count() === 0)pe-none disabled @endif" href="{{ route('decks.show', ['deck' => $deck->id]) }}" disabled><button class="btn btn-primary">Study</button></a>
                        <a class="me-4" href="{{ route('cards.create', ['deck' => $deck->id]) }}"><button class="btn btn-primary">Add Cards</button></a>
                        <a class="me-4 @if($deck->cards->count() === 0)pe-none disabled @endif" href="{{ route('cards.index', ['deck' => $deck->id]) }}"><button class="btn btn-primary">Edit Cards</button></a>
                        <a class="me-4" href="{{ route('decks.edit', ['deck' => $deck->id]) }}"><button class="btn btn-primary">Edit Deck Options</button></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>