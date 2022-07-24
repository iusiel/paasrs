<x-app-layout activeLink="decks">
    @section('title', 'Decks Index')

    <h1 class="mb-3">Decks index</h1>
    <div id="app">
        <create-deck-form></create-deck-form>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th class="decks__table-header" scope="col">Deck name</th>
                    <th class="decks__table-header" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($decks as $deck)
                <tr>
                    <th scope="row">{{ $deck->name }}</th>
                    <td>
                        <study-button activate-on="{{ $deck->earliest_activation_card }}" href="{{ route('decks.show', ['deck' => $deck->id]) }}" disabled="@if($deck->cards->count() === 0) true @endif"></study-button>
                        <a class="me-md-4 mb-2 mb-xl-0 py-2 d-block d-md-inline-block btn btn-primary" href="{{ route('cards.create', ['deck' => $deck->id]) }}">Add Cards</a>
                        <a class="me-md-4 mb-2 mb-xl-0 py-2 d-block d-md-inline-block btn btn-primary @if($deck->cardsCount === 0)pe-none disabled @endif" href="{{ route('cards.index', ['deck' => $deck->id]) }}">Edit Cards</a>
                        <a class="me-md-4 mb-2 mb-xl-0 py-2 d-block d-md-inline-block btn btn-primary" href="{{ route('decks.edit', ['deck' => $deck->id]) }}">Edit Deck Options</a>
                        <import-cards form-action="{{ route('decks.import', ['deck' => $deck->id]) }}"></import-cards>
                        <a href="{{ route('decks.export', ['deck' => $deck->id]) }}" class="d-block d-md-inline-block mb-2 mb-xl-0 py-2 btn btn-primary">Export Cards</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>