<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar (list) tagihan.
     */
    public function viewAny(User $user): bool
    {
        // Pengguna dengan access_level 1 (Admin) atau 2 (Officer) diizinkan melihat daftar seluruh tagihan.
        return in_array((int) $user->access_level, [1, 2], true);
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail sebuah model tagihan secara spesifik.
     */
    public function view(User $user, Bill $bill): bool
    {
        // Pengguna dengan access_level 1 (Admin) atau 2 (Officer) diizinkan melihat detail tagihan tertentu.
        return in_array((int) $user->access_level, [1, 2], true);
    }

    /**
     * Tentukan apakah pengguna dapat menambahkan data tagihan baru (create).
     */
    public function create(User $user): bool
    {
        // Pengguna dengan access_level 1 (Admin) atau 2 (Officer) diizinkan untuk membuat tagihan baru.
        return in_array((int) $user->access_level, [1, 2], true);
    }

    /**
     * Tentukan apakah pengguna dapat mengubah data model tagihan (update).
     */
    public function update(User $user, Bill $bill): bool
    {
        // Pengguna dengan access_level 1 (Admin) atau 2 (Officer) diizinkan untuk memperbarui data tagihan.
        return in_array((int) $user->access_level, [1, 2], true);
    }

    /**
     * Tentukan apakah pengguna dapat menghapus data model tagihan (delete).
     */
    public function delete(User $user, Bill $bill): bool
    {
        // Hanya pengguna dengan access_level 1 (Admin) yang diizinkan untuk menghapus data tagihan.
        // Officer (access_level 2) dilarang keras untuk menghapus data tagihan.
        return (int) $user->access_level === 1;
    }

    /**
     * Tentukan apakah pengguna dapat mengembalikan model tagihan yang telah dihapus soft-delete (restore).
     */
    public function restore(User $user, Bill $bill): bool
    {
        // Hanya Admin (access_level 1) yang diizinkan untuk mengembalikan tagihan yang terhapus.
        return (int) $user->access_level === 1;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model tagihan secara permanen (forceDelete).
     */
    public function forceDelete(User $user, Bill $bill): bool
    {
        // Hanya Admin (access_level 1) yang diizinkan untuk menghapus tagihan secara permanen dari database.
        return (int) $user->access_level === 1;
    }
}

