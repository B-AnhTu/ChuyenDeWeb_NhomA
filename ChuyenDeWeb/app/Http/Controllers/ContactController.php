<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        // Validate dữ liệu
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'message' => 'required|string|min:10'
            ],
            [
                'name.required' => 'Vui lòng nhập họ tên',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Email không hợp lệ',
                'message.required' => 'Vui lòng nhập tin nhắn'
            ]
        );

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'userMessage' => $request->input('message')
        ];

        try {
            Mail::to('22211tt3673@mail.tdc.edu.vn')->send(new ContactMail($data));
            return response()->json([
                'success' => true,
                'message' => 'Gửi tin nhắn thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
}
