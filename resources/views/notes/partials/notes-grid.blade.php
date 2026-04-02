@if($notes->count() > 0)
<div class="cu-notes-grid">
    @foreach($notes as $note)
    <div class="cu-note-card" data-note-id="{{ $note->id }}">
        <div class="cu-note-accent"></div>

        {{-- Title + favorite --}}
        <div class="cu-note-head">
            <h3 class="cu-note-title">{{ $note->title }}</h3>
            <button class="cu-fav-btn {{ $note->is_favorite ? 'active' : '' }}" data-note-id="{{ $note->id }}" title="Toggle favourite">
                <i class="bi bi-star-fill"></i>
            </button>
        </div>

        {{-- Category + tags --}}
        @if($note->category || ($note->tags && count($note->tags)))
        <div class="cu-note-badges">
            @if($note->category)
                <span class="cu-cat-badge"><i class="bi bi-tag me-1" style="font-size:10px;"></i>{{ $note->category }}</span>
            @endif
            @if($note->tags)
                @foreach(array_slice($note->tags, 0, 3) as $tag)
                    <span class="cu-tag-pill">{{ $tag }}</span>
                @endforeach
                @if(count($note->tags) > 3)
                    <span class="cu-tag-pill">+{{ count($note->tags) - 3 }}</span>
                @endif
            @endif
        </div>
        @endif

        {{-- Excerpt --}}
        <div class="cu-note-excerpt">{{ $note->excerpt }}</div>

        {{-- Footer --}}
        <div class="cu-note-footer">
            <div class="cu-note-meta">
                <i class="bi bi-clock" style="font-size:10px;"></i>
                {{ $note->created_at->diffForHumans() }}
                &middot; {{ $note->word_count }} words
            </div>
            <div class="cu-note-actions">
                <a href="{{ route('notes.edit', $note->id) }}" class="cu-note-btn edit" onclick="event.stopPropagation()">
                    <i class="bi bi-pencil"></i>
                </a>
                <button class="cu-note-btn copy" data-note-id="{{ $note->id }}" title="Duplicate">
                    <i class="bi bi-files"></i>
                </button>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                      onsubmit="return confirm('Delete this note?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="cu-note-btn del" onclick="event.stopPropagation()">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="cu-notes-grid">
    <div class="cu-empty">
        <i class="bi bi-journal-text"></i>
        <p>No notes found. Create your first one!</p>
        <a href="{{ route('notes.create') }}"><i class="bi bi-plus-lg"></i> New Note</a>
    </div>
</div>
@endif
