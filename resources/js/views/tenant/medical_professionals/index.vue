<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Profesionales Médicos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" v-if="typeUser != 'integrator'" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Profesionales</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Especialidad</th>
                            <th>Nombre</th>
                            <th>Tipo Documento</th>
                            <th>Número Documento</th>
                            <th>Celular</th>
                            <th>Disponibilidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.medical_speciality }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.document_type }}</td>
                            <td>{{ row.document_number }}</td>
                            <td>{{ row.mobile_phone }}</td>
                            <td>
                                <el-switch
                                    style="display: block"
                                    v-model="row.availability"
                                    @change="checkStatus(row)"
                                ></el-switch>
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <medical-professionals-form :showDialog.sync="showDialog"
                        :typeUser="typeUser"
                        :recordId="recordId"></medical-professionals-form>
        </div>
    </div>
</template>

<script>

    import MedicalProfessionalsForm from './form1.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['typeUser'],
        mixins: [deletable],
        components: {MedicalProfessionalsForm},
        data() {
            return {
                showDialog: false,
                resource: 'medical_professionals',
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
            },
            checkStatus(row) {
                this.$http
                    .post(`${this.resource}/check_status`, row)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message);
                            this.$eventHub.$emit("reloadData");
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 500) {
                            this.$message.error(error.response.data.message);
                        } else {
                            console.log(error.response);
                        }
                    })
            },
        }
    }
</script>
