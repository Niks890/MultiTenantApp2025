<?php

namespace App\Services;

use App\Repositories\Contracts\SystemUserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Auth;

class SystemUserService
{
    protected $systemUserRepo;

    public function __construct(SystemUserRepositoryInterface $systemUserRepo)
    {
        $this->systemUserRepo = $systemUserRepo;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $avatarPath = null;
            if ($request->filled('cropped_avatar')) {
                $imageData = $request->input('cropped_avatar');
                $imageName = time() . '.png';
                $path = 'avatar_uploads/' . $imageName;
                $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                Storage::disk('public')->put($path, base64_decode($base64));
                $avatarPath = $path;
            }
            elseif ($request->hasFile('avatar_url')) {
                $avatarPath = $request->file('avatar_url')->store('avatar_uploads', 'public');
            }

            $user = $this->systemUserRepo->create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'display_name' => $request->display_name,
                'email' => $request->email,
                'avatar_url' => $avatarPath,
            ]);

            Log::channel('system_user')->info('Tạo người dùng thành công', [
                'ip' => $request->ip(),
                'route' => '/system-user.store',
                'data' => $user
            ]);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Tạo người dùng thất bại ' . $e->getMessage(), [
                'ip' => $request->ip(),
                'route' => '/system-user.store',
                'data' => $request->all(),
            ]);
            throw $e;
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $currentUser = Auth::user();
            $userToDelete = $this->systemUserRepo->find($id);
            if (!$userToDelete) {
                DB::rollBack();
                return false;
            }
            if ($userToDelete->is_super_admin || ($currentUser && $currentUser->id == $id)) {
                DB::rollBack();
                Log::channel('system_user')->warning('Hành động xóa bị chặn', [
                    'ip' => request()->ip(),
                    'route' => '/system-user.destroy',
                    'data' => [
                        'attempted_delete_id' => $id,
                        'by_user_id' => $currentUser->id ?? null,
                        'reason' => $userToDelete->is_super_admin
                            ? 'Không thể xóa super admin'
                            : 'Không thể tự xóa tài khoản của mình',
                    ],
                ]);
                return false;
            }
            $userDeleted = $this->systemUserRepo->delete($id);
            if (!$userDeleted) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            Log::channel('system_user')->info('Xóa người dùng thành công', [
                'ip' => request()->ip(),
                'route' => '/system-user.destroy',
                'data' => ['user_id_deleted' => $id],
            ]);
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Xóa người dùng thất bại ' . $e->getMessage(), [
                'ip' => request()->ip(),
                'route' => '/system-user.destroy',
                'data' => ['user_id_deleted' => $id],
            ]);
        }
    }


    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->systemUserRepo->find($id);
            if ($user->is_super_admin && array_key_exists('is_active', $data)) {
                unset($data['is_active']);
            }
            $updateData = [
                'username'     => $data['username'],
                'display_name' => $data['display_name'],
                'email'        => $data['email'],
            ];

            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }

            if (!empty($data['password'])) {
                $updateData['password'] = bcrypt($data['password']);
                $updateData['password_changed_at'] = now();
            }
            if (!empty($data['edit_cropped_avatar'])) {
                $imageData = $data['edit_cropped_avatar'];
                $imageName = 'edit_' . time() . '.png';
                $path = 'avatar_uploads/' . $imageName;
                $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                Storage::disk('public')->put($path, base64_decode($base64));

                if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }

                $updateData['avatar_url'] = $path;
            }
            elseif (isset($data['avatar_url']) && $data['avatar_url'] instanceof \Illuminate\Http\UploadedFile) {
                if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $newAvatarPath = $data['avatar_url']->store('avatar_uploads', 'public');
                $updateData['avatar_url'] = $newAvatarPath;
            }

            $this->systemUserRepo->update($id, $updateData);

            Log::channel('system_user')->info('Cập nhật người dùng thành công', [
                'ip' => request()->ip(),
                'route' => '/system-user.update',
                'data' => '{' . '"'. $id. '"' .'}'
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('system_user')->error('Cập nhật người dùng thất bại ' . $e->getMessage());
            throw $e;
        }
    }

    public function edit($id)
    {
        return $this->systemUserRepo->find($id);
    }


    public function searchSystemUser(string $keyword, string $status = '', $paginate = null)
    {
        $perPage = $paginate ?? request('paginate') ?? 10;
        return $this->systemUserRepo->search($keyword, $status, $perPage);
    }
}
