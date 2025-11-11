<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;

class GroupRepository  implements GroupRepositoryInterface
{

    public function create(array $data)
    {
        return Group::create($data);
    }

    public function getAll()
    {
        return Group::select('id', 'name')->where('delete_flg', 0)->get();
    }


    public function find($id)
    {
        return Group::find($id);
    }

    public function update($id, array $data)
    {
        return Group::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $group = $this->find($id);
        $group->delete_flg = 1;
        return $group->save();
    }


    public function search(string $keyword, $perPage = null)
    {
        $perPage = request('paginate') ?? 10;
        $query = Group::where('delete_flg', 0);

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }
        $appends = [
            'paginate' => $perPage,
            'keyword' => $keyword,
        ];
        if ($perPage === 'all') {
            $total = $query->count();
            $groups = $query->orderByDesc('id')
                ->paginate($total)
                ->appends($appends);
            $groups->per_page = $total;
            $selectedPaginate = 'all';
        } else {
            $groups = $query->orderByDesc('id')
                ->paginate((int)$perPage)
                ->appends($appends);
            $selectedPaginate = $perPage;
        }
        return [
            'groups' => $groups,
            'selectedPaginate' => $selectedPaginate,
        ];
    }
}
