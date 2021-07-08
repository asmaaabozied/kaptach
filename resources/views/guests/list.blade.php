<div class="table-responsive">
    <div class="row col-md-offset-4" id="date-search" style="margin-bottom: 20px;">
        <div class="form-group">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control pull-right" name="" value="" id="key_search"
                           placeholder="Search by name or id">
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-default" id="search">
                    Search
                </a>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover dataTable"
           id="customers_table">
        <thead>
        <tr>
            <th>{{__('pages.select')}}</th>
            <th>{{__('pages.passport_number')}}*</th>
            <th>{{__('pages.first_name')}}</th>
            <th>{{__('pages.last_name')}}</th>
            <th>{{__('pages.gender')}}</th>
            <th>{{__('pages.nationality')}}</th>
            <th>{{__('pages.phone')}}</th>
            <th>{{__('pages.room_number')}}</th>
        </tr>
        </thead>
        <tbody>
        <tr>

        </tr>
        </tbody>
    </table>
    <button type="button"
            class="delete btn btn-sm btn-danger delete-row">{{__('buttons.delete_row')}}</button>
</div>

