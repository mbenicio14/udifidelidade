@extends('admin.layouts.default')

@section('page_title', trans('common.transactions') . config('default.page_title_delimiter') . $card->head . config('default.page_title_delimiter') . $member->name . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 h-full w-full place-items-center">
        <div class="max-w-md mx-auto">
            <div class="max-w-md mx-auto mb-5">
                <x-ui.breadcrumb :crumbs="[
                    ['url' => route('admin.index'), 'icon' => 'home', 'title' => trans('common.home')],
                    ['url' => route('admin.data.list', ['name' => 'members']), 'text' => trans('common.members')],
                    ['url' => route('member.card', ['card_id' => $card->id]), 'title' => trans('common.view_card_on_website'), 'icon' => 'qr-card', 'target' => '_blank']
                ]" />
            </div>

            <x-forms.messages />

            @if($card)
                <x-member.card :card="$card" :member="$member" :flippable="false" :links="false" :show-qr="false" />
            @endif

            @if($member)
               <x-member.member-card class="my-6" :member="$member" />
               <x-member.history :card="$card" :show-notes="true" :show-attachments="true" :show-staff="true" :member="$member" />
            @endif
        </div>
    </div>
</div>
@stop
