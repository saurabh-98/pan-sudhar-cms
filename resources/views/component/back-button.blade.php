@props([
    'fallback' => 'admin.dashboard'
])

<a href="{{ url()->previous() != url()->current() ? url()->previous() : route($fallback) }}" 
   class="btn btn-secondary">
    ⬅ Back
</a>