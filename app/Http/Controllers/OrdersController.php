<?php

namespace App\Http\Controllers;

use App\Http\traits\messages;
use App\Models\orders;
use App\Models\percentages;
use App\Models\templates;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    //

    public function index()
    {

    }

    public function make($template_id)
    {
        $template = templates::query()->findOrFailWithMsg($template_id,trans('errors.not_found'));
        $profit  = percentages::query()->where('name','=','profit')->first();
        $order = orders::query()->create([
            'user_id'=>auth()->id(),
            'template_id'=>$template_id,
            'price'=>$template->price,
            'service_profit'=>$profit->percentage,
        ]);
        return messages::success_output(trans('messages.payment_success'));
    }
}
