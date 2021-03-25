<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.patient_id}">
                            <label class="control-label">Paciente</label>
                            <el-select v-model="form.patient_id" filterable
                                       popper-class="el-select-identity_document_type">
                                <el-option v-for="option in patients" :key="option.id" :value="option.id"
                                           :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.patient_id"
                                   v-text="errors.patient_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.doctor}">
                            <label class="control-label">Cliente / Doctor</label>
                            <el-input v-model="form.doctor" :readonly="true"></el-input>
                            <small class="form-control-feedback" v-if="errors.doctor"
                                   v-text="errors.doctor[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.admission_date}">
                            <label class="control-label">Fecha Ingreso</label>
                            <el-date-picker v-model="form.admission_date" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.admission_date"
                                   v-text="errors.admission_date[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.admission_time}">
                            <label class="control-label">Hora Ingreso</label>
                            <el-time-picker
                                v-model="form.admission_time" value-format="HH:mm:ss">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.admission_time"
                                   v-text="errors.admission_time[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.departure_date}">
                            <label class="control-label">Fecha Salida</label>
                            <el-date-picker v-model="form.departure_date" type="date" value-format="yyyy-MM-dd"
                                            :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.departure_date"
                                   v-text="errors.departure_date[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.departure_time}">
                            <label class="control-label">Hora Salida</label>
                            <el-time-picker
                                v-model="form.departure_time" value-format="HH:mm:ss">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.departure_time"
                                   v-text="errors.departure_time[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.room_type}">
                            <label class="control-label">Tipo Habitacion</label>
                            <el-select v-model="form.room_type" filterable
                                       popper-class="el-select-identity_document_type">
                                <el-option v-for="option in room_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.room_type"
                                   v-text="errors.room_type[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.status}">
                            <label class="control-label">Estado</label>
                            <el-select v-model="form.status" filterable popper-class="el-select-identity_document_type">
                                <el-option v-for="option in states" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.status" v-text="errors.status[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.origin}">
                            <label class="control-label">Procedencia</label>
                            <el-input v-model="form.origin"></el-input>
                            <small class="form-control-feedback" v-if="errors.origin"
                                   v-text="errors.origin[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.observation}">
                            <label class="control-label">
                                Observacion
                            </label>
                            <el-input type="textarea" autosize v-model="form.observation"></el-input>
                            <small class="form-control-feedback" v-if="errors.observation"
                                   v-text="errors.observation[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

import {EventBus} from '../../../helpers/bus'

export default {
    props: ['showDialog', 'recordId', 'typeUser'],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            resource: 'appointments',
            errors: {},
            form: {},
            patients: [],
            doctors: [],
            medical_services: [],
            states: [],
            room_types: [],
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.patients = response.data.patients
                this.doctors = response.data.doctors
                this.medical_services = response.data.medical_services
                this.states = response.data.states
                this.room_types = response.data.room_types
            })
        await this.initForm()
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                patient_id: null,
                doctor_id: null,
                medical_service_id: null,
                appointment_date: null,
                observation: null,
                status: null,
            }
        },
        create() {
            this.titleDialog = (this.recordId) ? 'Editar Cita' : 'Nueva Cita'
            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                    })
            }
        },
        submit() {
            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message);
                        console.log(error)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
