<?php
// app/Http/Controllers/FirstFaceAssignmentController.php

namespace App\Http\Controllers;

use App\Models\FirstFaceAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FirstFaceAssignmentController extends Controller
{
    protected function set_response($data, $code, $status, $messages = [])
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'messages' => is_array($messages) ? $messages : [$messages],
        ], $code);
    }

    public function getAllAssignments(Request $request)
    {
        try {
            $assignments = FirstFaceAssignment::with(['user', 'assignedByUser'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return $this->set_response($assignments, 200, 'success', ['First Face assignments retrieved successfully']);
            
        } catch (\Exception $e) {
            return $this->set_response(null, 500, 'error', ['Failed to retrieve assignments: ' . $e->getMessage()]);
        }
    }

    public function createAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'department' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            if ($user->status != 1) {
                return $this->set_response(null, 422, 'error', ['Cannot assign inactive user as First Face']);
            }

            if ($request->department) {
                $existing = FirstFaceAssignment::where('department', $request->department)
                    ->where('is_active', true)
                    ->exists();
                    
                if ($existing) {
                    return $this->set_response(null, 422, 'error', ['There is already an active First Face assignment for this department']);
                }
            } else {
                $existing = FirstFaceAssignment::whereNull('department')
                    ->where('is_active', true)
                    ->exists();
                    
                if ($existing) {
                    return $this->set_response(null, 422, 'error', ['There is already an active First Face assignment for all departments']);
                }
            }

            $data = $request->only(['user_id', 'department', 'is_active']);
            $data['assigned_by'] = auth()->id() ?? 1;
            $data['assigned_at'] = now();
            $data['is_active'] = $request->boolean('is_active') ?? true;

            $assignment = FirstFaceAssignment::create($data);

            DB::commit();
            
            $departmentLabel = $request->department ?: 'All Departments';
            return $this->set_response($assignment, 201, 'success', ["First Face assignment created for {$departmentLabel}"]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->set_response(null, 500, 'error', ['Failed to create assignment: ' . $e->getMessage()]);
        }
    }

    public function updateAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:first_face_assignments,id',
            'user_id' => 'sometimes|exists:users,id',
            'department' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $assignment = FirstFaceAssignment::findOrFail($request->id);
            
            $updateData = $request->only(['user_id', 'department', 'is_active']);
            
            if (($request->has('is_active') && $request->boolean('is_active')) || 
                (!isset($updateData['is_active']) && $assignment->is_active)) {
                
                $department = $request->department ?? $assignment->department;
                
                $conflict = FirstFaceAssignment::where('id', '!=', $assignment->id)
                    ->where('is_active', true);
                    
                if ($department) {
                    $conflict->where('department', $department);
                } else {
                    $conflict->whereNull('department');
                }
                
                if ($conflict->exists()) {
                    return $this->set_response(null, 422, 'error', ['There is already an active First Face assignment for this department']);
                }
            }
            
            $assignment->update($updateData);
            
            return $this->set_response($assignment, 200, 'success', ['First Face assignment updated successfully']);
            
        } catch (\Exception $e) {
            return $this->set_response(null, 500, 'error', ['Failed to update assignment: ' . $e->getMessage()]);
        }
    }

    public function deleteAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:first_face_assignments,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        try {
            $assignment = FirstFaceAssignment::findOrFail($request->id);
            $assignment->delete();
            
            return $this->set_response(null, 200, 'success', ['First Face assignment deleted successfully']);
            
        } catch (\Exception $e) {
            return $this->set_response(null, 500, 'error', ['Failed to delete assignment: ' . $e->getMessage()]);
        }
    }

    public function getActiveAssignments(Request $request)
    {
        try {
            $assignments = FirstFaceAssignment::active()
                ->with(['user', 'assignedByUser'])
                ->orderByRaw("CASE WHEN department IS NULL THEN 1 ELSE 0 END")
                ->orderBy('department')
                ->get();
                
            return $this->set_response($assignments, 200, 'success', ['Active First Face assignments retrieved successfully']);
            
        } catch (\Exception $e) {
            return $this->set_response(null, 500, 'error', ['Failed to retrieve active assignments: ' . $e->getMessage()]);
        }
    }
}