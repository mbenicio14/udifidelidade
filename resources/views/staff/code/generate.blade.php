@extends('staff.layouts.default')

@section('page_title', $card->head . config('default.page_title_delimiter') . trans('common.generate_code') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 h-full w-full place-items-center">
        <div class="max-w-md mx-auto">

            <div class="max-w-md mx-auto mb-5">
                <x-ui.breadcrumb :crumbs="[
                    ['url' => route('staff.index'), 'icon' => 'home', 'title' => trans('common.home')],
                    ['url' => route('staff.data.list', ['name' => 'cards']), 'text' => trans('common.loyalty_cards')],
                    ['url' => route('member.card', ['card_id' => $card->id]), 'title' => trans('common.view_card_on_website'), 'icon' => 'qr-card', 'target' => '_blank']
                ]" />
            </div>

            {{-- Show the card --}}
            <x-member.card
                :card="$card"
                :member="null"
                :flippable="false"
                :links="false"
                :show-qr="false"
            />

            <x-forms.messages class="mt-4" />

            <x-forms.form-open
                action="{{ route('staff.code.generate.post', ['card_identifier' => $card->unique_identifier]) }}"
                method="POST"
            />

            <div class="grid gap-4 mb-6">
                <x-forms.input
                    name="points"
                    :label="trans('common.points')"
                    type="number"
                    inputmode="numeric"
                    icon="coins"
                    affix-class="text-gray-400 dark:text-gray-500 text-xl"
                    input-class="text-xl"
                    :min="$card->min_points_per_purchase"
                    :max="$card->max_points_per_purchase"
                    step="1"
                    :placeholder="trans('common.points_placeholder', ['min' => $card->min_points_per_purchase, 'max' => $card->max_points_per_purchase])"
                    required
                />
            </div>

            <div class="mb-6">
                <button type="submit" class="btn-primary btn-lg w-full h-16">
                    {{ trans('common.generate_code') }}
                </button>
            </div>

            <x-forms.form-close />

            <div class="hide-on-scan z-40 mb-4 flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <x-ui.icon icon="info" class="w-5 h-5"/>
                </div>
                <div class="ml-3 text-sm font-normal">{{ trans('common.generate_code_info', ['expiry' => Carbon\CarbonInterval::minutes(config('default.code_to_redeem_points_valid_minutes'))->cascade()->forHumans(['parts' => 2])]) }}</div>
            </div>

        </div>
    </div>
</div>
@stop
