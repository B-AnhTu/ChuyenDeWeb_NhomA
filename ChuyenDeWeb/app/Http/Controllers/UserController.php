<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\SingleSpaceOnly;
use App\Rules\GmailOnly;
use App\Rules\NoSpace;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list()
    {
        $users = User::paginate(5);
        return view('userAdmin', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('userCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => ['required', 'email', 'max:50', 'unique:users,email', new GmailOnly, new NoSpace],
            'password' => ['required', 'min:8', 'max:20', new NoSpace],
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters],
        ],[
            'fullname.required' => 'Vui lòng nhập tên người dùng',
            'image.required' => 'Vui lòng nhập ảnh',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'email.max' => 'Email không được quá 50 ký tự',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        User::create([
            'fullname' => $data['fullname'],
            'image' => $data['image'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        return redirect()->route('userAdmin.index')->with('success', 'Thêm người dùng thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $user_id)
    {
        $user = User::findOrFail($user_id);
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userShow', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $user_id)
    {
        $user = User::findOrFail($user_id);
        return view('userUpdate', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => ['required', 'email', 'max:50', 'unique:users,email', new GmailOnly, new NoSpace],
            'password' => 'required|min:8|max:20',
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters],
        ],[
            'fullname.required' => 'Vui lòng nhập tên người dùng',
            'image.required' => 'Vui lòng nhập ảnh',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'email.max' => 'Email không được quá 50 ký tự',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ]);

        $user = User::findOrFail($id);

        if(!$user){
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Delete old image if exists
            if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
                unlink(public_path('img/profile-picture/' . $user->image));
            }

            // Cập nhật ảnh mới trong database
            $user->image = $filename;
        }

        $user->fullname = $request->input('fullname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        return redirect()->route('userAdmin.index')->with('success', 'Cập nhật người dùng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if(!$user){
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        // Delete image if exists
        if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
            unlink(public_path('img/profile-picture/' . $user->image));
        }
        try {
            $user->delete();
            return redirect()->route('userAdmin.index')->with('success', 'Xóa người dùng thành công');
        } catch (\Exception $e) {
            return redirect()->route('userAdmin.index')->with('error', 'Xóa người dùng không thành công');
        }
    }
}
