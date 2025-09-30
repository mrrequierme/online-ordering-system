<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Order;

class HistoryController extends Controller
{
    public function index(){
        $histories = History::orderByRaw("
        case
        when status = 'done' then 1
        when status = 'unclaimed' then 2
        else 3
        end
        ")
        ->with(['products'])->orderBy('claim_date','desc')->get();
        
        return view('admins.histories.index',compact('histories'));
    }
}
