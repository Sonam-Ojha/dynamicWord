@props(['label' => null, 'name', 'options' => [], 'value' => null, 'placeholder' => '— Select —', 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
            {{ $label }} @if ($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm']) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $label)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
