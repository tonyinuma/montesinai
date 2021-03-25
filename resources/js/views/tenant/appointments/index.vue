<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Citas Médicas</span></li>
            </ol>
            <div class="right-wrapper pull-right">

                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" v-if="typeUser != 'integrator'" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>

                <!--<button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>-->
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Lista de Citas Médicas</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Ingresado Por</th>
                            <th>Paciente</th>
                            <th>Cliente</th>
                            <th>Fecha de Ingreso</th>
                            <th>Fecha de Salida</th>
                            <th>Tipo Habitacion</th>
                            <th>Precio</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.allocator_user }}</td>
                            <td>{{ row.patient }}</td>
                            <td>{{ row.cliente }}</td>
                            <td>{{ row.admission_date }}</td>
                            <td>{{ row.departure_date }}</td>
                            <td>{{ row.room_type }}</td>
                            <td>{{ row.price }}</td>
                            <td>{{ row.status }}</td>
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <appointments-form :showDialog.sync="showDialog"
                        :typeUser="typeUser"
                        :recordId="recordId"></appointments-form>
        </div>
    </div>
</template>

<script>

    import AppointmentsForm from './form1.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['typeUser'],
        mixins: [deletable],
        components: {AppointmentsForm},
        data() {
            return {
                showDialog: false,
                resource: 'appointments',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
