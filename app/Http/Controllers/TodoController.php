<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Tentukan query dasar berdasarkan role
        $baseQuery = $user->isAdmin() ? Todo::query() : $user->todosAssigned();

        // Ambil data user (hanya jika Admin)
        $users = $user->isAdmin() ? User::where('role', 'user')->get() : collect();

        $todos = $baseQuery
            ->with(['user', 'assignedBy'])

            // 1. Tugas Belum Selesai (NULL) selalu di atas Tugas Selesai
            // completed_at ASC akan menempatkan NULL (belum selesai) di atas tanggal (selesai)
            ->orderBy('completed_at', 'asc')

            // 2. Prioritas Deadline: Tugas ber-deadline di atas Tugas Tanpa Deadline.
            //    Di antara yang ber-deadline, urutkan tanggal terdekat dulu (asc).
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END, deadline asc')

            // 3. Urutkan Sekunder: Tugas terbaru di atas
            ->orderBy('created_at', 'desc')

            ->paginate(10);

        return view('dashboard', compact('todos', 'users'));
    }

    public function store(Request $request)
    {
        $isAdmin = $request->user()->isAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'deadline' => 'nullable|date|after_or_equal:today',
            'deadline' => [
                $isAdmin ? 'required' : 'nullable',
                'date',
                'after_or_equal:today',
            ],
            // Hanya admin yang bisa menentukan user_id
            'user_id' => [
                $isAdmin ? 'required' : 'nullable',
                'exists:users,id'
            ],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
        ];

        // Logic kepemilikan
        if ($isAdmin) {
            // Admin mengalokasikan ke user_id tertentu dan dicatat siapa yang membuat
            $data['user_id'] = $validated['user_id'];
            $data['assigned_by_id'] = request()->user()->id;
        } else {
            // User biasa membuat tugas untuk dirinya sendiri
            $data['user_id'] = request()->user()->id;
            $data['assigned_by_id'] = null;
        }

        Todo::create($data);

        return back()->with('success', 'Tugas berhasil ' . (request()->user()->isAdmin() ? 'dialokasikan' : 'ditambahkan') . '.');
    }

    // update() dan destroy() tetap sama (sudah ada otorisasi dari Policy)
    public function update(Todo $todo)
    {
        Gate::authorize('update', $todo);

        $todo->update([
            'completed_at' => $todo->completed_at ? null : now(),
        ]);

        $status = $todo->completed_at ? 'selesai' : 'belum selesai';
        return back()->with('success', "Status tugas diubah menjadi $status.");
    }

    public function destroy(Todo $todo)
    {
        Gate::authorize('delete', $todo);

        $todo->delete();
        return back()->with('success', 'Tugas dihapus.');
    }

    public function progress(Request $request)
    {
        $user = $request->user();

        // Gunakan Closure untuk mendapat Query Builder yang fresh setiap kali dipanggil (Mencegah Query Scope Bertumpuk)
        $baseQuery = fn() => $user->isAdmin() ? Todo::query() : $user->todosAssigned();

        // 1. Ambil data dasar & Penghitungan Ringkasan

        $totalTodos = $baseQuery()->count();
        $completedTodos = $baseQuery()->whereNotNull('completed_at')->count();

        // Hitung Overdue: Belum selesai DAN deadline sudah lewat
        $overdueTodos = $baseQuery()->whereNull('completed_at')
            ->where('deadline', '<', now())
            ->count();

        // Hitung Pending Murni (Tugas Aktif yang Aman): Belum selesai DAN deadline belum tiba / tidak ada
        $pendingTodos = $baseQuery()->whereNull('completed_at')
            ->where(function ($query) {
                $query->whereNull('deadline')
                    ->orWhere('deadline', '>', now());
            })->count();

        // 2. Tentukan status yang ingin dilihat (Default: Pending)
        $status = $request->query('status', 'pending');

        // 3. Ambil data detail (Filter)
        $detailQuery = $baseQuery();

        if ($status === 'completed') {
            $detailQuery->whereNotNull('completed_at');
        } elseif ($status === 'pending') {
            // Filter Pending Murni
            $detailQuery->whereNull('completed_at')
                ->where(function ($query) {
                    $query->whereNull('deadline')
                        ->orWhere('deadline', '>', now());
                });
        } elseif ($status === 'overdue') {
            // Filter Overdue
            $detailQuery->whereNull('completed_at')
                ->where('deadline', '<', now());
        } elseif ($status === 'all') {
            // Tidak perlu filter tambahan
        }

        // Tampilkan 10 tugas per halaman untuk detail
        $detailTodos = $detailQuery
            ->with(['user', 'assignedBy'])

            // Pengurutan sama dengan index: Belum Selesai > Overdue/Deadline > Selesai
            ->orderBy('completed_at', 'asc')
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END, deadline asc')
            ->orderBy('created_at', 'desc')

            ->paginate(10)
            ->withQueryString();

        // Data yang dikirim ke view
        $progressData = [
            'total' => $totalTodos,
            'completed' => $completedTodos,
            'pending' => $pendingTodos,
            'overdue' => $overdueTodos,
            'status' => $status
        ];

        return view('progress', compact('progressData', 'detailTodos'));
    }
}
