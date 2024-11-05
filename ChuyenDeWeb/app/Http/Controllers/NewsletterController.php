<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        Log::info('Newsletter subscription request received', [
            'email' => $request->email,
            'name' => $request->name
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:newsletter_subscribers,email',
                'name' => 'nullable|string|max:255',
            ], [
                'email.required' => 'Vui lòng nhập email của bạn.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email này đã được đăng ký.',
                'name.string' => 'Tên không hợp lệ.',
                'name.max' => 'Tên không được vượt quá 255 ký tự.'
            ]);

            if ($validator->fails()) {
                Log::warning('Newsletter validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Lưu subscriber vào database
            $subscriber = NewsletterSubscriber::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => true
            ]);

            Log::info('Subscriber created successfully', ['id' => $subscriber->id]);

            // Gửi email trong try-catch riêng
            try {
                Log::info('Attempting to send welcome email', [
                    'to' => $request->email,
                    'name' => $request->name
                ]);

                Mail::to($request->email)
                    ->send(new NewsletterWelcome($request->name, $request->email));

                Log::info('Welcome email sent successfully');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cảm ơn bạn đã đăng ký nhận bản tin! Vui lòng kiểm tra email của bạn.'
                ]);
            } catch (\Exception $mailError) {
                Log::error('Error sending welcome email', [
                    'error' => $mailError->getMessage(),
                    'trace' => $mailError->getTraceAsString()
                ]);

                // Vẫn trả về success vì đã lưu được vào database
                return response()->json([
                    'status' => 'partial_success',
                    'message' => 'Đăng ký thành công! Tuy nhiên có lỗi khi gửi email xác nhận.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Newsletter subscription error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xử lý yêu cầu của bạn.'
            ], 500);
        }
    }

    // // Thêm route test riêng trong controller
    // public function testMail()
    // {
    //     try {
    //         $testEmail = 'mypyker@gmail.com'; // Email test của bạn

    //         Log::info('Testing mail sending to: ' . $testEmail);

    //         Mail::to($testEmail)
    //             ->send(new NewsletterWelcome('Test User', $testEmail));

    //         Log::info('Test email sent successfully');

    //         return 'Email sent successfully! Please check your inbox and spam folder.';
    //     } catch (\Exception $e) {
    //         Log::error('Test mail error: ' . $e->getMessage());
    //         return 'Error sending email: ' . $e->getMessage();
    //     }
    // }
}
