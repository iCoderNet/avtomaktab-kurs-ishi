<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $upcomingExams = Exam::where('status', 'upcoming')
            ->whereDoesntHave('results', fn($q) => $q->where('student_id', $student->id))
            ->get();
        $myResults = ExamResult::with('exam')->where('student_id', $student->id)->latest()->get();
        return view('student.exams.index', compact('upcomingExams', 'myResults'));
    }

    public function take(Exam $exam)
    {
        $student = auth()->user()->student;
        if ($exam->status !== 'upcoming') abort(403, "Bu imtihon hozir mavjud emas.");
        $questions = $exam->questions()->orderBy('order')->get();
        return view('student.exams.take', compact('exam', 'questions'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $student = auth()->user()->student;
        $questions = $exam->questions;
        $answers = $request->answers ?? [];
        $correctCount = 0;

        foreach ($questions as $q) {
            if (isset($answers[$q->id]) && $answers[$q->id] === $q->correct_answer) {
                $correctCount++;
            }
        }

        $score = $questions->count() > 0
            ? (int)(($correctCount / $questions->count()) * 100)
            : 0;

        ExamResult::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'score' => $score,
            'correct_answers' => $correctCount,
            'total_questions' => $questions->count(),
            'result' => $score >= $exam->passing_score ? 'passed' : 'failed',
            'answers' => $answers,
            'started_at' => session("exam_start_{$exam->id}"),
            'finished_at' => now(),
        ]);

        return redirect()->route('student.exams.index')
            ->with('success', "Imtihon tugatildi! Natija: {$score}% — " . ($score >= $exam->passing_score ? "O'tdingiz 🎉" : "O'tmagansiz 😔"));
    }
}
