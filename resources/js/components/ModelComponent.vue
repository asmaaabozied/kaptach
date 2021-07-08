<!--<style>-->
<!--.modal-mask {-->
<!--position: fixed;-->
<!--z-index: 9998;-->
<!--top: 0;-->
<!--left: 0;-->
<!--width: 100%;-->
<!--height: 100%;-->
<!--background-color: rgba(0, 0, 0, 0.5);-->
<!--display: table;-->
<!--transition: opacity 0.3s ease;-->
<!--}-->

<!--.modal-wrapper {-->
<!--display: table-cell;-->
<!--vertical-align: middle;-->
<!--}-->

<!--.modal-container {-->
<!--width: 300px;-->
<!--margin: 0px auto;-->
<!--padding: 20px 30px;-->
<!--background-color: #fff;-->
<!--border-radius: 2px;-->
<!--box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);-->
<!--transition: all 0.3s ease;-->
<!--font-family: Helvetica, Arial, sans-serif;-->
<!--}-->

<!--.modal-header h3 {-->
<!--margin-top: 0;-->
<!--color: #42b983;-->
<!--}-->

<!--.modal-body {-->
<!--margin: 20px 0;-->
<!--}-->

<!--.modal-default-button {-->
<!--float: right;-->
<!--}-->

<!--/*-->
<!--* The following styles are auto-applied to elements with-->
<!--* transition="modal" when their visibility is toggled-->
<!--* by Vue.js.-->
<!--*-->
<!--* You can easily play with the modal transition by editing-->
<!--* these styles.-->
<!--*/-->

<!--.modal-enter {-->
<!--opacity: 0;-->
<!--}-->

<!--.modal-leave-active {-->
<!--opacity: 0;-->
<!--}-->

<!--.modal-enter .modal-container,-->
<!--.modal-leave-active .modal-container {-->
<!-- -webkit-transform: scale(1.1);-->
<!--transform: scale(1.1);-->
<!--}-->

<!--</style>-->
<template>
    <modal name="my-first-modal"
           :adaptive="true"
           :reset="true"
           :scrollable="true"
           height="auto"
           @before-close="beforeClose">
        <div class="modal-dialog">
            <!--<div class="modal-content">-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" @click="$emit('close')">&times;</button>
                <h4 class="modal-title">
                    <slot name="header">
                        Exchange
                    </slot>
                </h4>
            </div>
            <div class="modal-body">
                <slot name="body">
                    <table class="table table-bordered table-striped">
                        <tr v-for="attribute in attributes" v-bind:key="attribute.id">

                            <td v-if="attribute.airport">Airport</td>
                            <td v-if="attribute.airport">{{attribute.airport.name}}</td>

                            <td v-if="attribute.from_date">From Date</td>
                            <td v-if="attribute.from_date">{{attribute.from_date}}</td>


                            <td v-if="attribute.to_date">To Date</td>
                            <td v-if="attribute.to_date">{{attribute.to_date}}</td>

                            <td v-if="attribute.type">Type</td>
                            <td v-if="attribute.type">{{attribute.type}}</td>


                        </tr>
                    </table>

                    <div class="text-uppercase text-bold">id selected: {{selected}}</div>
                    <table id="list" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>
                                <label class="form-checkbox">
                                    <input type="checkbox" v-model="selectAll" @click="select">
                                    <i class="form-icon"></i></label>
                            </th>
                            <th>Id</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Airport</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="value in rows" v-bind:key="value.id">
                            <td>
                                <label class="form-checkbox">
                                    <input type="checkbox" :value="value.id" v-model="selected">
                                    <i class="form-icon"></i>
                                </label>
                            </td>
                            <td>{{ value.id }}</td>
                            <td>{{ value.type }}</td>
                            <td>{{ value.transfer_start_time }}</td>
                            <td>{{ value.airport.name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </slot>
            </div>
            <div class="modal-footer">
                <!--<datatable :columns="columns" :data="rows" class="table table-bordered table-striped" v-model="selected"></datatable>-->

                <slot name="footer">
                    <button class="btn btn-default" @click="$emit('close')">
                        OK
                    </button>
                    <button class="btn btn-success" v-on:click="applyOffer()">
                        Apply offer
                    </button>
                </slot>
            </div>
            <!--</div>-->
        </div>
    </modal>
</template>

<script>
    export default {
        props: ['exchange_id', 'company_id'],
        data: function () {
            return {
                exchange: {},
                attributes: [],
                columns: [
                    {label: 'Id', field: 'id'},
                    {label: 'Id', field: 'id'},
                    {label: 'Type', field: 'type'},
                    {label: 'Date', field: 'transfer_start_time'},
                    {label: 'Airport', field: 'airport.name'},
                ],
                rows: [],
                selected: [],
                selectAll: false
            }
        },
        methods: {
            select() {
                this.selected = [];
                if (!this.selectAll) {
                    for (let i in this.rows) {
                        this.selected.push(this.rows[i].id);
                    }
                }
            },
            applyOffer() {
                let vm = this;
                if (this.selected.length) {
                    this.$confirm("Are you sure?").then(() => {
                        axios.post('/apply/' + vm.exchange_id + '/offer', {selected: vm.selected})
                            .then((response) => {
                                this.$alert("Offer added successfully", "", 'success');
                                this.hide();
                            })
                            .catch(errors => {
                                console.log(errors);
                            });
                    });
                }

            },
            getExchangeInfo: function () {
                let vm = this;
                axios.get('/exchange/' + vm.exchange_id + '/info')
                    .then(function (response) {
                        vm.exchange = response.data;
                        vm.attributes = response.data.attributes;
                    })
                    .catch(function (error) {
                        return error;
                    });
            },
            getTransfers: function () {
                let vm = this;
                axios.get('/get/' + vm.exchange_id + '/transfers')
                    .then(function (response) {
                        vm.rows = response.data;
                    })
                    .catch(function (error) {
                        return error;
                    });
            },
            show() {
                this.$modal.show('my-first-modal');
            },
            hide() {
                this.$modal.hide('my-first-modal');
            }
        },
        mounted() {
            this.getExchangeInfo();
            this.getTransfers();
            console.log('Component modal mounted.');
            console.log(this.company_id)
            this.show()
        }
    }
</script>