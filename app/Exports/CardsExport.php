<?php

namespace App\Exports;

use App\Models\Card;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

/**
 * @SuppressWarnings("unused")
 *
 */
class CardsExport implements FromCollection, Responsable, WithMapping
{
    use Exportable;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = "";

    /**
     * Optional Writer Type
     *
     *
     */
    private $writerType = Excel::CSV;

    /**
     * Optional headers
     *
     */
    private $headers = [
        "Content-Type" => "text/csv",
    ];

    private $deck;

    public function __construct($deck)
    {
        $this->fileName =
            preg_replace("/[\W]/", "_", strtolower($deck->name)) . "-cards.csv";
        $this->deck = $deck;
    }

    public function collection()
    {
        return $this->deck->cards;
    }

    public function map($card): array
    {
        return [
            $card->question,
            $card->answer,
            $card->extra_information,
            $card->tags,
        ];
    }
}
