@extends('partner.layouts.default')

@section('page_title', trans('common.transactions') . config('default.page_title_delimiter') . $card->head . config('default.page_title_delimiter') . $member->name . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 h-full w-full place-items-center">
        <div class="max-w-md mx-auto">
            <div class="max-w-md mx-auto mb-5">
                <x-ui.breadcrumb :crumbs="[
                    ['url' => route('partner.index'), 'icon' => 'home', 'title' => trans('common.home')],
                    ['url' => route('partner.data.list', ['name' => 'members']), 'text' => trans('common.members')],
                    ['url' => route('partner.analytics.card', ['card_id' => $card->id]), 'text' => $card->head],
                    ['url' => route('member.card', ['card_id' => $card->id]), 'title' => trans('common.view_card_on_website'), 'icon' => 'qr-card', 'target' => '_blank']
                ]" />
            </div>

            <x-forms.messages />
            @if($card)
                <x-member.card
                    :card="$card"
                    :member="$member"
                    :flippable="false"
                    :links="false"
                    :show-qr="false"
                />
            @endif

            @if($member)
                <x-member.member-card class="my-6" :member="$member" />

                @if($card)
                    <a href="javascript:void(0);" class="my-6 btn-danger btn-lg flex" @click="deleteLastTransaction()">
                        <x-ui.icon icon="trash" class="h-5 w-5 mr-2" />
                        {{ trans('common.delete_last_transaction') }}
                    </a>

                    <script>
                        function deleteLastTransaction() {
                            appConfirm("{{ trans('common.confirm_deletion') }}", "{{ trans('common.confirm_delete_last_transaction') }}", {
                                'btnConfirm': {
                                    'click': function() {
                                        document.location = '{{ route('partner.delete.last.transaction', ['member_identifier' => $member->unique_identifier, 'card_identifier' => $card->unique_identifier]) }}';
                                    }
                                }
                            });
                        }
                    </script>
                @endif

                <x-member.history 
                    :card="$card" 
                    :show-notes="true" 
                    :show-attachments="true" 
                    :show-staff="true" 
                    :member="$member" 
                />
            @endif
        </div>
    </div>
</div>
@stop
