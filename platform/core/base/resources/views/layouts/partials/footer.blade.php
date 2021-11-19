<div class="page-footer">
    <div class="page-footer-inner">
        <div class="row">
            <div class="col-md-6">
                {!! clean(trans('core/base::layouts.copyright', ['year' => now(config('app.timezone'))->format('Y'), 'company' => setting('admin_title', config('core.base.general.base_name')), 'version' => '1.0.0'])) !!}
            </div>
            <div class="col-md-6 text-right">

            </div>
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up-circle"></i>
    </div>
</div>
