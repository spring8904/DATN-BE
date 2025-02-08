<option value="{{ $category->id }}"
        style="padding-left: {{ $level * 15 }}px; {{ $level == 0 ? 'font-weight: bold;' : '' }}"
    {{ $oldParentId == $category->id ? 'selected' : '' }}
    {{ $level > 0 ? 'disabled' : '' }}>
    @if($level > 0)
        {{ str_repeat('--', $level) }}
    @endif
    {{ $category->name }}
</option>

@if ($category->children->count())
    @foreach($category->children as $child)
        @include('categories.category_option', ['category' => $child, 'level' => $level + 1, 'oldParentId' => $oldParentId])
    @endforeach
@endif
