<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Models\RegistrationPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query  = RegistrationPayment::with('user.upline')->latest();

        if (in_array($status, ['pending', 'paid', 'failed'])) {
            $query->where('status', $status);
        }

        $payments = $query->paginate(20)->withQueryString();
        $pendingCount = RegistrationPayment::where('status', 'pending')->count();

        return view('admin.registrations.index', compact('payments', 'status', 'pendingCount'));
    }

    public function approve(RegistrationPayment $registration)
    {
        if ($registration->status !== 'pending') {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'এই payment ইতিমধ্যে process করা হয়েছে।',
                ], 422);
            }

            return back()->with('error', 'এই payment ইতিমধ্যে process করা হয়েছে।');
        }

        DB::transaction(function () use ($registration) {
            // Activate user
            $registration->user->update([
                'is_active'    => true,
                'activated_at' => now(),
            ]);

            // Mark payment as paid
            $registration->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            // Distribute 24-gen commissions
            (new RegisterController())->distributeRegistrationCommission(
                $registration->user,
                $registration->id
            );
        });

        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment approved. User activated successfully.',
                'removeRow' => true,
                'rowId' => 'payment-row-' . $registration->id,
            ]);
        }

        return back()->with('success', 'Payment approved. User account activated and commissions distributed.');
    }

    public function reject(Request $request, RegistrationPayment $registration)
    {
        if ($registration->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'এই payment ইতিমধ্যে process করা হয়েছে।',
                ], 422);
            }

            return back()->with('error', 'এই payment ইতিমধ্যে process করা হয়েছে।');
        }

        $registration->update([
            'status'       => 'failed',
            'raw_response' => json_encode([
                'rejected_at' => now(),
                'reason'      => $request->input('reason', 'Admin rejected'),
            ]),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment rejected. User remains inactive.',
                'removeRow' => true,
                'rowId' => 'payment-row-' . $registration->id,
            ]);
        }

        return back()->with('success', 'Payment rejected. User remains inactive.');
    }
}
