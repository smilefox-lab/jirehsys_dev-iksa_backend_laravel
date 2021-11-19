@if ($account->id)
    {!! Form::open(['url' => route('vendor.credits.add', $account->id)]) !!}
    <div class="next-form-section">
        <div class="next-form-grid">
            <div class="next-form-grid-cell">
                <label class="text-title-field">{{ __('Number of credits') }}</label>
                <input type="number" class="next-input" name="credits" placeholder="{{ __('Number of credits') }}" value="0">
            </div>
        </div>
        <div class="next-form-grid">
            <div class="next-form-grid-cell">
                <label class="text-title-field">{{ __('Description') }}</label>
                <textarea class="next-input" name="description" placeholder="{{ __('Description') }}" rows="5"></textarea>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endif
