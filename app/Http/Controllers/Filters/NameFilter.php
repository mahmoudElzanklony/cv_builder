<?php


namespace App\Http\Controllers\Filters;


use Illuminate\Support\Str;
use Closure;
class NameFilter extends FilterRequest
{
    public function handle($request, Closure $next){
        if (! request()->filled('name')) {
            return $next($request);
        }

        return $next($request)
            ->whereRaw('(ar_name LIKE "%'.request()->input('name').'%" OR en_name LIKE "%'.request()->input('name').'%")');
    }
}
