<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Todo $todo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null; // Lanjutkan ke pemeriksaan method di bawah
    }

    // User biasa hanya boleh membuat tugas untuk dirinya sendiri.
    // Karena Admin yang membuat tugas, kita izinkan Admin yang membuat
    public function create(User $user): bool
    {
        // Hanya Admin yang dapat membuat tugas untuk User lain, tapi kita izinkan semua user membuat untuk diri sendiri.
        return true;
    }

    // User biasa hanya boleh update jika itu tugasnya
    public function update(User $user, Todo $todo): bool
    {
        // User bisa update jika user adalah pemilik tugas
        return $user->id === $todo->user_id;
    }

    // User biasa hanya boleh delete jika itu tugasnya
    public function delete(User $user, Todo $todo): Response|bool
    {
        // Kondisi 1: User adalah pemilik tugas
        $isOwner = $user->id === $todo->user_id;

        // Kondisi 2: Tugas TIDAK dialokasikan oleh Admin (dibuat sendiri oleh user/tidak ada assigned_by_id)
        $isSelfCreated = is_null($todo->assigned_by_id);

        // User boleh menghapus jika dia pemilik tugas DAN tugas tersebut dibuat olehnya sendiri (bukan dari Admin)
        return $isOwner && $isSelfCreated
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan menghapus tugas yang dialokasikan oleh Admin.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Todo $todo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Todo $todo): bool
    {
        return false;
    }
}
