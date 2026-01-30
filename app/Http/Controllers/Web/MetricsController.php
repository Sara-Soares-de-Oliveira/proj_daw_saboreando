<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class MetricsController extends Controller
{
    public function explorador(Request $request)
    {
        $token = $request->session()->get('api_token');
        $period = (string) $request->query('period', 'week');
        $from = $request->query('from');
        $to = $request->query('to');

        $params = [];
        if ($from || $to) {
            if ($from) {
                $params['from'] = $from;
            }
            if ($to) {
                $params['to'] = $to;
            }
        } else {
            $params['period'] = $period;
        }

        $response = InternalApi::request('GET', '/api/metrics/explorador', $params, $token);
        $data = $response['ok'] ? ($response['json'] ?? []) : [];

        return view('metrics.explorador', [
            'metrics' => $data,
            'period' => $period,
        ]);
    }

    public function moderador(Request $request)
    {
        $token = $request->session()->get('api_token');
        $period = (string) $request->query('period', 'week');
        $from = $request->query('from');
        $to = $request->query('to');

        $params = [];
        if ($from || $to) {
            if ($from) {
                $params['from'] = $from;
            }
            if ($to) {
                $params['to'] = $to;
            }
        } else {
            $params['period'] = $period;
        }

        $response = InternalApi::request('GET', '/api/metrics/moderador', $params, $token);
        $data = $response['ok'] ? ($response['json'] ?? []) : [];

        return view('metrics.moderador', [
            'metrics' => $data,
            'period' => $period,
        ]);
    }
}
