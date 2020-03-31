<?php

namespace App\Http\LogicalModels;

use App\Http\Models\Proxy;


class ProxyLogicalModel
{

    public function save(Proxy $proxy): void
    {
        $proxy->port = 0; //TODO create from port_string
        $proxy->save();
    }
}
