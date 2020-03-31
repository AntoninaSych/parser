<?php

namespace App\Http\LogicalModels;

use App\Http\Models\Type;


class ProxyTypeLogicalModel
{

    public function save(string $proxyType): Type
    {
        $country = Type::where('name', $proxyType)->first();

        if (is_null($country)) {
            Type::firstOrCreate(['name' => $proxyType])->save();
            $country = Type::where('name', $proxyType)->first();
        }

        return $country;
    }
}
