@extends('core/base::layouts.master')
@section('content')
    {!! Form::open(['route' => ['settings.media']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">

            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('core/setting::setting.media.title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">{{ trans('core/setting::setting.media.description') }}</p>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20">
                    <div class="form-group">
                        <label class="text-title-field"
                               for="media_driver">{{ trans('core/setting::setting.media.driver') }}
                        </label>
                        <div class="ui-select-wrapper">
                            <select name="media_driver" class="ui-select" id="media_driver">
                                <option value="public" @if (config('filesystems.default') === 'public') selected @endif>Public</option>
                                <option value="s3" @if (config('filesystems.default') === 's3') selected @endif>Amazon S3</option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>

                    <div class="s3-config-wrapper" @if (config('filesystems.default') !== 's3') style="display: none;" @endif>
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="media_aws_access_key_id">{{ trans('core/setting::setting.media.aws_access_key_id') }}</label>
                            <input type="text" class="next-input" name="media_aws_access_key_id" id="media_aws_access_key_id"
                                   value="{{ config('filesystems.disks.s3.key') }}" placeholder="Ex: AKIAIKYXBSNBXXXXXX">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="media_aws_secret_key">{{ trans('core/setting::setting.media.aws_secret_key') }}</label>
                            <input type="text" class="next-input" name="media_aws_secret_key" id="media_aws_secret_key"
                                   value="{{ config('filesystems.disks.s3.secret') }}" placeholder="Ex: +fivlGCeTJCVVnzpM2WfzzrFIMLHGhxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="media_aws_default_region">{{ trans('core/setting::setting.media.aws_default_region') }}</label>
                            <input type="text" class="next-input" name="media_aws_default_region" id="media_aws_default_region"
                                   value="{{ config('filesystems.disks.s3.region') }}" placeholder="Ex: ap-southeast-1">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="media_aws_bucket">{{ trans('core/setting::setting.media.aws_bucket') }}</label>
                            <input type="text" class="next-input" name="media_aws_bucket" id="media_aws_bucket"
                                   value="{{ config('filesystems.disks.s3.bucket') }}" placeholder="Ex: botble">
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="text-title-field"
                                   for="media_aws_url">{{ trans('core/setting::setting.media.aws_url') }}</label>
                            <input type="text" class="next-input" name="media_aws_url" id="media_aws_url"
                                   value="{{ config('filesystems.disks.s3.url') }}" placeholder="Ex: https://s3-ap-southeast-1.amazonaws.com/botble">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-title-field"
                               for="media_chunk_enabled">{{ trans('core/setting::setting.media.enable_chunk') }}
                        </label>
                        <label class="hrv-label">
                            <input type="radio" name="media_chunk_enabled" class="hrv-radio"
                                   value="1"
                                   @if (setting('media_chunk_enabled', config('core.media.media.chunk.enabled'))) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                        </label>
                        <label class="hrv-label">
                            <input type="radio" name="media_chunk_enabled" class="hrv-radio"
                                   value="0"
                                   @if (!setting('media_chunk_enabled', config('core.media.media.chunk.enabled'))) checked @endif>{{ trans('core/setting::setting.general.no') }}
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="text-title-field"
                               for="media_chunk_size">{{ trans('core/setting::setting.media.chunk_size') }}</label>
                        <input type="number" class="next-input" name="media_chunk_size" id="media_chunk_size"
                               value="{{ setting('media_chunk_size', config('core.media.media.chunk.chunk_size')) }}" placeholder="{{ trans('core/setting::setting.media.chunk_size_placeholder') }}">
                    </div>

                    <div class="form-group">
                        <label class="text-title-field"
                               for="media_max_file_size">{{ trans('core/setting::setting.media.max_file_size') }}</label>
                        <input type="number" class="next-input" name="media_max_file_size" id="media_max_file_size"
                               value="{{ setting('media_max_file_size', config('core.media.media.chunk.max_file_size')) }}" placeholder="{{ trans('core/setting::setting.media.max_file_size_placeholder') }}">
                    </div>

                </div>
            </div>

        </div>

        <div class="flexbox-annotated-section" style="border: none">
            <div class="flexbox-annotated-section-annotation">
                &nbsp;
            </div>
            <div class="flexbox-annotated-section-content">
                <button class="btn btn-primary" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('footer')
    <script>
        'use strict';
        $(document).ready(function () {
            $(document).on('change', '#media_driver', function () {
               if ($(this).val() === 's3') {
                   $('.s3-config-wrapper').show();
               } else {
                   $('.s3-config-wrapper').hide();
               }
            });
        });
    </script>
@endpush
