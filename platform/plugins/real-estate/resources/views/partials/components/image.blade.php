<div class="list-photo-hover-overlay">
    <ul class="photo-overlay-actions">
        <li>
            <a class="mr10 btn-trigger-edit-object-image" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ __('Thay hình') }}">
                <i class="fa fa-edit"></i>
            </a>
        </li>
        <li>
            <a class="mr10 btn-trigger-remove-object-image" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ __('Xóa hình ảnh') }}">
                <i class="fa fa-trash"></i>
            </a>
        </li>
    </ul>
</div>
<div class="custom-image-box image-box">
    <input type="hidden"
           name="{{ $name }}"
           value="{{ $value }}"
           class="image-data">
    <img
            src="{{ get_image_url($value, 'thumb', false, config('media.default-img')) }}"
            alt="preview image" class="preview_image">
    <div class="image-box-actions">
        <a class="btn-images" data-result="{{ $name }}" data-action="{{ $attributes['action'] ?? 'select-image' }}">
            {{ trans('core/base::forms.choose_image') }}
        </a> |
        <a class="btn_remove_image">
            <span></span>
        </a>
    </div>
</div>
