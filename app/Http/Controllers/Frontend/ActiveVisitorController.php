<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActiveVisitor;
use Illuminate\Support\Str;

class ActiveVisitorController extends Controller
{
    public function heartbeat(Request $request)
    {
        $visitorId = $request->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = Str::uuid()->toString();
        }

        $visitor = ActiveVisitor::updateOrCreate(
            ['visitor_id' => $visitorId],
            [
                'user_id'     => auth()->check() ? auth()->id() : null,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'current_url' => $request->fullUrl(),
                'last_seen_at'=> now(),
                'left_at'     => null,
            ]
        );

        $activeCount = ActiveVisitor::whereNull('left_at')
            ->where('last_seen_at', '>=', now()->subSeconds(15))
            ->count();

        return response()->json([
            'status' => true,
            'visitor_id' => $visitorId,
            'active_count' => $activeCount,
        ])->cookie('visitor_id', $visitorId, 60 * 24 * 30);
    }

    public function leave(Request $request)
    {
        $visitorId = $request->cookie('visitor_id');

        if ($visitorId) {
            ActiveVisitor::where('visitor_id', $visitorId)
                ->update([
                    'left_at' => now()
                ]);
        }

        return response()->json(['status' => true]);
    }

    public function count()
    {
        $activeCount = ActiveVisitor::whereNull('left_at')
            ->where('last_seen_at', '>=', now()->subSeconds(15))
            ->count();

        return response()->json([
            'status' => true,
            'active_count' => $activeCount,
        ]);
    }
}
