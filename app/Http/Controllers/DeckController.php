<?php

namespace App\Http\Controllers;

use App\Exports\CardsExport;
use App\Http\Requests\CreateDeckRequest;
use App\Http\Requests\DeckExamRequest;
use App\Http\Requests\UpdateDeckRequest;
use App\Models\Card;
use App\Models\Deck;
use App\Models\DeckExam;
use App\Services\DefaultDeckSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $decks = Deck::with([
            "cards" => function ($query) {
                $query->orderBy("appear_on", "asc");
                $query->where("appear_on", "<=", date("Y-m-d H:i:s"));
            },
        ])
            ->withCount(["cards as cardsCount"])
            ->orderBy("name", "ASC")
            ->get();

        $data = [
            "decks" => $decks,
        ];
        return view("decks/index", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDeckRequest $request)
    {
        $deckDetails = DefaultDeckSettings::get();
        $deckDetails["name"] = $request->name;
        Deck::create($deckDetails);

        if ($request->ajax()) {
            return response()->json([
                "message" => "Deck created successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Deck $deck)
    {
        $deck->load([
            "cards" => function ($query) use ($deck) {
                $query->where("appear_on", "<=", date("Y-m-d H:i:s"));
                $query->limit($deck->number_of_cards_per_review);
                $query->orderBy("appear_on", "asc");
            },
        ]);

        if ($deck->cards->count() === 0) {
            return redirect(route("decks.index"));
        }

        $data = [
            "deck" => $deck,
        ];
        return view("decks/study", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Deck $deck)
    {
        $data = [
            "deck" => $deck,
        ];

        return view("decks/edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeckRequest $request, Deck $deck)
    {
        $deck->name = $request->name;
        $deck->number_of_cards_per_review =
            $request->number_of_cards_per_review;
        $deck->hard_interval = $request->hard_interval;
        $deck->good_interval = $request->good_interval;
        $deck->easy_interval = $request->easy_interval;
        $deck->randomize_order_of_questions =
            $request->randomize_order_of_questions;
        $deck->save();

        if ($request->ajax()) {
            return response()->json([
                "message" => "Deck updated successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deck $deck, Request $request)
    {
        try {
            DB::beginTransaction();
            Card::where("deck_id", $deck->id)->delete();
            $deck->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        if ($request->ajax()) {
            return response()->json([
                "message" => "Deck deleted successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    // phpcs:ignore Generic.Metrics.CyclomaticComplexity
    public function import(Deck $deck, Request $request)
    {
        $handle = fopen($request->file("csv")->getPathname(), "r");

        $dataToInsert = [];
        while (($data = fgetcsv($handle, 0, ",")) !== false) {
            $question = $data[0] ?? "";
            $answer = $data[1] ?? "";
            $extraInformation = $data[2] ?? "";
            $tags = $data[3] ?? "";

            $dataToInsert[] = [
                "deck_id" => $deck->id,
                "question" => $question,
                "answer" => $answer,
                "extra_information" => $extraInformation,
                "tags" => $tags,
                "appear_on" => date("Y-m-d H:i:s"),
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];
        }
        fclose($handle);

        try {
            DB::beginTransaction();
            DB::table("cards")->insert($dataToInsert);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }

        if ($request->ajax()) {
            return response()->json([
                "message" => "Cards imported successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    public function export(Deck $deck)
    {
        return new CardsExport($deck);
    }

    public function showExamPage(Deck $deck, Request $request)
    {
        session(["examStart" => strtotime("now")]);

        $deck->load([
            "cards" => function ($query) use ($deck, $request) {
                if (!empty($request->limit)) {
                    $query->limit($request->limit);
                }
                $query->inRandomOrder();
            },
        ]);

        if ($deck->cards->count() === 0) {
            return redirect(route("decks.index"));
        }

        $data = [
            "deck" => $deck,
        ];
        return view("decks/exam", $data);
    }

    public function storeExamResults(DeckExamRequest $request)
    {
        $difference = date("H:i:s", strtotime("now") - session("examStart"));

        $deck = Deck::where("id", $request->deck_name)->first();

        $exam = new DeckExam();
        $exam->deck_name = $deck->name;
        $exam->number_of_questions = $request->number_of_questions;
        $exam->easy_answers = $request->easy_answers;
        $exam->good_answers = $request->good_answers;
        $exam->hard_answers = $request->hard_answers;
        $exam->time_to_finish_exam = $difference;
        $exam->save();

        if ($request->ajax()) {
            return response()->json([
                "message" => "Exam results saved to database.",
                "time_elapsed" => $difference,
            ]);
        }

        return redirect(route("home"));
    }
}
