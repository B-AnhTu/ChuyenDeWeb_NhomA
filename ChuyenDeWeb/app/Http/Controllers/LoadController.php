<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadController extends Controller
{
    // hiển thị trang index đầu tiên khi truy cập vào trang
    public function page($index = "index")
    {
        
        // Kiểm tra nếu là trang index
        if ($index === "index") {
            return $this->getStatistics();
        }


        // Kiểm tra xem view có tồn tại không trước khi render
        if (view()->exists($index)) {
            return view($index);
        }

        // Nếu view không tồn tại, bạn có thể trả về một view mặc định hoặc báo lỗi
        return view('404'); 

        return view($index);
    }

    private function getStatistics()
    {
        return view('index');
    }
}
