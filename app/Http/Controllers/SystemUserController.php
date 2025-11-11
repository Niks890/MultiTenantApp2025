<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemUserRequest;
use App\Http\Requests\UpdateSystemUserRequest;
use App\Models\SystemUser;
use App\Services\SystemUserService;
use Illuminate\Http\Request;

class SystemUserController extends Controller
{

    protected $systemUserService;

    public function __construct(SystemUserService $systemUserService)
    {
        $this->systemUserService = $systemUserService;
    }

    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword ?? '';
            $status = $request->status ?? '';
            $paginate = $request->paginate ?? 10;

            $result = $this->systemUserService->searchSystemUser($keyword, $status, $paginate);
            $systemUsers = $result['systemUsers'];
            $selectedPaginate = $result['selectedPaginate'];

            if ($request->ajax()) {
                $table = view('admin.system_user.partials.system-user_table', compact('systemUsers', 'keyword', 'status', 'selectedPaginate'))->render();
                $pagination = view('admin.system_user.partials.pagination', compact('systemUsers', 'selectedPaginate'))->render();
                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination,
                    'selected_paginate' => $selectedPaginate
                ]);
            }
            return view('admin.system_user.index', compact('systemUsers', 'keyword', 'status', 'selectedPaginate'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()]);
        }
    }

    public function store(SystemUserRequest $request)
    {
        if ($this->systemUserService->store($request)) {
            return response()->json(['success' => true, 'message' => 'Thêm người dùng thành công.']);
        }
        return response()->json(['success' => false, 'message' => 'Thêm người dùng thất bại.'], 422);
    }

    public function edit(SystemUser $systemUser)
    {
        $user = $this->systemUserService->edit($systemUser->id);
        return response()->json($user);
    }


    public function update(UpdateSystemUserRequest $request, SystemUser $systemUser)
    {
        $data = $request->only(['display_name', 'email', 'username', 'is_active', 'avatar_url', 'edit_cropped_avatar']);
        $passwordChanged = false;
        if ($request->has('password') && trim($request->password) !== '') {
            $data['password'] = $request->password;
            $passwordChanged = true;
        }

        if ($this->systemUserService->update($data, $systemUser->id)) {
            return response()->json(['success' => true, 'updated_user_id' => $systemUser->id, 'password_changed' => $passwordChanged, 'message' => 'Cập nhật người dùng thành công.', 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Cập nhật người dùng thất bại.'], 422);
    }



    public function destroy(SystemUser $systemUser)
    {
        if ($this->systemUserService->destroy($systemUser->id)) {
            return redirect()->route('system-user.index')
                ->with('success', 'Xóa người dùng thành công.');
        }
        return redirect()->route('system-user.index')
            ->with('error', 'Xóa người dùng thất bại.');
    }
}
