<?php

namespace App\Http\LogicalModels;

use App\Http\Models\Country;


class CountryLogicalModel
{


    public function save(string $countryCode): Country
    {
        $country = Country::where('name', $countryCode)->first();

        if (is_null($country)) {
            Country::firstOrCreate(['name' => $countryCode])->save();
            $country = Country::where('name', $countryCode)->first();

        }

        return $country;
    }
}
