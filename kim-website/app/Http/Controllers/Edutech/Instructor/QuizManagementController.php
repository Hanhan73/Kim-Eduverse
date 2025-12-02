<?php

namespace App\Http\Controllers\Edutech\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizManagementController extends Controller
{
    /**
     * Show all quizzes for instructor's courses
     */
    public function index()
    {
        $instructorId = session('edutech_user_id');
        
        $quizzes = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->with(['course', 'module', 'questions'])
        ->withCount('questions')
        ->latest()
        ->get();

        return view('edutech.instructor.quiz.index', compact('quizzes'));
    }

    /**
     * Show create quiz form
     */
    public function create(Request $request)
    {
        $instructorId = session('edutech_user_id');
        
        $courses = Course::where('instructor_id', $instructorId)
            ->where('is_published', true)
            ->with('modules')
            ->get();

        $selectedCourseId = $request->get('course_id');
        $selectedModuleId = $request->get('module_id');
        $quizType = $request->get('type', 'module_quiz'); // default module_quiz

        return view('edutech.instructor.quiz.create', compact('courses', 'selectedCourseId', 'selectedModuleId', 'quizType'));
    }

    /**
     * Store new quiz
     */
    public function store(Request $request)
    {
        $instructorId = session('edutech_user_id');

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:pre_test,post_test,module_quiz',
            'module_id' => 'required_if:type,module_quiz|nullable|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $course = Course::where('id', $request->course_id)
            ->where('instructor_id', $instructorId)
            ->firstOrFail();

        // Check if pre/post test already exists
        if ($request->type === 'pre_test' || $request->type === 'post_test') {
            $existingQuiz = Quiz::where('course_id', $course->id)
                ->where('type', $request->type)
                ->first();

            if ($existingQuiz) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This course already has a ' . str_replace('_', '-', $request->type));
            }
        }

        // Check if module quiz already exists for this module
        if ($request->type === 'module_quiz' && $request->module_id) {
            $existingModuleQuiz = Quiz::where('module_id', $request->module_id)
                ->where('type', 'module_quiz')
                ->first();

            if ($existingModuleQuiz) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This module already has a quiz');
            }
        }

        $quizData = [
            'course_id' => $request->course_id,
            'module_id' => $request->type === 'module_quiz' ? $request->module_id : null,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'passing_score' => $request->passing_score,
            'duration_minutes' => $request->duration_minutes,
            'max_attempts' => $request->max_attempts,
            'is_active' => true,
        ];

        $quiz = Quiz::create($quizData);

        return redirect()
            ->route('edutech.instructor.quiz.edit', $quiz->id)
            ->with('success', 'Quiz created! Now add questions.');
    }

    /**
     * Show edit quiz + add questions
     */
    public function edit($id)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::with(['course', 'module', 'questions' => function($query) {
            $query->orderBy('order');
        }])
        ->whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->findOrFail($id);

        return view('edutech.instructor.quiz.edit', compact('quiz'));
    }

    /**
     * Update quiz
     */
    public function update(Request $request, $id)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $quiz->update($request->only([
            'title', 'description', 'passing_score',
            'duration_minutes', 'max_attempts',
        ]));

        return redirect()->back()->with('success', 'Quiz updated!');
    }

    /**
     * Delete quiz
     */
    public function destroy($id)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($id);

        $quiz->delete();

        return redirect()
            ->route('edutech.instructor.quiz.index')
            ->with('success', 'Quiz deleted!');
    }

    /**
     * Toggle active
     */
    public function toggleActive($id)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($id);

        $quiz->update(['is_active' => !$quiz->is_active]);

        return redirect()->back()->with('success', 'Quiz status updated!');
    }

    // QUESTION MANAGEMENT

    public function storeQuestion(Request $request, $quizId)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($quizId);

        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'correct_answer' => 'required',
            'points' => 'required|integer|min:1',
        ]);

        $nextOrder = QuizQuestion::where('quiz_id', $quiz->id)->max('order') + 1;

        $options = null;
        if ($request->type === 'multiple_choice') {
            $options = array_values(array_filter($request->options));
        } elseif ($request->type === 'true_false') {
            $options = ['True', 'False'];
        }

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
            'order' => $nextOrder,
        ]);

        return redirect()->back()->with('success', 'Question added!');
    }

    public function updateQuestion(Request $request, $quizId, $questionId)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($quizId);

        $question = QuizQuestion::where('quiz_id', $quiz->id)->findOrFail($questionId);

        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'correct_answer' => 'required',
            'points' => 'required|integer|min:1',
        ]);

        $options = null;
        if ($request->type === 'multiple_choice') {
            $options = array_values(array_filter($request->options));
        } elseif ($request->type === 'true_false') {
            $options = ['True', 'False'];
        }

        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
        ]);

        return redirect()->back()->with('success', 'Question updated!');
    }

    public function destroyQuestion($quizId, $questionId)
    {
        $instructorId = session('edutech_user_id');

        $quiz = Quiz::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->findOrFail($quizId);

        $question = QuizQuestion::where('quiz_id', $quiz->id)->findOrFail($questionId);
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted!');
    }
}