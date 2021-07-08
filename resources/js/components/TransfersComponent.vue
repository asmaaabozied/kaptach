<template>
    <div class="box-body">
        <div class="projects">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        <a id="prev_day" name="prev_day" class="btn btn-block btn-default btn-lg" @click="prevDate()">
                            <i class="fa fa-arrow-left"></i>Bir Önceki Gün
                        </a>
                    </div>
                    <div class="col-md-3 tarih">
                        <div class="input-group date">
                            <input type="text" class="form-control pull-right datepicker" id="datepicker"
                                   name="datepicker" data-date-format="yyyy-mm-dd" v-model="tableData.selected_date"
                                   required>
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a id="next_day" name="next_day" class="btn btn-block btn-default btn-lg" @click="nextDate()">
                            Bir Sonraki Gün <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-sm-6">
                    <div class="dataTables_length">
                        <label>Sayfada
                            <select class="form-control input-sm" v-model="tableData.length" @change="getTransfers()">
                                <option v-for="(records, index) in perPage" :key="index" :value="records">
                                    {{records}}
                                </option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="dataTables_filter">
                        <label>Ara:<input class="form-control input-sm" type="text" v-model="tableData.search"
                                          placeholder="Search Table"
                                          @input="getTransfers()"></label>
                    </div>
                </div>
            </div>

            <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                <tbody>
                <tr v-for="transfer in transfers" :key="transfer.id">
                    <td><input type="checkbox" name="record" value="transfer.id"></td>
                    <td @click="'transfers/show/'+transfer.id">{{transfer.id}}</td>
                    <td @click="showAlert()">{{transfer.type}}</td>
                    <td @click="showAlert()"><p style="font-size: 22px;">
                        {{moment(transfer.transfer_start_time).format('HH:mm')}}</p></td>
                    <td @click="showAlert()">{{transfer.transferable.name}}</td>
                    <td @click="showAlert()">{{transfer.airport.name}}</td>
                    <td @click="showAlert()"><p v-if="transfer.driver">{{transfer.driver.employer.first_name+' '+transfer.driver.employer.last_name}}</p>
                    </td>
                    <td @click="showAlert()">{{transfer.car_model.model_name}}</td>
                    <td @click="showAlert()">{{transfer.price}}</td>
                    <td @click="showAlert()">{{transfer.request_status}}</td>
                    <td @click="showAlert()">{{transfer.action_by_admin.username}}</td>
                    <td @click="showAlert()">{{transfer.updated_at}}</td>
                    <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                                <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu">
                                <li><a :href="'transfers/'+transfer.id+'/edit'">Edit</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                </tbody>
            </datatable>

            <pagination :pagination="pagination"
                        @prev="getTransfers(pagination.prevPageUrl)"
                        @next="getTransfers(pagination.nextPageUrl)">
            </pagination>
        </div>
    </div>
</template>

<script>
    import Datatable from './Datatable.vue';
    import Pagination from './Pagination.vue';
    import moment from 'moment';

    export default {
        components: {datatable: Datatable, pagination: Pagination},
        created() {
            this.getTransfers();
        },
        data() {
            let sortOrders = {};

            let columns = [
                {label: 'checkbox', name: 'checkbox'},
                {label: 'id', name: 'id'},
                {label: 'type', name: 'type'},
                {label: 'transfer Start time', name: 'transfer Start time'},
                {label: 'Client', name: 'Client'},
                {label: 'Airport', name: 'Airport'},
                {label: 'Driver', name: 'Driver'},
                {label: 'Car Model', name: 'Car Model'},
                {label: 'Price', name: 'Price'},
                {label: 'Request Status', name: 'Request Status'},
                {label: 'Admin', name: 'Admin'},
                {label: 'Updated At', name: 'Updated At'},
                {label: 'Action', name: 'Action'}
            ];

            columns.forEach((column) => {
                sortOrders[column.name] = -1;
            });
            return {
                transfers: [],
                moment: moment,
                columns: columns,
                sortKey: 'deadline',
                sortOrders: sortOrders,
                perPage: ['10', '20', '30'],
                tableData: {
                    draw: 0,
                    length: 10,
                    search: '',
                    column: 0,
                    dir: 'desc',
                    selected_date: moment().format('YYYY-MM-DD'),
                },
                pagination: {
                    lastPage: '',
                    currentPage: '',
                    total: '',
                    lastPageUrl: '',
                    nextPageUrl: '',
                    prevPageUrl: '',
                    from: '',
                    to: ''
                },
            }
        },
        methods: {
            getTransfers(url = '/transfers') {
                this.tableData.draw++;
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        let data = response.data;
                        if (this.tableData.draw == data.draw) {
                            this.transfers = data.data.data;
                            this.configPagination(data.data);
                        }
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
                Echo.private('activity.1')
                    .listen('ActivityLogged', (e) => {
                        console.log(e.data.description);
                    });
//                Echo.channel('add-channel').listen('TransferCreated', ({transfer}) => {
//                    this.transfers.push(transfer);
//                });
            },
            configPagination(data) {
                this.pagination.lastPage = data.last_page;
                this.pagination.currentPage = data.current_page;
                this.pagination.total = data.total;
                this.pagination.lastPageUrl = data.last_page_url;
                this.pagination.nextPageUrl = data.next_page_url;
                this.pagination.prevPageUrl = data.prev_page_url;
                this.pagination.from = data.from;
                this.pagination.to = data.to;
            },
            sortBy(key) {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.getTransfers();
            },
            getIndex(array, key, value) {
                return array.findIndex(i => i[key] == value)
            },
            prevDate() {
                var prevDate = moment(this.tableData.selected_date).subtract(1, "days").format("YYYY-MM-DD");
                this.tableData.selected_date = prevDate;
                this.getTransfers();
            },
            nextDate() {
                var nextDate = moment(this.tableData.selected_date).add(1, "days").format("YYYY-MM-DD");
                this.tableData.selected_date = nextDate;
                this.getTransfers();
            },
            showAlert() {
                console.log(0);

            }
        }
    };
</script>
