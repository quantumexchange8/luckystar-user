<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReferralController extends Controller
{
    public function structureIndex()
    {
        $user = User::with('rank')
            ->withCount('directs')
            ->find(request()->user()->id);

        $user->directs->loadCount('directs');

        $data = [
            'key' => $user->id,
            'type' => 'person',
            'data' => [
                'username' => $user->username,
                'email' => $user->email,
                'rank' => $user->rank->rank_code,
                'directs_count' => $user->directs_count,
                'total_clients' => count($user->getChildrenIds()),
                'active_capital' => 0,
                'group_active_capital' => 0,
            ]
        ];

        if ($user->directs_count > 0) {
            foreach ($user->directs as $child) {
                $childNode = [
                    'key' => $child->id,
                    'type' => 'person',
                    'data' => [
                        'id' => $child->id,
                        'username' => $child->username,
                        'email' => $child->email,
                        'rank' => $child->rank->rank_code,
                        'directs_count' => $child->directs_count,
                        'total_clients' => count($child->getChildrenIds()),
                        'active_capital' => 0,
                        'group_active_capital' => 0,
                    ]
                ];

                // Add a loading node if the child has directs
                if ($child->directs_count > 0) {
                    $childNode['children'] = [
                        [
                            'key' => 'loading-' . $child->id,
                            'label' => 'loading'
                        ]
                    ];
                }

                $data['children'][] = $childNode;
            }
        }

        return Inertia::render('Referral/Listing/Structure/StructureListing', [
            'root' => $data,
        ]);
    }

    public function getStructureData(Request $request)
    {
        $authUser = Auth::user();
        $childrenIds = $authUser->getChildrenIds();
        $childrenIds[] = $authUser->id;

        $buildUserNode = function ($user) {
            // Ensure directs and rank are loaded
            $user->loadMissing(['rank']);

            // Prepare the user data
            $data = [
                'key' => $user->id,
                'type' => 'person',
                'data' => [
                    'username' => $user->username,
                    'email' => $user->email,
                    'rank' => $user->rank->rank_code,
                    'directs_count' => $user->directs_count, // This should now be properly set
                    'total_clients' => count($user->getChildrenIds()),
                    'active_capital' => 0,
                    'group_active_capital' => 0,
                ],
                'children' => []  // Initialize children as an empty array
            ];

            // If the user has directs (directs_count > 0), add a loading node
            if ($user->directs_count > 0) {
                $data['children'][] = [
                    'key' => 'loading-' . $user->id,
                    'label' => 'loading'
                ];
            }

            return $data;
        };

        if ($request->filled('search')) {
            // Handling search request
            $searchTerm = $request->input('search');
            $searchUser = User::with(['rank'])
                ->withCount('directs')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('username', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                ->whereIn('id', $childrenIds)
                ->first();

            if (!$searchUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $buildUserNode($searchUser)
            ]);
        }

        // Handle child ID request
        $childId = $request->input('child_id');
        $parent = User::withCount('directs')
            ->with(['rank'])
            ->findOrFail($childId);

        $parent->directs->loadCount('directs');

        // Build the nodes for each user (directs of parent)
        $directs = $parent->directs->map(function ($user) use ($buildUserNode) {
            return $buildUserNode($user);
        })->values();

        return response()->json([
            'success' => true,
            'data' => $directs,
        ]);
    }

    public function salesIndex()
    {
        return Inertia::render('Referral/Listing/Sales/SalesListing');
    }
}
