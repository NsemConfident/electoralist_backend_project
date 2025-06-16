<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VoterController extends Controller
{
    public function status(Request $request)
    {
        $user = $request->user();

        $voter = Voter::where('user_id', $user->id)->first();
        $vote = $voter ? Vote::where('voter_id', $voter->id)->first() : null;

        return response()->json([
            'success' => true,
            'data' => [
                'is_registered' => $voter !== null,
                'has_voted' => $vote !== null,
                'voted_candidate_id' => $vote ? $vote->candidate_id : null,
            ]
        ]);
    }

    public function register(Request $request)
    {
        $user = $request->user();

        // Check if already registered
        if (Voter::where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already registered as a voter'
            ], 400);
        }

        $request->validate([
            'biometric_token' => 'required|string|max:255|unique:voters,biometric_token'
        ]);

        // Create voter record
        $voter = Voter::create([
            'user_id' => $user->id,
            'biometric_token' => $request->biometric_token,
            'device_id' => $request->header('X-Device-Id') ?? Str::random(16),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered as voter'
        ]);
    }

    public function vote(Request $request)
    {
        $user = $request->user();

        // Check if registered
        $voter = Voter::where('user_id', $user->id)->first();
        if (!$voter) {
            return response()->json([
                'success' => false,
                'message' => 'You are not registered as a voter'
            ], 403);
        }

        // Validate request
        $request->validate([
            'candidate_id' => 'required|exists:presidential_candidates,id',
            'biometric_token' => 'required|string'
        ]);

        // Verify biometric token matches
        if ($voter->biometric_token !== $request->biometric_token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid biometric token'
            ], 403);
        }

        // Check if already voted and update, otherwise create new
        $vote = Vote::updateOrCreate(
            ['voter_id' => $voter->id],
            ['candidate_id' => $request->candidate_id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Vote cast successfully'
        ]);
    }
}
