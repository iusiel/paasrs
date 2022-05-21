<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->query->get('deck')) {
            return redirect(route('decks.index'));
        }

        $deck = Deck::where('id', $request->deck)->first();
        if (empty($deck)) {
            return redirect(route('decks.index'));
        }

        $data = [
            'deck' => $deck,
        ];
        return view('cards/add', $data);
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

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Card created successfully.',
            ]);
        }

        return redirect(route('decks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        dd($card);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        dd($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        dd($request);
        dd($card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        dd($card);
    }
}
