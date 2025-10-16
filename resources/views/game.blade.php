<x-main-layout pageTitle="Countries and Capitals">


    <!-- options -->

 <div class="container">

        <x-question :country="$country" :currentQuestion="$current_question" :totalQuestions="$total_questions" />
        <div class="row">

            @foreach($answers as $answer)
                <x-answer :capital="$answer" />
            @endforeach

        </div>

    </div>

    <!-- cancel game -->
    <div class="text-center mt-5">
        <a href="{{ route('startGame') }}" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
    </div>
</x-main-layout>
