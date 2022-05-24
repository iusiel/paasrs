<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDeckRequest;
use App\Models\Deck;
use App\Services\DefaultDeckSettings;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $decks = Deck::with(['cards' => function ($query) {
            $query->orderBy('appear_on', 'asc');
            $query->where('appear_on', '<=', date("Y-m-d H:i:s"));
        }])->get();
        $data = [
            'decks' => $decks
        ];
        return view('decks/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $deckDetails['name'] = $request->name;
        Deck::create($deckDetails);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Deck created successfully.',
            ]);
        }

        return redirect(route('decks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Deck $deck)
    {
        $deck->load(['cards' => function ($query) use ($deck) {
            $query->orderBy('appear_on', 'asc');
            $query->where('appear_on', '<=', date("Y-m-d H:i:s"));
            $query->limit($deck->number_of_cards_per_review);
        }]);

        if ($deck->cards->count() === 0) {
            return redirect(route('decks.index'));
        }

        $data = [
            'deck' => $deck,
        ];
        return view('decks/study', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($id);
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOptions(Deck $deck)
    {
        echo "Options screen";
        dd($deck);
    }
}
