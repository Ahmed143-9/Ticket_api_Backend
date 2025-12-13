<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\FirstFaceAssignment;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProblemController extends Controller
{
    // Helper method for response format
    private function set_response($data = null, $httpCode = 200, $status = 'success', $messages = [])
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'messages' => $messages
        ], $httpCode);
    }

    // Get all problems
    public function getAllProblems(Request $request)
    {
        try {
            $problems = Problem::with(['assignedTo', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->set_response($problems, 200, 'success', ['Problems retrieved successfully']);
        } catch (\Exception $e) {
            return $this->set_response(null, 500, 'error', ['Failed to retrieve problems: ' . $e->getMessage()]);
        }
    }

    // Get problems assigned to a specific user
    public function getAssignedProblemsByUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|in:pending,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $query = Problem::with(['assignedTo', 'createdBy'])
                ->where('assigned_to', $request->user_id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $problems = $query->orderBy('created_at', 'desc')->get();
            return $this->set_response($problems, 200, 'success', ['Assigned problems retrieved successfully']);
        } catch (\Exception $e) {
            \Log::error('User assigned problems error: ' . $e->getMessage());
            return $this->set_response(null, 500, 'error', ['Failed to retrieve assigned problems: ' . $e->getMessage()]);
        }
    }

    // Create problem (images as URLs)
    public function createProblem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'statement' => 'required|string|min:3',
            'department' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'images' => 'nullable|array',
            'images.*' => 'string', // URLs only
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        DB::beginTransaction();
        try {
            $createdBy = $request->created_by;
            $creator = User::find($createdBy);
            if (!$creator) {
                DB::rollBack();
                return $this->set_response(null, 422, 'error', ['Creator user not found']);
            }

            $data = [
                'statement' => $request->statement,
                'department' => $request->department,
                'priority' => $request->priority,
                'description' => $request->description ?? '',
                'created_by' => $createdBy,
                'images' => $request->images ? json_encode($request->images) : null,
            ];

            // Assignment logic (manual / auto)
            $assignedUserId = null;
            $assignmentHistory = [];
            $status = 'pending';
            $isSelfAssignment = $request->filled('assigned_to') && $request->assigned_to == $createdBy;

            if ($request->filled('assigned_to') && !$isSelfAssignment) {
                $assignedUserId = $request->assigned_to;
                $assignedUser = User::find($assignedUserId);
                if (!$assignedUser) {
                    DB::rollBack();
                    return $this->set_response(null, 422, 'error', ['Assigned user not found']);
                }
                $assignmentHistory[] = [
                    'assigned_to' => $assignedUserId,
                    'assigned_to_name' => $assignedUser->name,
                    'assigned_by' => $createdBy,
                    'assigned_by_name' => $creator->name,
                    'assigned_at' => now()->toISOString(),
                    'type' => 'manual',
                    'reason' => 'Manual assignment by creator'
                ];
                $status = 'pending';
                $data['assigned_to'] = $assignedUserId;
            } elseif (!$isSelfAssignment) {
                $firstFace = FirstFaceAssignment::where('is_active', true)
                    ->where(function ($query) use ($request, $createdBy) {
                        $query->where('department', $request->department)
                              ->orWhereNull('department');
                    })
                    ->where('user_id', '!=', $createdBy)
                    ->orderByRaw("CASE WHEN department IS NULL THEN 1 ELSE 0 END")
                    ->orderBy('department')
                    ->first();

                if ($firstFace) {
                    $assignedUserId = $firstFace->user_id;
                    $firstFaceUser = User::find($firstFace->user_id);
                    $firstFaceAssignedBy = User::find($firstFace->assigned_by);
                    if ($firstFaceUser) {
                        $assignmentHistory[] = [
                            'assigned_to' => $assignedUserId,
                            'assigned_to_name' => $firstFaceUser->name,
                            'assigned_by' => $firstFace->assigned_by,
                            'assigned_by_name' => $firstFaceAssignedBy ? $firstFaceAssignedBy->name : 'System',
                            'assigned_at' => now()->toISOString(),
                            'type' => 'auto_first_face',
                            'first_face_assignment_id' => $firstFace->id,
                            'department' => $firstFace->department ?: 'all',
                            'reason' => 'Auto-assigned by First Face system'
                        ];
                        $status = 'pending';
                        $data['assigned_to'] = $assignedUserId;
                    }
                }
            } else {
                DB::rollBack();
                return $this->set_response(null, 422, 'error', ['Cannot assign problem to yourself']);
            }

            $data['status'] = $status;
            $data['assignment_history'] = json_encode($assignmentHistory);

            $problem = Problem::create($data);
            DB::commit();

            $message = 'Problem created successfully';
            if ($assignedUserId) {
                $user = User::find($assignedUserId);
                if ($request->filled('assigned_to')) {
                    $message .= " and manually assigned to {$user->name}";
                } else {
                    $message .= " and automatically assigned to {$user->name} (First Face)";
                }
            } else {
                $message .= " - will be assigned manually";
            }

            $problem->load(['assignedTo', 'createdBy']);
            return $this->set_response($problem, 201, 'success', [$message]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Create problem error: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            return $this->set_response(null, 500, 'error', ['Failed to create problem: ' . $e->getMessage()]);
        }
    }

    // Update problem (images as URLs)
    public function updateProblem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:problems,id',
            'statement' => 'sometimes|string|min:3',
            'department' => 'sometimes|string',
            'priority' => 'sometimes|in:Low,Medium,High',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:pending,in_progress,resolved',
            'assignment_history' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $problem = Problem::findOrFail($request->id);
            $updateData = $request->only(['statement', 'department', 'priority', 'description', 'status']);

            if ($request->has('assigned_to') && $request->assigned_to != $problem->assigned_to) {
                $updateData['assigned_to'] = $request->assigned_to;
                $history = json_decode($problem->assignment_history, true) ?? [];
                $history[] = [
                    'assigned_to' => $request->assigned_to,
                    'assigned_to_name' => User::find($request->assigned_to)->name ?? 'Unknown',
                    'assigned_by' => Auth::id() ?? $problem->created_by,
                    'assigned_by_name' => Auth::user()->name ?? User::find($problem->created_by)->name ?? 'System',
                    'assigned_at' => now()->toISOString(),
                    'type' => 'reassignment',
                    'reason' => $request->input('transfer_reason', 'Reassigned')
                ];
                $updateData['assignment_history'] = json_encode($history);
                if ($request->assigned_to) {
                    $updateData['status'] = 'in_progress';
                }
            }

            // Use frontend-uploaded image URLs
            if ($request->has('images')) {
                $updateData['images'] = json_encode($request->images);
            }

            if ($request->has('assignment_history')) {
                $updateData['assignment_history'] = json_encode($request->assignment_history);
            }

            $problem->update($updateData);
            $problem->load(['assignedTo', 'createdBy', 'comments.user']);

            return $this->set_response($problem, 200, 'success', ['Problem updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Update problem error: ' . $e->getMessage());
            return $this->set_response(null, 500, 'error', ['Failed to update problem: ' . $e->getMessage()]);
        }
    }

    // Get single problem
    public function getProblem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:problems,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $problem = Problem::with(['assignedTo', 'createdBy', 'comments.user'])->findOrFail($request->id);
            if (is_string($problem->assignment_history)) {
                $problem->assignment_history = json_decode($problem->assignment_history, true);
            }
            if (is_string($problem->images)) {
                $problem->images = json_decode($problem->images, true);
            }
            return $this->set_response($problem, 200, 'success', ['Problem retrieved successfully']);
        } catch (\Exception $e) {
            \Log::error('Get problem error: ' . $e->getMessage());
            return $this->set_response(null, 500, 'error', ['Failed to retrieve problem: ' . $e->getMessage()]);
        }
    }

    // Delete problem
    public function deleteProblem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:problems,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $problem = Problem::findOrFail($request->id);
            $user = Auth::user();
            if ($user && $user->role !== 'admin' && $problem->created_by !== $user->id) {
                return $this->set_response(null, 403, 'error', ['You do not have permission to delete this problem']);
            }
            $problem->delete();
            return $this->set_response(null, 200, 'success', ['Problem deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Delete problem error: ' . $e->getMessage());
            return $this->set_response(null, 500, 'error', ['Failed to delete problem: ' . $e->getMessage()]);
        }
    }

    // Add comment
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'problem_id' => 'required|exists:problems,id',
            'text' => 'required|string|min:1',
            'type' => 'nullable|string|in:general,solution,transfer,status_change',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $problem = Problem::findOrFail($request->problem_id);
            $user = Auth::user();
            \Log::info('Authenticated user:', ['user' => $user ]);
            if (!$user || ($user->id !== $problem->created_by && $user->id !== $problem->assigned_to && !in_array($user->role, ['admin', 'team_leader']))) {
                return $this->set_response(null, 403, 'error', ['You do not have permission to comment on this problem']);
            }
            $comment = Comment::create([
                'problem_id' => $request->problem_id,
                'user_id' => $user->id,
                'text' => $request->text,
                'type' => $request->input('type', 'general'),
            ]);
            $comment->load('user');
            return $this->set_response($comment, 201, 'success', ['Comment added successfully']);
        } catch (\Exception $e) {
            \Log::error('Add comment error: ' . $e->getMessage());
            return $this->set_response(null, 500, 'error', ['Failed to add comment: ' . $e->getMessage()]);
        }
    }
}
