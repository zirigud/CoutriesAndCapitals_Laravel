<x-main-layout :pageTitle="'Home'">

    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-4 text-center">
                <form action="{{ route('prepareGame') }}" method="post">
                    @csrf
                    <div class="mt-3 mb-5">
                        <label class="form-label display-6 mb-3" for="total_questions">Número de perguntas:</label>
                        <input type="number" class="form-control form-control-lg text-center" id="total_questions" name="total_questions" min="3" max="30" value="{{ old('total_questions', 10) }}" required>
                        @error('total_questions')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary px-5" type="submit">INICIAR QUESTIONÁRIO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-main-layout>
