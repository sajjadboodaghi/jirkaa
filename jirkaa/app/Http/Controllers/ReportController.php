<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Report;
use Auth;

class ReportController extends Controller
{
	public function postStore($link_id)
	{
		$report = Report::where('link_id', $link_id);
		if($report->count()) {
			return response('OK', 200);
		} else {
			Report::create([
				'link_id' => $link_id,
				'reporter_id' => Auth::id()
				]);
			return response('OK', 200);
		}
	}
}
