<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WhatsAppAuthController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Request a verification code
     */
    public function requestCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Send verification code
        $this->whatsappService->sendVerificationCode($request->whatsapp);

        return response()->json(['message' => 'Código de verificação enviado com sucesso!']);
    }

    /**
     * Verify code and login or register user
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify the code
        if (!$this->whatsappService->verifyCode($request->whatsapp, $request->code)) {
            return response()->json(['error' => 'Código inválido ou expirado'], 422);
        }

        // Find or create member
        $member = Member::firstOrCreate(
            ['whatsapp' => $request->whatsapp],
            [
                'name' => 'Usuário ' . substr($request->whatsapp, -4),
                'password' => Hash::make(str_random(20)),
            ]
        );

        // Login
        Auth::guard('member')->login($member);

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'redirect' => route('member.dashboard')
        ]);
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . Auth::guard('member')->id(),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $member = Auth::guard('member')->user();
        $member->update($request->only(['name', 'email']));

        return response()->json(['message' => 'Perfil atualizado com sucesso!']);
    }
}
