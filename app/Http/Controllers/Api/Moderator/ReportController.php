<?php

namespace App\Http\Controllers\Api\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['comment.recipe', 'user'])
            ->latest()
            ->get();

        return ReportResource::collection($reports);
    }
}
