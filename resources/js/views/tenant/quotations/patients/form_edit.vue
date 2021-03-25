<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" :close-on-click-modal="false"
               :close-on-press-escape="false" :show-close="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="form.identity_document_type_id" filterable
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
                                                         v-model="form.number"
                                                         @search="searchNumber"></x-input-patient-service>
                            </div>
                            <div v-else>
                                <el-input v-model="form.number" :maxlength="maxLength" dusk="number">
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
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.occupation}">
                            <label class="control-label">Ocupación</label>
                            <el-input v-model="form.occupation"></el-input>
                            <small class="form-control-feedback" v-if="errors.occupation"
                                   v-text="errors.occupation[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.room_type}">
                            <label class="control-label">Tipo habitación</label>
                            <el-select v-model="form.room_type" filterable>
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
                            <el-input v-model="form.mobile_phone"></el-input>
                            <small class="form-control-feedback" v-if="errors.mobile_phone"
                                   v-text="errors.mobile_phone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.nationality}">
                            <label class="control-label">Nacionalidad</label>
                            <el-input v-model="form.nationality"></el-input>
                            <small class="form-control-feedback" v-if="errors.nationality"
                                   v-text="errors.nationality[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.origin}">
                            <label class="control-label">Procedencia</label>
                            <el-input v-model="form.origin"></el-input>
                            <small class="form-control-feedback" v-if="errors.origin" v-text="errors.origin[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_entry}">
                            <label class="control-label">Fecha Ingreso</label>
                            <el-date-picker v-model="form.date_entry" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_entry"
                                   v-text="errors.date_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_entry}">
                            <label class="control-label">Hora Ingreso</label>
                            <el-time-picker
                                v-model="form.time_entry" value-format="HH:mm">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.time_entry"
                                   v-text="errors.time_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_exit}">
                            <label class="control-label">Fecha Salida</label>
                            <el-date-picker v-model="form.date_exit" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_exit"
                                   v-text="errors.date_exit[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_exit}">
                            <label class="control-label">Hora Salida</label>
                            <el-time-picker
                                v-model="form.time_exit" value-format="HH:mm">
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
                <el-button type="primary" native-type="submit" :loading="loading_submit">Actualizar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

export default {
    components: {},
    props: ['showDialog', 'recordId'],
    data() {
        return {
            titleDialog: 'Editar Cita/Paciente',
            resource: 'appointments',
            loading_submit: false,
            api_service_token: false,
            errors: {},
            identity_document_types: [],
            cards_brand: [],
            room_types: [],
            identity_document_type_id: '1',
            patient_id: null,
            form: {},
            appointment_id: null,
            loading_search: false,
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
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.identity_document_types = response.data.identity_document_types
                this.api_service_token = response.data.api_service_token
                this.room_types = response.data.room_types
                this.initRecord()
            })
    },
    methods: {
        initRecord() {
            this.$http.get(`/${this.resource}/data_edit/${this.recordId}`)
                .then(response => {
                    let data_edit = response.data.appointment
                    this.appointment_id = data_edit.id;
                    this.form.patient_id = data_edit.patient.id;
                    this.form.identity_document_type_id = data_edit.patient.identity_document_type_id;
                    this.form.number = data_edit.patient.document_number.toString();
                    this.form.name = data_edit.patient.name;
                    this.form.occupation = data_edit.patient.occupation;
                    this.form.nationality = data_edit.patient.nationality;
                    this.form.mobile_phone = data_edit.patient.mobile_phone;
                    this.form.room_type = data_edit.room_type;
                    this.form.origin = data_edit.origin;
                    this.form.date_entry = data_edit.admission_date;
                    this.form.time_entry = data_edit.admission_time;
                    this.form.date_exit = data_edit.departure_date;
                    this.form.time_exit = data_edit.departure_time;
                })
        },
        initForm() {
            this.errors = {}
            this.form = {
                patient_id: null,
                identity_document_type_id: '1',
                number: null,
                name: null,
                occupation: null,
                mobile_phone: null,
                room_type: null,
                nationality: null,
                origin: null,
                date_entry: moment().format("YYYY-MM-DD"),
                time_entry: moment().format("HH:mm"),
                date_exit: moment().format("YYYY-MM-DD"),
                time_exit: null,
            }
        },
        create() {
        },
        async submit() {
            if (confirm("Seguro que desea Actualizar?")) {
                this.loading_submit = true;
                this.form.appointment_id = this.appointment_id;

                await this.$http.post(`/${this.resource}/update_patient`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            alert("Actualizado")
                            this.close()
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
            }
        },
        close(flag) {
            this.errors = {}
            this.initRecord()
            this.$emit('update:showDialog', false)
        },
        searchNumber(data) {
            this.initForm()
            if (data.id) {
                this.form.number = data.number.toString()
                this.form.name = data.name
                this.form.patient_id = data.id
                this.form.mobile_phone = data.mobile_phone
                this.form.occupation = data.occupation
                this.form.nationality = data.nationality
            } else {
                this.form.number = data.numero
                this.form.name = data.nombre_completo
                this.form.patient_id = 0
            }
        }
    }
}
</script>
