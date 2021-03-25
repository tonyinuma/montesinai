<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Pacientes</span></li>
            </ol>
            <div class="right-wrapper pull-right">

                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" v-if="typeUser != 'integrator'" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>

                <!--<button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>-->
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Pacientes</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Tipo Documento</th>
                            <th>Número Documento</th>
                            <th>celular</th>
                            <th>Edad</th>
                            <th>Estado Civil</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.document_type }}</td>
                            <td>{{ row.document_number }}</td>
                            <td>{{ row.mobile_phone }}</td>
                            <td>{{ row.age }}</td>
                            <td>{{ row.marital_status }}</td>
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <patients-form :showDialog.sync="showDialog"
                        :typeUser="typeUser"
                        :recordId="recordId"></patients-form>
        </div>
    </div>
</template>

<script>

    import PatientsForm from './form1.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['typeUser'],
        mixins: [deletable],
        components: {PatientsForm},
        data() {
            return {
                showDialog: false,
                resource: 'patients',
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
