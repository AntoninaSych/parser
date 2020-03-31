<?php


namespace App\Http\Controllers;


use App\Http\LogicalModels\AnonymityLogicalModel;
use App\Http\LogicalModels\CountryLogicalModel;
use App\Http\LogicalModels\ProxyLogicalModel;
use App\Http\LogicalModels\ProxyTypeLogicalModel;
use App\Http\Models\Proxy;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use phpQuery;


class ParserController extends Controller
{
    public $country;
    public $type;
    public $anonymity;
    public $proxy;

    public function __construct(CountryLogicalModel $country,
                                ProxyTypeLogicalModel $type,
                                AnonymityLogicalModel $anonymity,
                                ProxyLogicalModel $proxy)
    {
        $this->country = $country;
        $this->type = $type;
        $this->anonymity = $anonymity;
        $this->proxy = $proxy;
    }


    public function index()
    {
        return view('welcome');
    }


    public function getWebsite()
    {
        $baseUrl = 'http://spys.one';
        $part = '/free-proxy-list/ALL/';

        $url = $baseUrl . $part;
        $request = $this->nextPage($url);

        $pq = phpQuery::newDocument($request);

        $links = $pq->find('a');

        foreach ($links as $link) {
            $pqLink = pq($link); //pq делает объект phpQuery
            $text[] = $pqLink->html();
            if (stristr($pqLink->attr('href'), $part) !== false) {
                $part= stristr($pqLink->attr('href'), $part);
                $url = $baseUrl . $part;
                $request = $this->nextPage($url);
            }
        }

        return $request;
    }


    private function nextPage($url = null)
    {
        $client = new Client();
        $params = [
            'xx0' => '',
            'xpp' => 5,
            'xf1' => 0,
            'xf2' => 0,
            'xf4' => 0,
            'xf5' => 0
        ];

        $request = $client->request('get', $url)->getBody();
        $request = $request->getContents();
        if ($request) {sleep(5);
            $pos1 = strpos($request, 'name=\'xx0\' value=\'', 3);
            $hash = substr($request, $pos1 + 18, 32);
            $params['xx0'] = $hash;
        }
        $request = $client->request('post', $url, ['form_params' => $params])->getBody()->getContents();

        $doc = phpQuery::newDocument($request);
        $table = phpQuery::newDocument($doc['body:nth-child(2) tr:nth-child(5) table']);
        $trs = $table->find('tr[onmouseover]');
        $data = $this->parse($trs);
        $this->save($data);

        return $request;
    }


    private function parse($data)
    {
        $collection = new Collection();

        foreach ($data as $item) {
            $proxy = new Proxy();
            $el = pq($item);

            $type = ($el->find('td:nth-child(2)')->text());
            $type = $this->type->save(substr(trim($type), 0, 5));
            $proxy->type = $type->id;

            $proxy->ip = $el->find('td:nth-child(1)')->text();
            $pos = strpos($proxy->ip, 'document.write');
            $proxy->ip = substr($proxy->ip, 0, $pos);

            $proxy->port_string = $el->find('td:nth-child(1)')->text();

            $end = strlen($proxy->port_string);
            $proxy->port_string = trim(substr($proxy->port_string, $pos, $end));

            $proxy->anonymity = $el->find('td:nth-child(3)')->text();
            $anonymity = $this->anonymity->save($proxy->anonymity);
            $proxy->anonymity = $anonymity->id;

            $proxy->country = substr($el->find('td:nth-child(4)')->text(), 0, 3);
            $country = $this->country->save($proxy->country);
            $proxy->country = $country->id;

            $collection->push($proxy);
        }

        return $collection;
    }

    private function save($collection)
    {
        foreach ($collection as $proxy) {
            $this->proxy->save($proxy);
        }
    }

}
