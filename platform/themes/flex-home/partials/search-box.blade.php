<div class="search-box">
    <form action="{{ $type == 'property' ? route('public.properties') : route('public.projects') }}" method="get">
        <div class="form-group">
            <label for="keyword" class="control-label">{{ __('Keyword') }}</label>
            <div class="input-has-icon">
                <input type="text" id="keyword" class="form-control" name="k" value="{{ request()->input('k') }}" placeholder="{{ __('Enter keyword...') }}">
                <i class="fas fa-search"></i>
            </div>
        </div>
        @if ($type == 'property')
            <div class="form-group">
                <label for="select-type" class="control-label">{{ __('Type') }}</label>
                <select name="type" id="select-type" class="form-control">
                    <option value="">{{ __('-- Select --') }}</option>
                    <option value="sale" @if (request()->input('type') == 'sale') selected @endif>{{ __('Sale') }}</option>
                    <option value="rent" @if (request()->input('type') == 'rent') selected @endif>{{ __('Rent') }}</option>
                </select>
            </div>
        @endif

        <div class="form-group">
            <label for="select-category" class="control-label">{{ __('Category') }}</label>
            <select name="category_id" id="select-category" class="form-control">
                <option value="">{{ __('-- Select --') }}</option>
                @foreach($categories as $categoryId => $categoryName)
                    <option value="{{ $categoryId }}" @if (request()->input('category_id') == $categoryId) selected @endif>{{ $categoryName }}</option>
                @endforeach
            </select>
        </div>

        @if ($type == 'property')
            <div class="form-group">
                <label for="select-bedroom" class="control-label">{{ __('Number of bedrooms') }}</label>
                <select name="bedroom" id="select-bedroom" class="form-control">
                    <option value="">{{ __('-- Select --') }}</option>
                    @for($i = 1; $i < 5; $i++)
                        <option value="{{ $i }}" @if (request()->input('bedroom') == $i) selected @endif>{{ $i }} {{ $i == 1 ? __('room') : __('rooms') }}</option>
                    @endfor
                    <option value="5" @if (request()->input('bedroom') == 5) selected @endif>{{ __('5+ rooms') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="select-bathroom" class="control-label">{{ __('Number of bathrooms') }}</label>
                <select name="bathroom" id="select-bathroom" class="form-control">
                    <option value="">{{ __('-- Select --') }}</option>
                    @for($i = 1; $i < 5; $i++)
                        <option value="{{ $i }}" @if (request()->input('bathroom') == $i) selected @endif>{{ $i }} {{ $i == 1 ? __('room') : __('rooms') }}</option>
                    @endfor
                    <option value="5" @if (request()->input('bathroom') == 5) selected @endif>{{ __('5+ rooms') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="select-floor" class="control-label">{{ __('Number of floors') }}</label>
                <select name="floor" id="select-floor" class="form-control">
                    <option value="">{{ __('-- Select --') }}</option>
                    @for($i = 1; $i < 5; $i++)
                        <option value="{{ $i }}" @if (request()->input('floor') == $i) selected @endif>{{ $i }} {{ $i == 1 ? __('floor') : __('floors') }}</option>
                    @endfor
                    <option value="5" @if (request()->input('floor') == 5) selected @endif>{{ __('5+ floors') }}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_price" class="control-label">{{ __('Price from') }}</label>
                        <input type="number" name="min_price" class="form-control" id="min_price" value="{{ request()->input('min_price') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="max_price" class="control-label">{{ __('Price to') }}</label>
                        <input type="number" name="max_price" class="form-control" id="max_price" value="{{ request()->input('max_price') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_square" class="control-label">{{ __('Square from') }}</label>
                        <input type="number" name="min_square" class="form-control" id="min_square" value="{{ request()->input('min_square') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="max_square" class="control-label">{{ __('Square to') }}</label>
                        <input type="number" name="max_square" class="form-control" id="max_square" value="{{ request()->input('max_square') }}">
                    </div>
                </div>
            </div>
        @else
            <div class="form-group">
                <label for="select-blocks" class="control-label">{{ __('Number of blocks') }}</label>
                <select name="blocks" id="select-blocks" class="form-control">
                    <option value="">{{ __('-- Select --') }}</option>
                    @for($i = 1; $i < 5; $i++)
                        <option value="{{ $i }}" @if (request()->input('blocks') == $i) selected @endif>{{ $i }} {{ $i == 1 ? __('block') : __('blocks') }}</option>
                    @endfor
                    <option value="5" @if (request()->input('blocks') == 5) selected @endif>{{ __('5+ blocks') }}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_floor" class="control-label">{{ __('Floor from') }}</label>
                        <input type="number" name="min_floor" class="form-control" id="min_floor" value="{{ request()->input('min_floor') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="max_floor" class="control-label">{{ __('Floor to') }}</label>
                        <input type="number" name="max_floor" class="form-control" id="max_floor" value="{{ request()->input('max_floor') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="min_flat" class="control-label">{{ __('Flat from') }}</label>
                        <input type="number" name="min_flat" class="form-control" id="min_flat" value="{{ request()->input('min_flat') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="max_flat" class="control-label">{{ __('Flat to') }}</label>
                        <input type="number" name="max_flat" class="form-control" id="max_flat" value="{{ request()->input('max_flat') }}">
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary btn-full">{{ __('Search') }}</button>
        </div>
    </form>
</div>
