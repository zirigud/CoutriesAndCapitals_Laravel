@props(['country', 'currentQuestion' => 0, 'totalQuestions' => 0])

<div class="border border-primary rounded-5 p-3 text-center fs-3 mb-3">
    Pergunta: <span class="text-info fw-bolder">{{ $currentQuestion + 1 }} / {{ $totalQuestions }}</span>
</div>

<div class="text-center fs-3 mb-3">
    QUAL É A CAPITAL DE <span class="text-primary fw-bold">{{ strtoupper($country) }}</span> ?
</div>
