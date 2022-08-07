<x-app-layout activeLink="decks">
    @section('title', 'Exam |  ' . $deck->name)

    <h1 class="mb-3">{{ $deck->name }}</h1>
    <div id="app">
        <deck-exam form-action="{{ route('decks.exam.process', ['deck' => $deck->id ] ) }}" deck="{{ base64_encode($deck->toJson()) }}" />
    </div>
</x-app-layout>