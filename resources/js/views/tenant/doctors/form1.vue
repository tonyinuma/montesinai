<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombres</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc. Identidad</label>
                            <el-select v-model="form.identity_document_type_id" filterable
                                       popper-class="el-select-identity_document_type">
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id"
                                   v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.document_number}">
                            <label class="control-label">Numero Documento</label>
                            <el-input v-model="form.document_number"></el-input>
                            <small class="form-control-feedback" v-if="errors.document_number"
                                   v-text="errors.document_number[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.medical_department_id}">
                            <label class="control-label">Departamento Médico</label>
                            <el-select v-model="form.medical_department_id" filterable
                                       popper-class="el-select-identity_document_type">
                                <el-option v-for="option in medical_departments" :key="option.id" :value="option.id"
                                           :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.medical_department_id"
                                   v-text="errors.medical_department_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.mobile_phone}">
                            <label class="control-label">Celular</label>
                            <el-input v-model="form.mobile_phone"></el-input>
                            <small class="form-control-feedback" v-if="errors.mobile_phone"
                                   v-text="errors.mobile_phone[0]"></small>
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
            resource: 'doctors',
            errors: {},
            form: {},
            identity_document_types: [],
            medical_departments: [],
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.identity_document_types = response.data.identity_document_types
                this.medical_departments = response.data.medical_departments
            })
        await this.initForm()
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                name: null,
                identity_document_type_id: null,
                medical_department_id: null,
                document_number: null,
                mobile_phone: null,
                levels: [],
            }
        },
        create() {
            this.titleDialog = (this.recordId) ? 'Editar Doctor' : 'Nuevo Doctor'
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
