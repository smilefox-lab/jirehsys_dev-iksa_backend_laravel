<div class="flexbox-annotated-section">

    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/real-estate::consult.settings.email.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/real-estate::consult.settings.email.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
                <div class="table-wrap">
                    <table class="table product-list ws-nm">
                        <thead>
                        <tr>
                            <th class="border-none-b">{{ trans('core/setting::setting.template') }}</th>
                            <th class="border-none-b"> {{ trans('core/setting::setting.description') }} </th>
                            <th class="border-none-b"> {{ trans('core/setting::setting.enable') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $emailTemplates = config('plugins.real-estate.email.templates', []);
                        @endphp
                        @foreach ($emailTemplates as $key => $template)
                            <tr>
                                <td>
                                    <a class="hover-underline a-detail-template"
                                       href="{{ route('setting.email.template.edit', ['type' => 'plugins', 'name' => REAL_ESTATE_MODULE_SCREEN_NAME, 'template_file' => $key]) }}">
                                        {{ trans($template['title']) }}
                                    </a>
                                </td>
                                <td>{{ trans($template['description']) }}</td>
                                <td>
                                    @if ($template['can_off'])
                                        <div class="form-group ">
                                            {!! Form::onOff(get_setting_email_status_key('plugins', REAL_ESTATE_MODULE_SCREEN_NAME, $key),
                                                get_setting_email_status('plugins', REAL_ESTATE_MODULE_SCREEN_NAME, $key) == 1,
                                                ['data-key' => 'email-config-status-btn', 'data-change-url' => route('setting.email.status.change')]
                                            ) !!}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
