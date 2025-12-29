<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtpNotification;
use Illuminate\Http\Request;

class OtpNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = OtpNotification::recent(48)
            ->orderByDesc('requested_at')
            ->paginate(20);

        $pendingCount = OtpNotification::pending()->count();

        return view('admin.otp-notifications.index', compact('notifications', 'pendingCount'));
    }

    public function markAsShared(Request $request, OtpNotification $notification)
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $notification->markAsShared(auth()->id(), $data['notes'] ?? null);

        return back()->with('status', 'Notificación marcada como compartida');
    }

    public function sendWhatsApp(OtpNotification $notification)
    {
        $message = urlencode("Hola! Tu código de acceso a HM INNOVA es: {$notification->code}");
        $url     = "https://wa.me/" . ($notification->whatsapp ?? '') . "?text={$message}";

        $notification->markAsShared(auth()->id(), 'Compartido por WhatsApp');

        return redirect()->away($url);
    }

    public function pendingCount()
    {
        return response()->json(['count' => OtpNotification::pending()->count()]);
    }

    public function latest()
    {
        $latest = OtpNotification::recent(48)
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->first();

        return response()->json([
            'latest_id'     => $latest?->id,
            'latest_status' => $latest?->status,
            'requested_at'  => $latest?->requested_at?->format('d/m/Y H:i'),
        ]);
    }
}
