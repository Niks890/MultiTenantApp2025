<?php

namespace App\Services;

use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }


    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $group = $this->groupRepository->create([
                'name' => $data['group_name'],
                'description' => $data['group_description'],
            ]);
            DB::commit();
            Log::channel('system_user')->info('Thêm mới nhóm cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/group.store',
                'data' => json_encode($group->toArray())
            ]);
            return $group;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Tạo nhóm cửa hiệu thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/group.store',
                'data' => json_encode($data)
            ]);
            throw $e;
        }
    }

    public function edit($id)
    {
        return $this->groupRepository->find($id);
    }

    public function getAll(){
        return $this->groupRepository->getAll();
    }


    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $group = $this->groupRepository->update($id, [
                'name' => $data['group_name'],
                'description' => $data['group_description'],
            ]);
            DB::commit();
            Log::channel('system_user')->info('Sửa thông tin nhóm cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/group.update',
                'data' => json_encode($data)
            ]);
            return $group;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Sửa thông tin nhóm cửa hiệu thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/group.update',
                'data' => json_encode($data)
            ]);
            throw $e;
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $groupDeleted = $this->groupRepository->delete($id);
            if (!$groupDeleted) {
                DB::rollBack();
                Log::channel('system_user')->error('Nhóm đang có cửa hiệu hoạt động, không thể xoá!', [
                    'ip' => request()->ip(),
                    'route' => '/group.destroy',
                    'data' => $id
                ]);
                return false;
            }
            DB::commit();
            Log::channel('system_user')->info('Xóa nhóm cửa hiệu thành công', [
                'ip' => request()->ip(),
                'route' => '/group.destroy',
                'data' => $id
            ]);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Xóa nhóm cửa hiệu thất bại' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/group.destroy',
                'data' => $id
            ]);
        }
    }


    public function searchGroup(string $keyword, $paginate = null)
    {
        $perPage = $paginate ?? request('paginate') ?? 10;
        return $this->groupRepository->search($keyword, $perPage);
    }
}
