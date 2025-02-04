@extends('member.layouts.default')

@section('page_title', trans('common.redeem_points_code') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 h-full w-full place-items-center">
        <div class="max-w-md mx-auto">

            <div class="max-w-md mx-auto mb-5">
                <x-ui.breadcrumb :crumbs="[
                    ['url' => route('member.index'), 'icon' => 'home', 'title' => trans('common.home')],
                    ['url' => route('member.dashboard'), 'text' => trans('common.dashboard')],
                    ['text' => trans('common.redeem_points_code')]
                ]" />
            </div>

            <x-forms.messages class="mt-4" />

            <x-forms.form-open
                action="{{ route('member.code.redeem.post') }}"
                method="POST"
            />

            <div class="grid gap-4 mb-6">
                <x-forms.input
                    name="code"
                    :label="trans('common.enter_code')"
                    type="number"
                    inputmode="numeric"
                    icon="calculator"
                    affix-class="text-gray-400 dark:text-gray-500 text-xl"
                    input-class="text-xl"
                    maxlength="4"
                    step="1"
                    :placeholder="trans('common.enter_code_placeholder')"
                    required
                />
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('code');
    if (!input) return;

    // Restrict input to digits only and enforce max length of 4
    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^0-9]/g, '').slice(0, 4); // Remove non-digits and limit to 4
    });
});
</script>
            </div>

            <div class="mb-6">
                <button type="submit" class="btn-primary btn-lg w-full h-16">
                    {{ trans('common.redeem_code') }}
                </button>
            </div>

            <x-forms.form-close />

            <div class="hide-on-scan z-40 mb-4 flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <x-ui.icon icon="info" class="w-5 h-5"/>
                </div>
                <div class="ml-3 text-sm font-normal">{{ trans('common.redeem_code_info', ['expiry' => Carbon\CarbonInterval::minutes(config('default.code_to_redeem_points_valid_minutes'))->cascade()->forHumans(['parts' => 2])]) }}</div>
            </div>

        </div>
    </div>
</div>
@stop
