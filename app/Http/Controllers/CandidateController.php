<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('role', function($q) {
                $q->where('slug', 'seeker');
            })
            ->with('seekerProfile')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('seekerProfile', function($sq) use ($request) {
                      $sq->where('current_position', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('seekerProfile', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $candidates = $query->latest()->paginate(12);

        return view('candidates.index', compact('candidates'));
    }

    public function show($id)
    {
        $candidate = User::whereHas('role', function($q) {
                $q->where('slug', 'seeker');
            })
            ->with(['seekerProfile', 'educationRecords', 'experienceRecords', 'certificates'])
            ->findOrFail($id);

        return view('candidates.show', compact('candidate'));
    }
}
