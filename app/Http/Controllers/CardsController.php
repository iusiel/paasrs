<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Http\Requests\CardUpdateAppearOnRequest;
use App\Http\Requests\MarkCardRequest;
use App\Models\Card;
use App\Models\Deck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cards = Card::with("deck");
        if ($request->query->get("deck")) {
            $cards->where("deck_id", $request->deck);
        }

        $cards = $cards->orderBy("id", "DESC")->get();
        if ($cards->count() === 0) {
            return redirect(route("decks.index"));
        }

        $data = [
            "cards" => $cards,
        ];
        return view("cards/index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->query->get("deck")) {
            return redirect(route("decks.index"));
        }

        $deck = Deck::where("id", $request->deck)->first();
        if (empty($deck)) {
            return redirect(route("decks.index"));
        }

        $data = [
            "deck" => $deck,
        ];
        return view("cards/add", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardRequest $request)
    {
        $card = new Card();
        $card->deck_id = $request->deck_id;
        $card->question = $request->question;
        $card->answer = $request->answer;
        $card->extra_information = $request->extra_information;
        $card->tags = $request->tags;
        $card->appear_on = date("Y-m-d H:i:s");
        $card->save();

        if (!empty($request->create_reverse_card)) {
            $this->createReverseCard($card);
        }

        if ($request->ajax()) {
            return response()->json([
                "message" => "Card created successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    private function createReverseCard(Card $card)
    {
        try {
            $existingCard = Card::where("question", $card->answer)
                ->where("answer", $card->question)
                ->first();
            if (empty($existingCard)) {
                $reverseCard = new Card();
                $reverseCard->deck_id = $card->deck_id;
                $reverseCard->question = $card->answer;
                $reverseCard->answer = $card->question;
                $reverseCard->extra_information = $card->extra_information;
                $reverseCard->tags = $card->tags;
                $reverseCard->appear_on = date("Y-m-d H:i:s");
                $reverseCard->save();
            }
        } catch (Exception $e) {
            Log::warning($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        $decks = Deck::select("id", "name")->get();
        $data = [
            "card" => $card,
            "decks" => $decks,
        ];
        return view("cards/edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(CardRequest $request, Card $card)
    {
        $card->deck_id = $request->deck_id;
        $card->question = $request->question;
        $card->answer = $request->answer;
        $card->extra_information = $request->extra_information;
        $card->tags = $request->tags;
        $card->save();

        if (!empty($request->create_reverse_card)) {
            $this->createReverseCard($card);
        }

        if ($request->ajax()) {
            return response()->json([
                "message" => "Card updated successfully.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card, Request $request)
    {
        $card->delete();

        if ($request->ajax()) {
            return response()->json([
                "message" => "Card deleted successfully.",
            ]);
        }

        return redirect(route("cards.index"));
    }

    public function updateAppearOn(
        Card $card,
        CardUpdateAppearOnRequest $request
    ) {
        if ($request->interval === "easy") {
            $interval = "+ " . $card->deck->easy_interval . " days";
        }
        if ($request->interval === "good") {
            $interval = "+ " . $card->deck->good_interval . " days";
        }
        if ($request->interval === "hard") {
            $interval = "+ " . $card->deck->hard_interval . " days";
        }

        $card->appear_on = date("Y-m-d H:i:s", strtotime($interval));
        $card->save();

        if ($request->ajax()) {
            return response()->json([
                "message" => "Card successfully updated.",
            ]);
        }

        return redirect(route("decks.index"));
    }

    public function markCard(Card $card, MarkCardRequest $request)
    {
        $card->marked_message = $request->marked_message;
        $card->save();

        if ($request->ajax()) {
            return response()->json([
                "message" => "You have marked this card.",
            ]);
        }

        return redirect(route("cards.index"));
    }
}
