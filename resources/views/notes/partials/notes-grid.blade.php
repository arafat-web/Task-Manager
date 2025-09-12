@if($notes->count() > 0)
    <div class="notes-grid">
        @foreach($notes as $note)
            <div class="note-card" data-note-id="{{ $note->id }}">
                <!-- Note Actions -->
                <div class="note-actions">
                    <a href="{{ route('notes.edit', $note->id) }}" class="action-btn edit" title="Edit Note">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="action-btn duplicate" data-note-id="{{ $note->id }}" title="Duplicate Note">
                        <i class="fas fa-copy"></i>
                    </button>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display: inline;"
                          onsubmit="return confirm('Are you sure you want to delete this note?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete" title="Delete Note">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- Note Header -->
                <div class="note-card-header">
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <button class="note-favorite {{ $note->is_favorite ? 'active' : '' }}"
                            data-note-id="{{ $note->id }}" title="Toggle Favorite">
                        <i class="fas fa-star"></i>
                    </button>
                </div>

                <!-- Category and Tags -->
                @if($note->category)
                    <div style="padding: 0 1.25rem;">
                        <span class="note-category">{{ $note->category }}</span>
                    </div>
                @endif

                @if($note->tags && count($note->tags) > 0)
                    <div style="padding: 0 1.25rem;">
                        <div class="note-tags">
                            @foreach($note->tags as $tag)
                                <span class="note-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Note Content -->
                <div class="note-content">
                    {!! $note->excerpt !!}
                </div>

                <!-- Note Meta -->
                <div class="note-meta">
                    <div>
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            {{ $note->created_at->diffForHumans() }}
                        </small>
                        @if($note->date)
                            <br>
                            <small>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $note->formatted_date }}
                                @if($note->time)
                                    at {{ $note->formatted_time }}
                                @endif
                            </small>
                        @endif
                    </div>
                    <div class="text-end">
                        <small>{{ $note->word_count }} words</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- List View (Hidden by default) -->
    <div class="notes-list">
        @foreach($notes as $note)
            <div class="note-card" data-note-id="{{ $note->id }}" style="display: flex; align-items: center; padding: 1.5rem; cursor: pointer;">
                <div class="me-3">
                    <button class="note-favorite {{ $note->is_favorite ? 'active' : '' }}"
                            data-note-id="{{ $note->id }}" title="Toggle Favorite">
                        <i class="fas fa-star"></i>
                    </button>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">{{ $note->title }}</h5>
                        <div class="d-flex gap-2">
                            @if($note->category)
                                <span class="note-category">{{ $note->category }}</span>
                            @endif
                            <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <p class="text-muted mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {!! $note->excerpt !!}
                    </p>

                    @if($note->tags && count($note->tags) > 0)
                        <div class="note-tags">
                            @foreach($note->tags as $tag)
                                <span class="note-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="ms-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('notes.show', $note->id) }}">
                                <i class="fas fa-eye me-2"></i>View
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('notes.edit', $note->id) }}">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a></li>
                            <li><button class="dropdown-item action-btn duplicate" data-note-id="{{ $note->id }}">
                                <i class="fas fa-copy me-2"></i>Duplicate
                            </button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this note?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-notes">
        <div class="empty-icon">
            <i class="fas fa-sticky-note"></i>
        </div>
        <h3>No Notes Found</h3>
        <p class="mb-4">You haven't created any notes yet, or no notes match your current filters.</p>
        <a href="{{ route('notes.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Create Your First Note
        </a>
    </div>
@endif
