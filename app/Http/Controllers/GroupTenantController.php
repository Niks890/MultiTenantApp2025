<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupTenantController extends Controller
{

    protected $groupService;
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }


    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword ?? '';
            $paginate = $request->paginate ?? 10;
            $result = $this->groupService->searchGroup($keyword, $paginate);
            $groups = $result['groups'];
            $selectedPaginate = $result['selectedPaginate'];
            if ($request->ajax()) {
                $table = view(
                    'admin.group.partials.group-table',
                    compact('groups', 'keyword', 'selectedPaginate')
                )->render();
                $pagination = view('admin.group.partials.pagination', compact('groups', 'selectedPaginate'))->render();

                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination,
                    'selected_paginate' => $selectedPaginate
                ]);
            }
            return view('admin.group.index', compact('groups', 'keyword', 'selectedPaginate'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()], 500);
            }
        }
        return back()->withErrors(['error' => 'Lấy danh sách thất bại: ' . $e->getMessage()]);
    }

    public function create()
    {
        return view('admin.group.create');
    }

    public function store(GroupRequest $request)
    {
        if ($this->groupService->store($request->all())) {
            session()->flash('success', 'Thêm nhóm cửa hiệu thành công.');
            return response()->json(['success' => true, 'message' => 'Thêm nhóm cửa hiệu thành công.', 'data' => $request->all()]);
        }
        session()->flash('error', 'Thêm nhóm cửa hiệu thất bại.');
        return response()->json(['success' => false, 'message' => 'Thêm nhóm cửa hiệu thất bại.'], 422);
    }


    public function edit(Group $group)
    {
        if ($group->delete_flg == 1) {
            abort(404);
        }
        $group = $this->groupService->edit($group->id);
        return view('admin.group.edit', compact('group'));
    }


    public function update(UpdateGroupRequest $request, Group $group)
    {
        if ($this->groupService->update($request->all(), $group->id)) {
            session()->flash('success', 'Cập nhật thông tin nhóm cửa hiệu thành công.');
            return response()->json(['success' => true, 'message' => 'Cập nhật thông tin nhóm cửa hiệu thành công.', 'data' => $request->all()]);
        }
        session()->flash('error', 'Cập nhật thông tin nhóm cửa hiệu thất bại.');
        return response()->json(['success' => false, 'message' => 'Cập nhật thông tin nhóm cửa hiệu thất bại.'], 422);
    }


    public function destroy(Group $group)
    {
        if ($this->groupService->destroy($group->id)) {
            session()->flash('success', 'Xóa nhóm cửa hiệu thành công.');
            return response()->json(['success' => true, 'message' => 'Xóa nhóm cửa hiệu thành công.']);
        }
        session()->flash('error', 'Xóa nhóm cửa hiệu thất bại, do nhóm đang có cửa hiệu hoạt động.');
        return response()->json(['success' => false, 'message' => 'Xóa nhóm cửa hiệu thất bại.'], 422);
    }
}
