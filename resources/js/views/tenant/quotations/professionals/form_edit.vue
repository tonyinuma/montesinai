<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" :close-on-click-modal="false"
               :close-on-press-escape="false" :show-close="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.medical_professional_id}">
                            <label class="typo__label">Especialidad</label>
                            <multiselect v-model="medical_speciality_id" deselect-label="Can't remove this value"
                                         track-by="name"
                                         label="name" placeholder="Select one" :options="specialities"
                                         :searchable="false" @select="getProfessionals"
                                         :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.medical_professional_id}">
                            <label class="typo__label">Personal Medico</label>
                            <multiselect v-model="appointment_professionals" tag-placeholder="Agregar a la Lista"
                                         placeholder="Buscar o agregar Personal" label="name" track-by="id"
                                         :options="professionals" :multiple="true" :taggable="true"></multiselect>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%">
                            <thead>
                            <tr width="100%">
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Especialidad</th>
                                <th>DNI</th>
                                <th>Celular</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in appointment_professionals" :key="index" width="100%">
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.name }}</td>
                                <td>{{ row.medical_speciality }}</td>
                                <td>{{ row.document_number }}</td>
                                <td>{{ row.mobile_phone }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close(true)">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>

    </el-dialog>
</template>

<script>

import Multiselect from 'vue-multiselect'

export default {
    components: {Multiselect},
    props: ['showDialog', 'professional', 'recordId'],
    data() {
        return {
            titleDialog: 'Editar Personal Médico',
            resource: 'appointments',
            loading_submit: false,
            errors: {},
            professional_id: null,
            loading_search: false,
            professionals: [],
            appointment_professionals: [],
            specialities: [],
            form: {
                professionals_data: [],
                appointment_id: null,
            },
            medical_speciality_id: null,
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.specialities = response.data.specialities
                this.initRecord()
            })
    },
    methods: {
        initRecord() {
            this.$http.get(`/${this.resource}/professionals/data_edit/${this.recordId}`)
                .then(response => {
                    this.appointment_professionals = response.data.appointment_professionals
                    this.form.appointment_id = response.data.appointment_id
                })
        },
        create() {},
        async getProfessionals(row) {
            await this.$http.get(`/${this.resource}/list_speciality/${row.id}`)
                .then(response => {
                    if (response.data.professionals) {
                        this.professionals = response.data.professionals
                        this.professionals.forEach(data => {
                            switch (data.medical_speciality_id) {
                                case 1:
                                    data.medical_speciality = 'ENFERMERO/A'
                                    break;
                                case 2:
                                    data.medical_speciality = 'ANESTECIOLOGO/A'
                                    break;
                                case 3:
                                    data.medical_speciality = 'TERAPEUTA'
                                    break;
                                case 4:
                                    data.medical_speciality = 'PSICOLOGO/A'
                                    break;
                            }
                        });
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
        },
        async submit() {
            this.form.professionals_data = this.appointment_professionals;
            await this.$http.post(`/${this.resource}/update_professional`, this.form)
                .then(response => {
                    if (response.data.success) {
                        console.log(response.data);
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
            this.initRecord()
            this.errors = {}
            this.$emit('update:showDialog', false)
        },
    }
}
</script>
