<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name"
                                   v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description"
                                   v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.active}">
                            <label class="control-label">Estado</label>
                            <el-switch
                                style="display: block"
                                v-model="form.active"
                                @change="checkStatus(row)"
                            ></el-switch>
                            <small class="form-control-feedback" v-if="errors.active"
                                   v-text="errors.active[0]"></small>
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
            resource: 'medical_departments',
            errors: {},
            form: {},
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {

            })
        await this.initForm()
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                name: null,
                description: null,
                active: null,
            }
        },
        create() {
            this.titleDialog = (this.recordId) ? 'Editar Departamento Médico' : 'Nuevo Departamento Médico'
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
