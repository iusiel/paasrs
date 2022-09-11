<x-app-layout activeLink="exams">
    @section('title', 'Exam Statistics')

    <h1 class="mb-5">Exam Statistics</h1>
    <div id="app">

        <table id="examStatisticsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Deck Name</th>
                    <th>Easy</th>
                    <th>Good</th>
                    <th>Hard</th>
                    <th>Time To Finish Exam</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exams as $exam)
                <tr  >
                    <td>{{ $exam->id }}</td>
                    <td>
                        {{ $exam->deck_name }}
                    </td>
                    <td>{{ $exam->easy_answers }} / {{ $exam->number_of_questions }}</td>
                    <td>{{ $exam->good_answers }} / {{ $exam->number_of_questions }}</td>
                    <td>{{ $exam->hard_answers }} / {{ $exam->number_of_questions }}</td>
                    <td>{{ $exam->time_to_finish_exam }}</td>

        
                </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Deck Name</th>
                    <th>Easy</th>
                    <th>Good</th>
                    <th>Hard</th>
                    <th>Time To Finish Exam</th>
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