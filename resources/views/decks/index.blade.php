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
                <tr>
                    <th scope="row">Default deck</th>
                    <td>
                        <a class="me-4" href="#"><button class="btn btn-primary">Study</button></a>
                        <a class="me-4" href="#"><button class="btn btn-primary">Add Cards</button></a>
                        <a class="me-4" href="#"><button class="btn btn-primary">Edit Cards</button></a>
                        <a class="me-4" href="#"><button class="btn btn-primary">Options</button></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>