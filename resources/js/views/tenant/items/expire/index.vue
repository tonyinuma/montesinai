<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos Vencidos - Por Vencer</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template v-if="typeUser === 'admin'">
                    <div class="btn-group flex-wrap">
                        <button type="button" class="btn btn-custom btn-sm  mt-2 mr-5" @click.prevent="clickExport()">
                            <i class="fa fa-download"></i> Exportar
                            <span class="caret"></span></button>
                    </div>
                </template>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de productos vencidos o por vencer</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Tipo Item (Artículo)</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Vencimiento</th>
                        <th>Estado</th>
                        <th>Cód. SUNAT</th>
                        <th class="text-left">Stock</th>
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th v-if="typeUser != 'seller'" class="text-right">P.Unitario (Compra)</th>
                        <th class="text-center">Tiene Igv (Venta)</th>
                        <th class="text-center">Tiene Igv (Compra)</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{ disable_color : !row.active}"
                        v-if="row.today >= row.date_of_due || row.range_due >= row.date_of_due">
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.item_type }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.description }}</td>
                        <td>{{ row.date_of_due }}</td>
                        <td>
                            <span v-if="row.today >= row.date_of_due"
                                  class="float-right badge badge-danger badge-danger mr-3" style="font-size:12px">Vencido</span>
                            <span v-else-if="row.range_due >= row.date_of_due"
                                  class="float-right badge badge-warning badge-warning mr-3" style="font-size:12px">Por Vencer</span>
                        </td>
                        <td>{{ row.item_code }}</td>
                        <td>
                            <div v-if="config.product_only_location == true">
                                {{ row.stock }}
                            </div>
                            <div v-else>
                                <template v-if="typeUser=='seller' && row.unit_type_id !='ZZ'">
                                    {{ row.stock }}
                                </template>
                            </div>

                        </td>
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td v-if="typeUser != 'seller'" class="text-right">{{ row.purchase_unit_price }}</td>
                        <td class="text-center">{{ row.has_igv_description }}</td>
                        <td class="text-center">{{ row.purchase_has_igv_description }}</td>
                        <td class="text-right">
                            <template v-if="typeUser === 'admin'">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickCreate(row.id)">Editar
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDelete(row.id)">Eliminar
                                </button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDisable(row.id)" v-if="row.active">Inhabilitar
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                        @click.prevent="clickEnable(row.id)" v-else>Habilitar
                                </button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                        @click.prevent="clickBarcode(row)">Cod. Barras
                                </button>

                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <items-export :showDialog.sync="showExportDialog"></items-export>
        </div>
    </div>
</template>
<script>
import ItemsForm from '../form'
import DataTable from '../../../../components/DataTable.vue'
import {deletable} from '../../../../mixins/deletable'
import ItemsExport from './export'

export default {
    props: ['typeUser'],
    mixins: [deletable],
    components: {ItemsForm, DataTable, ItemsExport},
    data() {
        return {
            showDialog: false,
            showExportDialog: false,
            resource: 'items',
            recordId: null,
            config: {},
        }
    },
    created() {
        this.$http.get(`/configurations/record`).then(response => {
            this.config = response.data.data
        })
    },
    methods: {
        duplicate(id) {
            this.$http.post(`${this.resource}/duplicate`, {id})
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('Se guardaron los cambios correctamente.')
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
            this.$eventHub.$emit('reloadData')
        },
        clickExport() {
            this.showExportDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickCreate(recordId = null) {
            this.recordId = recordId
            this.showDialog = true
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/disable/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enable/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickBarcode(row) {
            if (!row.internal_id) {
                return this.$message.error('Para generar el código de barras debe registrar el código interno.')
            }
            window.open(`/${this.resource}/barcode/${row.id}`)
        }
    }
}
</script>
