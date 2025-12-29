<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginCode;
use App\Models\Tecnico;
use App\Models\User;
use App\Models\OtpNotification;
use App\Events\OtpRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function requestOtp(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
        ]);

        // Nombre por defecto a partir del correo (antes de la @)
        $defaultName = Str::before($data['email'], '@') ?: 'Técnico HM';

        // Usuario por email
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'              => $defaultName, // ⬅️ YA NO ES NULL
                'password'          => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        // Técnico asociado (crear o obtener)
        $tecnico = Tecnico::firstOrCreate(
            ['user_id' => $user->id],
            [
                'cedula'           => 'TEMP-' . $user->id, // luego se actualiza con el perfil
                'nombres'          => $user->name ?? 'Técnico',
                'apellidos'        => '',
                'correo'           => $user->email,
                'estado'           => 'activo',
                'perfil_completo'  => false,
            ]
        );

        // Actualizar teléfono si se proporciona
        if (!empty($data['phone'])) {
            $tecnico->update(['telefono' => $data['phone']]);
        }

        $code = (string) random_int(100000, 999999);

        // Mantener LoginCode (flujo actual)
        LoginCode::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Registrar en otp_notifications (nuevo)
        $notification = OtpNotification::create([
            'email'        => $user->email,
            'code'         => $code,
            'whatsapp'     => $data['whatsapp'] ?? null,
            'status'       => 'pending',
            'requested_at' => now(),
        ]);

        // Broadcast del evento (no rompe si broadcasting no está configurado)
        try {
            event(new OtpRequested($notification));
        } catch (\Throwable $e) {
            // Opcional: log si quieres
            // \Log::warning('Broadcast OTP failed: ' . $e->getMessage());
        }

        // Envío de correo (flujo actual)
        try {
            Mail::raw("Tu código de acceso a HM INNOVA es: {$code}", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Código de acceso HM INNOVA');
            });
        } catch (\Throwable $e) {
            // Aquí puedes loguear el error si quieres:
            // \Log::error('Error enviando mail OTP: '.$e->getMessage());
        }

        return response()->json([
            'message' => 'Si el correo es válido, se ha enviado un código de acceso.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $loginCode = LoginCode::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->orderByDesc('created_at')
            ->first();

        if (! $loginCode) {
            return response()->json(['message' => 'Código inválido'], 422);
        }

        if ($loginCode->isUsed()) {
            return response()->json(['message' => 'Código ya utilizado'], 422);
        }

        if ($loginCode->isExpired()) {
            return response()->json(['message' => 'Código expirado'], 422);
        }

        $loginCode->update(['used_at' => now()]);

        // Marcar notificación como verificada (nuevo, sin romper nada)
        try {
            OtpNotification::where('email', $user->email)
                ->where('code', $data['code'])
                ->where('requested_at', '>=', now()->subHours(24))
                ->orderByDesc('requested_at')
                ->first()
                ?->markAsVerified(); // si quieres notas, dime y lo ajusto en el modelo
        } catch (\Throwable $e) {
            // Opcional: log si quieres
            // \Log::warning('OtpNotification verify mark failed: ' . $e->getMessage());
        }

        $token = $user->createToken('mobile')->plainTextToken;

        $tecnico = $user->tecnico;

        return response()->json([
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'email' => $user->email,
                'name'  => $user->name,
            ],
            'tecnico' => $tecnico,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user'    => $user,
            'tecnico' => $user->tecnico,
        ]);
    }
}
