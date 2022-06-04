<?php

namespace App\Http\Controllers;

use App\Exports\CardsExport;
use App\Http\Requests\CreateDeckRequest;
use App\Http\Requests\UpdateDeckRequest;
use App\Models\Card;
use App\Models\Deck;
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
        ])->get();
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
                // phpcs:ignore - ternary operator. if randomize setting is enabled, use inRandomOrder(), else use orderBy()
                $deck->randomize_order_of_questions
                    ? $query->inRandomOrder()
                    : $query->orderBy("appear_on", "asc");
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

    public function import(Deck $deck, Request $request)
    {
        $handle = fopen($request->file("csv")->getPathname(), "r");

        $dataToInsert = [];
        while (($data = fgetcsv($handle, 0, ",")) !== false) {
            [$question, $answer, $extraInformation, $tags] = $data;
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
}
