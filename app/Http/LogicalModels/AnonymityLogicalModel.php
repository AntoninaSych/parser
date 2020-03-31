<?php

namespace App\Http\LogicalModels;

use App\Http\Models\Anonymity;


class AnonymityLogicalModel
{


    public function save(string $anonimytyName): Anonymity
    {
        $anonimyty = Anonymity::where('name', $anonimytyName)->first();

        if (is_null($anonimyty)) {
        Anonymity::firstOrCreate(['name' => $anonimytyName])->save();
            $anonimyty = Anonymity::where('name', $anonimytyName)->first();

        }

        return $anonimyty;
    }
}
