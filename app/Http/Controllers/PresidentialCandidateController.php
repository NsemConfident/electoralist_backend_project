<?php

namespace App\Http\Controllers;

use App\Models\PresidentialCandidate;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class PresidentialCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canditates = PresidentialCandidate::all();
        if ($canditates) {
            return response()->json([
                'data' => $canditates
            ], 200);
        } else {
            return response()->json([
                'message' => 'No Candidates availabel',
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string',
            'political_party' => 'nullable|string',
            'national_id' => 'required|string|unique:presidential_candidates,national_id',
            'region' => 'required|string',
            'email' => 'required|email|unique:presidential_candidates,email',
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);
        if ($request->hasFile('photo')) {
            $validate['photo'] = $request->file('photo')->store('presidential_candidates', 'public');
        }
        $candidate = PresidentialCandidate::create($validate);
        if ($candidate) {
            return response()->json([
                'message' => 'Presidential candidate created successfully',
                'data' => $candidate
            ], 201);
        } else {
            return response()->json([
                'message' => 'Failed to create presidential candidate',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PresidentialCandidate $presidentialCandidate)
    {
        return response()->json($presidentialCandidate);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresidentialCandidate $presidentialCandidate)
    {
        return response()->json($presidentialCandidate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresidentialCandidate $presidentialCandidate)
    {
        $validate = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'place_of_birth' => 'sometimes|string|max:255',
            'political_party' => 'nullable|string|max:255',
            'national_id' => 'sometimes|string|unique:presidential_candidates,national_id,' . $presidentialCandidate->id,
            'region' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:presidential_candidates,email,' . $presidentialCandidate->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validate['photo'] = $request->file('photo')->store('presidential_candidates', 'public');
        }

        $presidentialCandidate->update($validate);
        if ($presidentialCandidate) {
            return response()->json([
                'message' => 'Candidate updated successfully.',
                'data' => $presidentialCandidate
            ], 201);
        } else {
            return response()->json([
                'message' => 'Candidate updated Failed.',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresidentialCandidate $presidentialCandidate)
    {
        $presidentialCandidate->delete();
        return response()->json([
            'message' => 'Candidate deleted successfully',
            'data' => $presidentialCandidate
        ], 200);
    }
}
