<x-app-layout activeLink="exam">
    @section('title', 'Exam Statistics')
    <h1 class="mb-5">Exam Statistics</h1>
    <div id="app">

        <table id="examStatisticsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Deck Name</th>
                    <th>Easy</th>
                    <th>Good</th>
                    <th>Hard</th>
                    <th>Time To Finish Exam</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exams as $exam)
                <tr  >
                    <td>{{ $exam->id }}</td>
                    <td>{{ $exam->created_at }}</td>
                    <td>
                        {{ $exam->deck_name }}
                    </td>
                    <td>{{ $exam->easy_answers }} / {{ $exam->number_of_questions }} ({{ $exam->easy_answers_percentage }}%)</td>
                    <td>{{ $exam->good_answers }} / {{ $exam->number_of_questions }} ({{ $exam->good_answers_percentage }}%)</td>
                    <td>{{ $exam->hard_answers }} / {{ $exam->number_of_questions }} ({{ $exam->hard_answers_percentage }}%)</td>
                    <td>{{ $exam->time_to_finish_exam }}</td>
                    <td>
                        <exam-statistics-related-button deck-name="{{ $exam->deck_name }}" encoded-exam-statistics="{!! base64_encode($exams->toJson()) !!}" ></exam-statistics-related-button>
                    </td>
                </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
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
    <script>
        var examStatistics =`{!! base64_encode($exams->toJson()) !!}`; 
    </script>

    <script type="text/javascript" src="{{ asset('/js/DataTables/datatables.min.js') }}"></script>
    @endsection
</x-app-layout>