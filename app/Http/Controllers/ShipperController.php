<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use Illuminate\Http\Request;

class ShipperController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('isAdmin');
    }

    public function index()
    {
        $shippers = Shipper::all();
        return $this->responseSuccess(200, "Fetched all successfully", $shippers);
    }

    public function create()
    {
        $shipper = Shipper::create($this->request->toArray());
        return $this->responseSuccess(200, "Shipper added", $shipper);
    }

    public function edit($id)
    {
        Shipper::where(['id' => $id])->update(["name" => $this->request->name]);
        $shipper = Shipper::find($id);
        return $this->responseSuccess(200, "Shipper updated", $shipper);
    }

    public function delete($id)
    {
        Shipper::destroy($id);
        return $this->responseSuccess(200, "Shipper deleted");
    }
    
}
