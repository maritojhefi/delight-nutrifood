@props(['step','maxSteps'])

<div class="progress-pills d-flex justify-content-center gap-2">
    {{-- Loop for active pills (up to the current step) --}}
    @for ($i = 1; $i <= $step; $i++)
        <div class="pill active"></div>
    @endfor

    {{-- Loop for inactive pills (remaining steps) --}}
    @for ($i = $step + 1; $i <= $maxSteps; $i++)
        <div class="pill"></div>
    @endfor
</div>