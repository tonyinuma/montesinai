<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" :close-on-click-modal="false"
               :close-on-press-escape="false" :show-close="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="identity_document_type_id" filterable
                                       popper-class="el-select-identity_document_type" dusk="identity_document_type_id">
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id"
                                   v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">Número <span class="text-danger">*</span></label>

                            <div v-if="api_service_token !== false">
                                <x-input-patient-service :identity_document_type_id="identity_document_type_id"
                                                         v-model="patient.number"
                                                         @search="searchNumber"></x-input-patient-service>
                            </div>
                            <div v-else>
                                <el-input v-model="patient.number" :maxlength="maxLength" dusk="number">
                                    <template v-if="identity_document_type_id === '1'">
                                        <el-button type="primary" slot="append" :loading="loading_search"
                                                   icon="el-icon-search" @click.prevent="searchCustomer">
                                            RENIEC
                                        </el-button>
                                    </template>
                                </el-input>
                            </div>

                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombres y Apellidos</label>
                            <el-input v-model="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.occupation}">
                            <label class="control-label">Ocupación</label>
                            <el-input v-model="patient.occupation"></el-input>
                            <small class="form-control-feedback" v-if="errors.occupation"
                                   v-text="errors.occupation[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.room_type}">
                            <label class="control-label">Tipo habitación</label>
                            <el-select v-model="patient.room_type" filterable>
                                <el-option v-for="option in room_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.room_type"
                                   v-text="errors.room_type[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.mobile_phone}">
                            <label class="control-label">Celular</label>
                            <el-input v-model="patient.mobile_phone"></el-input>
                            <small class="form-control-feedback" v-if="errors.mobile_phone"
                                   v-text="errors.mobile_phone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.nationality}">
                            <label class="control-label">Nacionalidad</label>
                            <el-input v-model="patient.nationality"></el-input>
                            <small class="form-control-feedback" v-if="errors.nationality"
                                   v-text="errors.nationality[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.origin}">
                            <label class="control-label">Procedencia</label>
                            <el-input v-model="patient.origin"></el-input>
                            <small class="form-control-feedback" v-if="errors.origin" v-text="errors.origin[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_entry}">
                            <label class="control-label">Fecha Ingreso</label>
                            <el-date-picker v-model="patient.date_entry" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_entry"
                                   v-text="errors.date_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_entry}">
                            <label class="control-label">Hora Ingreso</label>
                            <el-time-picker
                                v-model="patient.time_entry" value-format="HH:mm">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.time_entry"
                                   v-text="errors.time_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_exit}">
                            <label class="control-label">Fecha Salida</label>
                            <el-date-picker v-model="patient.date_exit" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_exit"
                                   v-text="errors.date_exit[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_exit}">
                            <label class="control-label">Hora Salida</label>
                            <el-time-picker
                                v-model="patient.time_exit" value-format="HH:mm">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.time_exit"
                                   v-text="errors.time_exit[0]"></small>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close(true)">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

export default {
    components: {},
    props: ['showDialog'],
    data() {
        return {
            titleDialog: 'Cita y Datos personales del Paciente',
            resource: 'appointments',
            loading_submit: false,
            api_service_token: false,
            errors: {},
            identity_document_types: [],
            cards_brand: [],
            room_types: [],
            identity_document_type_id: '1',
            patient_id: null,
            name: null,
            loading_search: false,
            patient: {
                date_entry: moment().format("YYYY-MM-DD"),
                time_entry: moment().format("HH:mm"),
                date_exit: moment().format("YYYY-MM-DD"),
            }
        }
    },
    computed: {
        maxLength: function () {
            if (this.identity_document_type_id === '1') {
                return 8
            } else {
                return 12
            }
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.identity_document_types = response.data.identity_document_types
                this.api_service_token = response.data.api_service_token
                this.room_types = response.data.room_types
            })
    },
    methods: {
        initVars() {
            this.name = null
            this.identity_document_type_id = '1'
        },
        create() {
            if (_.isEmpty(this.patient)) {
                this.initVars()
            }
        },
        async submit() {

            this.patient.name = this.name
            this.patient.identity_document_type_id = this.identity_document_type_id

            this.loading_submit = true;

            await this.$http.post(`/${this.resource}/validate_patient`, this.patient)
                .then(response => {
                    if (response.data.success) {
                        this.$emit('addPatient', this.patient);
                        this.close(false)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close(flag) {
            if (flag) this.$emit('addPatient', {});
            this.errors = {}
            this.$emit('update:showDialog', false)
        },
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        async searchServiceNumberByType() {
            if (this.patient.number === '') {
                this.$message.error('Ingresar el número a buscar')
                return
            }
            let identity_document_type_name = ''
            if (this.identity_document_type_id === '1') {
                identity_document_type_name = 'dni'
            }
            this.loading_search = true
            let response = await this.$http.get(`/services/${identity_document_type_name}/${this.patient.number}`)
            if (response.data.success) {
                let data = response.data.data
                this.name = data.name

            } else {
                this.$message.error(response.data.message)
            }
            this.loading_search = false
        },
        searchNumber(data) {
            if (data) {
                if (data.id) {
                    this.name = data.name
                    this.patient.patient_id = data.id
                    this.patient.mobile_phone = data.mobile_phone
                    this.patient.occupation = data.occupation
                    this.patient.nationality = data.nationality
                } else {
                    this.name = data.nombre_completo
                    this.patient.patient_id = 0
                }
            }
        }
    }
}
</script>
