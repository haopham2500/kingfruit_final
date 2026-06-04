@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="fw-bold">{{ __('messages.review_management') }}</h2>
        {{-- Giữ nguyên nút thêm hoặc các chức năng phụ khác --}}
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4 py-3">{{ __('messages.review_customer_contact') }}</th>
                            <th class="py-3">{{ __('messages.review_content') }}</th>
                            <th class="py-3">{{ __('messages.review_history') }}</th>
                            <th class="py-3 text-end pe-4">{{ __('messages.product_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-body">{{ $review->user?->name ?? __('messages.guest_customer') }}</span>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ optional($review->created_at)->format('d/m/Y H:i') }}</small>
                                    <div class="mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill small {{ $i <= $review->rating ? 'text-warning' : 'text-light' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 text-secondary small" style="max-width: 300px;">{{ $review->comment }}</p>
                            </td>
                            <td style="width: 35%;">
                                {{-- Danh sách các câu đã trả lời --}}
                                @php
                                    $adminReply = $review->replies->firstWhere('user.role', 'admin');
                                @endphp
                                <div class="reply-container mb-2">
                                    @if($review->replies->isNotEmpty())
                                        @foreach($review->replies as $reply)
                                        <div class="p-2 mb-2 bg-body-tertiary rounded border-start border-success border-3 shadow-xs">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success-soft text-success mb-1" style="font-size: 0.7rem;">{{ __('messages.admin_reply') }}</span>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ optional($reply->created_at)->format('H:i d/m') }}</small>
                                            </div>
                                            <p class="mb-0 small text-body italic">"{{ $reply->comment }}"</p>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-muted small">{{ __('messages.no_reviews_yet') }}</div>
                                    @endif
                                </div>

                                @if($adminReply)
                                <div class="collapse mt-2" id="editReply{{ $adminReply->id }}">
                                    <form action="{{ route('admin.reviews.reply.update', $adminReply->id) }}" method="POST" onsubmit="return handleReplyEditSubmit(event, this)" data-reply-check-url="{{ route('admin.reviews.replies.check', $adminReply->id) }}" data-reply-updated-at="{{ $adminReply->updated_at->format('Y-m-d H:i:s') }}" data-review-check-url="{{ route('admin.reviews.check', $review->id) }}" data-review-updated-at="{{ $review->updated_at?->format('Y-m-d H:i:s') }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="reply_updated_at" value="{{ $adminReply->updated_at->format('Y-m-d H:i:s') }}">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="reply_content" value="{{ $adminReply->comment }}" class="form-control shadow-none" required>
                                            <button class="btn btn-primary" type="submit">{{ __('messages.save') }}</button>
                                        </div>
                                    </form>
                                </div>
                                @endif

                                {{-- Form trả lời nhanh --}}
                                <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="reply_content" class="form-control shadow-none" placeholder="{{ __('messages.reply_placeholder') }}" required>
                                        <button class="btn btn-success" type="submit">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($review->product)
                                    <a href="{{ route('product.detail', $review->product->id) }}" class="btn btn-outline-primary btn-sm" title="{{ __('messages.view_product') }}" onclick="return handleReviewView(event, this)" data-review-check-url="{{ route('admin.reviews.check', $review->id) }}" data-review-updated-at="{{ $review->updated_at?->format('Y-m-d H:i:s') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                    @php
                                        $adminReply = $review->replies->firstWhere('user.role', 'admin');
                                        $deleteConfirm = addslashes(__('messages.delete_review_confirm'));
                                    @endphp
                                    @if($adminReply)
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="return handleReviewEdit(event, this)" title="{{ __('messages.edit') }}" data-review-check-url="{{ route('admin.reviews.check', $review->id) }}" data-review-updated-at="{{ $review->updated_at?->format('Y-m-d H:i:s') }}" data-review-edit-target="#editReply{{ $adminReply->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', ['id' => $review->id]) }}" method="POST" onsubmit="return handleReviewDelete(event, this, '{{ $deleteConfirm }}')" data-review-check-url="{{ route('admin.reviews.check', $review->id) }}" data-review-updated-at="{{ $review->updated_at?->format('Y-m-d H:i:s') }}">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="review_updated_at" value="{{ $review->updated_at?->format('Y-m-d H:i:s') }}">
                                        <button class="btn btn-outline-danger btn-sm" title="{{ __('messages.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                {{ __('messages.no_reviews_yet') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="review-stale-data" data-message="{{ __('messages.review_changed_reload') }}"></div>

<script>
    const reviewChangedMessage = document.getElementById('review-stale-data')?.dataset?.message || 'This review has changed.';

    async function fetchReviewStatus(url) {
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) {
                return { status: 'changed' };
            }
            return await response.json();
        } catch (error) {
            return { status: 'changed' };
        }
    }

    async function handleReviewView(event, anchor) {
        event.preventDefault();
        const url = anchor.dataset.reviewCheckUrl;
        if (!url) {
            window.location = anchor.href;
            return false;
        }

        const result = await fetchReviewStatus(url);
        const currentUpdatedAt = anchor.dataset.reviewUpdatedAt;
        if (result.status !== 'ok' || (currentUpdatedAt && result.updated_at !== currentUpdatedAt)) {
            alert(reviewChangedMessage);
            return false;
        }

        window.location = anchor.href;
        return false;
    }

    async function handleReviewDelete(event, form, confirmMessage) {
        event.preventDefault();
        if (!confirm(confirmMessage)) {
            return false;
        }

        const url = form.dataset.reviewCheckUrl;
        const currentUpdatedAt = form.dataset.reviewUpdatedAt;
        if (url) {
            const result = await fetchReviewStatus(url);
            if (result.status !== 'ok' || (currentUpdatedAt && result.updated_at !== currentUpdatedAt)) {
                alert(reviewChangedMessage);
                return false;
            }
        }

        form.submit();
        return false;
    }

    function toggleCollapse(targetSelector) {
        const target = document.querySelector(targetSelector);
        if (!target) {
            return;
        }
        target.classList.toggle('show');
    }

    async function handleReviewEdit(event, button) {
        event.preventDefault();
        const reviewCheckUrl = button.dataset.reviewCheckUrl;
        const currentReviewUpdatedAt = button.dataset.reviewUpdatedAt;
        const target = button.dataset.reviewEditTarget;

        if (reviewCheckUrl) {
            const result = await fetchReviewStatus(reviewCheckUrl);
            if (result.status !== 'ok' || (currentReviewUpdatedAt && result.updated_at !== currentReviewUpdatedAt)) {
                alert(reviewChangedMessage);
                return false;
            }
        }

        if (target) {
            toggleCollapse(target);
        }
        return false;
    }

    async function handleReplyEditSubmit(event, form) {
        event.preventDefault();

        const reviewCheckUrl = form.dataset.reviewCheckUrl;
        const currentReviewUpdatedAt = form.dataset.reviewUpdatedAt;
        const replyCheckUrl = form.dataset.replyCheckUrl;
        const currentReplyUpdatedAt = form.dataset.replyUpdatedAt;

        if (reviewCheckUrl) {
            const reviewResult = await fetchReviewStatus(reviewCheckUrl);
            if (reviewResult.status !== 'ok' || (currentReviewUpdatedAt && reviewResult.updated_at !== currentReviewUpdatedAt)) {
                alert(reviewChangedMessage);
                return false;
            }
        }

        if (replyCheckUrl) {
            const replyResult = await fetchReviewStatus(replyCheckUrl);
            if (replyResult.status !== 'ok' || (currentReplyUpdatedAt && replyResult.updated_at !== currentReplyUpdatedAt)) {
                alert(reviewChangedMessage);
                return false;
            }
        }

        form.submit();
        return false;
    }
</script>

<style>
    /* Custom CSS để giống trang quản lý đơn hàng */
    .bg-success-soft { background-color: #e8f5e9; }
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.04)!important; }
    .table thead th {
        border-top: none;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .italic { font-style: italic; color: #555; }
</style>
@endsection