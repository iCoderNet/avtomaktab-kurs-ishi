<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::withCount('results')->latest()->paginate(15);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:theory,practical,stop',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:10',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $exam = Exam::create([
            ...$request->except(['_token', 'questions']),
            'created_by' => auth()->id(),
        ]);

        // Savollari bilan birga saqlash
        if ($request->questions) {
            foreach ($request->questions as $q) {
                if (!empty($q['question'])) {
                    $exam->questions()->create([
                        'question' => $q['question'],
                        'options' => $q['options'] ?? [],
                        'correct_answer' => $q['correct_answer'] ?? 'A',
                        'order' => $q['order'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.exams.index')->with('success', "Imtihon yaratildi!");
    }

    public function show(Exam $exam)
    {
        $exam->load('questions', 'results.student.user');
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $exam->load('questions');
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $exam->update($request->except(['_token','_method']));
        return redirect()->route('admin.exams.show', $exam)->with('success', "Imtihon yangilandi!");
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', "Imtihon o'chirildi.");
    }

    public function results(Exam $exam)
    {
        $exam->load('results.student.user');
        return view('admin.exams.results', compact('exam'));
    }
}
