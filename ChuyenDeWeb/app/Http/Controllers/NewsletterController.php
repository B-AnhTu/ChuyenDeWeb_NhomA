<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Mail\NewsletterNotification;
use App\Mail\NewsletterVerification;
use App\Mail\SubscriptionConfirmation;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'name' => 'nullable|string|max:255'
        ]);

        try {
            $subscriber = Subscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'verification_token' => Str::random(32),
                'is_active' => false
            ]);

            // Gửi email xác nhận
            $verificationUrl = route('newsletter.verify', [
                'token' => $subscriber->verification_token
            ]);

            Mail::to($subscriber->email)
                ->queue(new NewsletterVerification($subscriber, $verificationUrl));

            return response()->json([
                'status' => 'success',
                'message' => 'Vui lòng kiểm tra email để xác nhận đăng ký!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }

    public function verify($token)
    {
        $subscriber = Subscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return redirect('/')->with('error', 'Link xác nhận không hợp lệ.');
        }

        $subscriber->update([
            'verified_at' => now(),
            'verification_token' => null,
            'is_active' => true
        ]);

        return redirect('/')->with('success', 'Xác nhận đăng ký thành công!');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string'
        ]);

        $subscribers = Subscriber::where('is_active', true)->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new NewsletterNotification($request->content));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi thông báo thành công!'
        ]);
    }
}
