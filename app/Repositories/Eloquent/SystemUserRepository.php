<?php

namespace App\Repositories\Eloquent;

use App\Models\SystemUser;
use App\Repositories\Contracts\SystemUserRepositoryInterface;


// Tạo class SystemUserRepository viết truy vấn eloquent tại đây
class SystemUserRepository implements SystemUserRepositoryInterface
{

    public function create(array $data)
    {
        return SystemUser::create($data);
    }


    public function delete($id)
    {
        $user = SystemUser::find($id);
        if (!$user || $user->is_super_admin) {
            return false;
        }
        return $user->update(['delete_flg' => 1, 'is_active' => 0]);
    }



    public function update($id, array $data)
    {
        return SystemUser::where('id', $id)->update($data);
    }

    public function find($id)
    {
        return SystemUser::find($id);
    }


    public function search(string $keyword, string $status = '', $perPage = null)
    {
        $perPage = request('paginate') ?? 10;
        $query = SystemUser::where('delete_flg', 0);

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'like', "%{$keyword}%")
                    ->orWhere('display_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        $appends = [
            'paginate' => $perPage,
            'keyword' => $keyword,
            'status' => $status,
        ];

        if ($perPage === 'all') {
            $total = $query->count();
            $systemUsers = $query->orderByDesc('id')
                ->paginate($total)
                ->appends($appends);
            $systemUsers->per_page = $total;
            $selectedPaginate = 'all';
        } else {
            $systemUsers = $query->orderByDesc('id')
                ->paginate((int)$perPage)
                ->appends($appends);
            $selectedPaginate = $perPage;
        }

        return [
            'systemUsers' => $systemUsers,
            'selectedPaginate' => $selectedPaginate,
        ];
    }
}
