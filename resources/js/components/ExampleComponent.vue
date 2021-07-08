<style>
    span.capitalize {
        text-transform: capitalize;
    }

    .box > .overlay {
        position: unset;
    }
</style>
<template>
    <!-- PRODUCT LIST -->
    <div class="row" style="   margin-left: -6px;">
        <!-- The time line -->
        <!--advanced search -->
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Search Fields</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="row" style="margin-left: 15px;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="checkedMine" v-bind:value="1"> My Transfers
                            </label><br>
                            <label>
                                <input type="checkbox" v-model="purchased" v-bind:value="1"> My (Purchased) Transfers
                            </label><br>
                            <label>
                                <input type="checkbox" v-model="sold" v-bind:value="1"> My (Sold) Transfers
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <select class="form-control" v-model="selected_company">
                                <option value="" selected>Select Transfer Company</option>
                                <option v-for="(key,value) in companies" v-bind:value="key">{{value}}
                                </option>
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="row" style="margin-left: 2px;">

                        <div class="form-group">
                            <div class="col-md-7">
                                <label for="from">From</label>
                                <datepicker id="from" format="dd MMM yyyy" v-model="from_date"
                                            input-class="form-control"
                                            name="from"></datepicker>
                                <!--<datepicker></datepicker>-->
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7">
                                <label for="to">To</label>
                                <datepicker id="to" format="dd MMM yyyy" v-model="to_date" input-class="form-control"
                                            name="to"></datepicker>

                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="box-footer">
                    <a class="btn default" v-on:click="reset()">Reset</a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!--->
        <div class="col-md-8">
            <div v-for="(transfer,index) in orderedTransfers" v-bind:key="transfer.id">
                <div class="row" style="   margin-left: -6px;" v-show="!getTimeDifference(transfer)">
                    <div class="time-label col-md-12" v-if="!getTimeDifference(transfer)">
                        <p class="text-muted well well-sm no-shadow text-center">
                            {{ moment(transfer.transfer_start_time).format('D MMM.YYYY')}}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div :class="transfer.p_class" :id="'id'+transfer.id">
                        <div :class="'box '+transfer.class + ' box-solid collapsed-box'">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <span class="time"><a :href="'transfers/show/'+transfer.id" target="_blank"><i
                                            class="fa fa-clock-o"></i> {{ moment(transfer.transfer_start_time).format('HH:mm')}}</a></span>
                                    <span class="badge bg-yellow capitalize">{{transfer.type}}</span>
                                    <span class="badge bg-yellow">{{transfer.airport.name}}</span>
                                </h3>
                                <div class="box-tools pull-right">
                                      <span v-show="transfer.updated"
                                            data-toggle="tooltip" title="" class="badge bg-yellow"
                                            data-original-title="updated">Updated</span>
                                    <span v-show="transfer.for_sale"
                                          data-toggle="tooltip" title="" class="badge bg-yellow"
                                          data-original-title="fOR SALE">FOR SALE</span>
                                    <span v-show="transfer.for_exchange"
                                          data-toggle="tooltip" title="" class="badge bg-yellow"
                                          data-original-title="For Exchange">For Exchange</span>
                                    <span v-show="transfer.sold"
                                          data-toggle="tooltip" title="" class="badge bg-light-blue"
                                          data-original-title="SOLD">SOLD</span>
                                    <span v-show="transfer.purchased"
                                          data-toggle="tooltip" title="" class="badge bg-light-blue"
                                          data-original-title="PURCHASED">PURCHASED</span>
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-plus"></i>
                                    </button>

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <ul class="nav nav-stacked">
                                    <li><a>By: <span class="pull-right">{{transfer.company.name}}</span></a></li>
                                    <li><a>Id: <span class="pull-right">{{transfer.id}}</span></a></li>
                                    <li v-if="transfer.type =='arrival'"><a>From: <span class="pull-right"> {{ transfer.airport.name}}</span></a>
                                    </li>
                                    <li v-if="transfer.type =='arrival'"><a>To: <span class="pull-right">{{ transfer.transferable.name }}</span></a>
                                    </li>
                                    <li v-if="transfer.type =='departure'"><a>From: <span class="pull-right">{{ transfer.transferable.name }}</span></a>
                                    </li>
                                    <li v-if="transfer.type =='departure'"><a>To: <span class="pull-right">{{ transfer.airport.name}}</span></a>
                                    </li>
                                    <li><a>CarModel: <span class="pull-right">{{transfer.car_model.model_name + '( Seats: ' + transfer.car_model.max_seats + ' Bags:'
                            + transfer.car_model.max_bags + ' )'}}</span></a>
                                    </li>
                                    <li><a>#Guests: <span
                                            class="pull-right badge bg-aqua">{{transfer.number_of_booking}}</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="overlay" v-show="transfer.for_sale">
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                            <div class="box-footer">
                                <div v-if="transfer.for_sale && transfer.company_id != companyId">
                                    <a class="btn btn-primary pull-left"
                                       v-on:click="buy(transfer.id,companyId,$event)"
                                       :id="'buy_now'+transfer.id">Buy Now
                                    </a>
                                </div>
                                <div v-if="transfer.for_exchange && transfer.company_id != companyId">
                                    <a class="btn btn-primary pull-left"
                                       :id="'view_exchange'+transfer.id"
                                       v-on:click="viewExchange(transfer.exchange_id)">view Exchange
                                    </a>
                                </div>
                                <div v-if="transfer.for_exchange && transfer.company_id == companyId">
                                    <a class="btn btn-primary pull-left"
                                       v-on:click="viewOffers(transfer.exchange_id,index,$event)"
                                       :id="'view_offers'+transfer.exchange_id">view Offers
                                    </a>
                                    <a v-if="transfer.close_offers" class="btn btn-primary pull-left"
                                       v-on:click="closeOffers()"
                                       :id="'close_offers'+transfer.exchange_id">Close Offers
                                    </a>
                                </div>
                                <div v-if="transfer.company_id != companyId">
                                    <a v-if="offers.includes(transfer.id)" class="btn btn-primary"
                                       v-on:click="acceptOffer(transfer.offer_id)">Accept
                                    </a>
                                    <a v-if="offers.includes(transfer.id)" class="btn btn-danger"
                                       v-on:click="rejectOffer(transfer.offer_id)">Reject
                                    </a>
                                    <a v-if="offers.includes(transfer.id)" class="btn btn-default"
                                       v-on:click="hideOffer(index)">Hide
                                    </a>
                                </div>
                                <div v-if="transfer.company_id == companyId && transfer.for_sale">
                                    <a class="btn btn-default pull-right"
                                       v-on:click="undo(transfer.store_id)"
                                       :id="'undo'+transfer.store_id">Undo
                                    </a>
                                </div>
                                <!--<div v-if="transfer.corporate_id == corporateId && !transfer.for_sale">-->
                                <!--<a class="btn btn-default pull-right"-->
                                <!--v-on:click="forSale(transfer.id)"-->
                                <!--:id="'for_sale'+transfer.id">For Sale-->
                                <!--</a>-->
                                <!--</div>-->
                            </div>

                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="timeline-footer">
                <div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy"
                     infinite-scroll-distance="10">
                </div>
            </div>
        </div>
        <!--<modal v-if="showModal" @close="showModal = false"> &lt;!&ndash; Hardcoded the url for now &ndash;&gt;-->
        <model-component  v-if="showModal" @close="showModal = false"
                         :exchange_id="exchange_id" :company_id="companyId">
        </model-component>
        <!--</modal>-->
    </div>

</template>

<script>
    import moment from 'moment'
    import Datepicker from 'vuejs-datepicker';

    export default {
        props: ['company-id', 'admin-id'],
        data: function () {
            return {
                loading: 1,
                busy: false,
                transfers: [],
                page: 1,
                moment: moment,
                ids: [],
                checkedMine: 0,
                from_date: '',
                to_date: '',
                companies: [],
                selected_company: '',
                purchased: '',
                sold: '',
                transfer: {},
                offers: [],
                showModal: false,
                exchange_id: 0
            }
        },
        created: function () {

        },
        methods: {
            loadMore: function () {
                let vm = this;
                vm.loading = 1;
                vm.busy = true;
                vm.getAllTransfers();
            },
            getAllTransfers: function () {
                let vm = this;
                axios.get('/transfers/transfers-offered-forSale?page=' + this.page)
                    .then(function (response) {

                        vm.loading = 0;
                        vm.page += 1;
                        $.each(response.data, function (key, value) {
                            vm.transfers.push(value);
                            vm.ids.push(value['id']);
                        });
                        if (response.data.length === 0) {
                            vm.busy = true;
                        }
                        else {
                            vm.busy = false;
                        }
//                        vm.busy = false;
                    })
                    .catch(function (error) {
                        vm.busy = false;
                    });

//                // Registered client on public channel to listen to MessageSent event


//                Echo.channel('undo-for-sale-channel').listen('TransferRemoved', ({transfer}) => {
//                    if (transfer.company_id == vm.companyId) {
//                        transfer['p_class'] = 'col-md-6 col-md-offset-6';
//                        transfer['class'] = 'box-success';
//                        let i = this.transfers.map(item => item.id).indexOf(transfer.id);
//                        this.$set(this.transfers[i], 'show_span', false);
//                    }
//                    else {
//                        transfer['p_class'] = 'col-md-6';
//                        transfer['class'] = 'box-danger';
//                        var index = this.ids.indexOf(transfer.id);
//                        this.transfers.splice(index, 1);
//                    }
//
//                });
            },
            getTransferById(data) {
                let vm = this;
                axios.get('/transfers/' + data.id + '/get')
                    .then(function (response) {
                        vm.transfer = response.data;
                        var name_action = data.name;
                        if (name_action === 'created_transfer') {
                            if (vm.transfer.company_id === vm.companyId) {
                                vm.transfer['p_class'] = 'col-md-6 col-md-offset-6';
                                vm.transfer['class'] = 'box-success';
                            }
                            else {
                                vm.transfer['p_class'] = 'col-md-6';
                                vm.transfer['class'] = 'box-danger';
                            }
                            vm.transfer['show_span'] = true;
                            vm.transfers.push(vm.transfer);
                            vm.ids.push(value['id']);
                        }
                        if (name_action === 'updated_transfer') {
                            let index = vm.ids.indexOf(vm.transfer.id);
                            if (vm.transfer.deleted_at != null) {
                                vm.transfers.splice(index, 1);
                            } else {
                                vm.$set(vm.transfers[index], 'updated', true);
                            }
                        }

                        vm.playSound();
                    })
                    .catch(function (error) {
                        return error;
                    });
            },
            viewOffers(exchange_id, index, event) {
                let vm = this;
                axios.get('/get/' + exchange_id + '/offers/')
                    .then(function (response) {
                        let arr = {};
//                        vm.$nextTick(function() {
//                            vm.exchange_open = vm.transfers[index];
//                            $('#view_offers5').hide();
//                            $('#close_offers5').show();
//                        });

                        $.each(response.data, function (key, value) {
                            vm.transfers.push(value);
                            vm.offers.push(value['id']);
                        });
//                        $('#view_offers5').hide();
//                        $('#close_offers5').show();
//
//                        this.$set(vm.transfers[index], 'close_offers', true);

//                        $('#close_offers' + exchange_id).display('block');
                    })
                    .catch(function (error) {
                        return error;
                    });
            }, closeOffers() {
                console.log(0);
            },
            listenForActivity() {
                let vm = this;
                Echo.private('activity.admin.' + vm.adminId)
                    .listen('.activity.created', e => this.getTransferById(e.data));
            },
            getAllCompanies: function () {
                let vm = this;
                axios.get('getAll')
                    .then(function (response) {
                        vm.companies = response.data;
                    });
            },
            buy: function (transfer_id, company_id, event) {
                this.$confirm("Are you sure?").then(() => {
                    axios.post('/store/transfer/' + transfer_id + '/buy', {company_id: company_id})
                        .then((response) => {
                            this.$alert("Purchasing has done successfully", "", 'success');
                            let i = this.transfers.map(item => item.id).indexOf(transfer_id);
                            this.$set(this.transfers[i], 'for_sale', false);
                            this.$set(this.transfers[i], 'purchased', true);
                            $('#buy_now' + transfer_id).hide();

                        })
                });
            },
            undo: function (store_id) {
                this.$confirm("Are you sure?").then(() => {
                    axios.post('/undo/offer-for-sale/transfer', {id: store_id})
                        .then((response) => {
                            this.$alert("undo has done successfully", "", 'success');
                            $('#undo' + store_id).hide();
                            let i = this.transfers.map(item => item.store_id).indexOf(store_id);
                            this.$set(this.transfers[i], 'for_sale', false);

                        })
                });
            },
            forSale: function (transfer_id) {

            },
            acceptOffer: function (offer_id) {
                let vm = this;
                axios.get('/offer/' + offer_id + '/accepted/')
                    .then(function (response) {

                    })
                    .catch(function (error) {
                        return error;
                    });
            },
            rejectOffer: function (offer_id) {
                let vm = this;
                axios.get('/offer/' + offer_id + '/rejected/')
                    .then(function (response) {

                    })
                    .catch(function (error) {
                        return error;
                    });
            },
            hideOffer: function (index) {
                console.log(index);
                this.transfers.splice(index, 1);
            },
            getPreviousItemIndex: function (entry) {
                var currentIndex = this.transfers.indexOf(entry);
                return (currentIndex - 1);
            },
            getPreviousItemDate: function (entry) {
                if (this.getPreviousItemIndex(entry) != -1) {
                    var previousDate = moment(this.transfers[this.getPreviousItemIndex(entry)].transfer_start_time).format('YYYY-MM-DD');
                    return previousDate;
                } else {
                    return -1;
                }

            },
            getTimeDifference: function (entry) {
                var entryDate = moment(entry.transfer_start_time).format('YYYY-MM-DD');
                var previousDate = this.getPreviousItemDate(entry);
                if (previousDate == -1) {
                    return false;
                } else {
                    return moment(entryDate).isSame(previousDate);
                }

            },
            playSound() {
                var audio = new Audio('http://127.0.0.1:8000/assets/notify.mp3');
                audio.play();
            },
            reset: function () {
                this.selected_company = '';
                this.checkedMine = 0;
                this.from_date = '';
                this.to_date = '';
                this.purchased = '';
                this.sold = '';
            },
            viewExchange: function (exchange_id) {
                this.showModal = true;
                this.exchange_id = exchange_id
            }
        },
        computed: {
            orderedTransfers: function () {
                return _.orderBy(this.transfers, 'transfer_start_time')
            },
            filteredTransfers: function () {
                var vm = this;

                if (this.checkedMine == "" && this.from_date == "" && this.selected_company == ""
                    && this.sold == "" && this.purchased == ""
                ) {
                    this.transfers = [];
                    this.page = 1;
                    this.loading = 1;
                    this.busy = true;
                    return this.getAllTransfers();
                }

                if (this.checkedMine != "") {
                    return this.transfers = this.transfers.filter(function (transfer) {
                        return transfer.company_id == vm.companyId;
                    });
                }
                if (this.from_date != "" && this.to_date != "") {
                    var from_date = moment(this.from_date).format('YYYY-MM-DD');
                    var to_date = moment(this.to_date).format('YYYY-MM-DD');

                    return this.transfers = this.transfers.filter(function (transfer) {
                        var start_date = moment(transfer.transfer_start_time).format('YYYY-MM-DD');
                        return start_date >= from_date &&
                            start_date <= to_date;
                    });
                }
                if (this.selected_company != "") {

                    return this.transfers = this.transfers.filter(function (transfer) {
                        return transfer.company_id == vm.selected_company;
                    });
                }
                if (this.purchased != "") {
                    return this.transfers = this.transfers.filter(function (transfer) {
                        return transfer.purchased == 'purchased';
                    });
                }
                if (this.sold != "") {
                    return this.transfers = this.transfers.filter(function (transfer) {
                        return transfer.sold == 'Sold';
                    });
                }
            },
        },
        mounted() {
            console.log('Component mounted.');
            this.getAllCompanies();
            this.loading = 0;
            this.listenForActivity();

            //Subscribe to the channel we specified in our Adonis Application
//            let channel = Pusher.subscribe('admin-channel');
//
//            channel.bind('TransferCreated', (data) => {
//                console.log('listen to .' + data)
//            })
        },
        components: {
            Datepicker
        }
    }
</script>