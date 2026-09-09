# tribepeer/sdk (PHP)

PHP client for TribePeer — a full digital campus in your Laravel, WordPress, or PHP app. You keep the interface. Enrollment, quizzes, AI, and learner tracking stay on TribePeer.

```bash
composer require tribepeer/sdk
```

Packagist: [https://packagist.org/packages/tribepeer/sdk](https://packagist.org/packages/tribepeer/sdk)

## What this unlocks

These are not four different products. Every language client talks to the same engine.

| Capability | What it does |
|---|---|
| **E-campus / classes** | Faculties, cohorts, join codes, branded portals |
| **Curriculum + e-library** | Modules, lessons, files, scheme of work |
| **Quizzes & CBT** | Generate, sit, auto-grade, results |
| **AI materials** | Lesson notes, assignments, curriculum from a PDF |
| **Ask TribeMate** | Tutor in the product, after hours or between classes |
| **Learner tracking** | Completions, submissions, per-student progress |
| **Cohort chat** | Class threads without a second chat stack |
| **Organisation training** | Staff onboarding on your own site |

Grading math, AI prompts, student caps, and billing never leave TribePeer. A clone of this repo without a live key cannot run a campus.

## Keys

1. [Register](https://www.tribepeer.com/register)
2. [Become a Tribe Owner](https://www.tribepeer.com/tribe-owner/apply)
3. [Issue keys](https://www.tribepeer.com/tribe-owner/credentials)
4. [API guide](https://www.tribepeer.com/institutions/docs)

```env
TP_CLIENT_ID=tp_id_…
TP_CLIENT_SECRET=tp_sec_…
```

The secret stays on the server.

```php
$tp = new TribePeer\Client(
    clientId: env('TP_CLIENT_ID'),
    clientSecret: env('TP_CLIENT_SECRET'),
);

$classes = $tp->tribes()->list();

// AI: set materials, then a quiz
$notes = $tp->ai()->material(['topic' => 'Photosynthesis', 'level' => 'SS2']);
$quiz  = $tp->ai()->quiz(['material' => $notes, 'questions' => 10]);

// Learners sit the quiz; you track them
$tp->campus()->submitQuiz($tribe, $material, ['answers' => $answers]);
$roster = $tp->campus()->students($tribe);
$done   = $tp->campus()->complete($tribe, $material);
```

PHP 8.1+. MIT.
