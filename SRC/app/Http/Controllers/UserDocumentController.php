<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Gate;

class UserDocumentController extends Controller
{
     public function edit(Loan $loan)
    {
        // Sử dụng Policy để phân quyền
        Gate::authorize('updateDocument', $loan);

        return view('user-documents.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        // Sử dụng Policy để phân quyền
        Gate::authorize('updateDocument', $loan);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // Cập nhật trực tiếp vào cột content của sách liên quan
        $loan->book->update([
            'content' => $validated['content'],
        ]);

        return redirect()->route('documents.edit', $loan)->with('success', 'Nội dung tài liệu đã được cập nhật!');
    }
}
