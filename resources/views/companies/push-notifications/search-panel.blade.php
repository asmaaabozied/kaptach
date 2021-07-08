@component('partials.search-modal', ['route'=>'notifications.index', 'title'=>'Search In Notifications'])
    <div class="row">
        <div class="col-md-6">
            {!! BootForm::input('text', 'from', Request::input('from'), 'Date From', $errors, ['class'=>'datepicker']) !!}
        </div>
        <div class="col-md-6">
            {!! BootForm::input('text', 'to', Request::input('to'), 'Date To', $errors, ['class'=>'datepicker']) !!}
        </div>
    </div>
@endcomponent