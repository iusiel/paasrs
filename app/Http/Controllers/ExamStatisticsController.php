<?php

namespace App\Http\Controllers;

use App\Models\DeckExam;

class ExamStatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "exams" => DeckExam::orderBy("id", "desc")->get(),
        ];
        return view("exam-statistics.index", $data);
    }
}
