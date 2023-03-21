<style>
	.v-select {
		margin-top: -2.5px;
		float: right;
		min-width: 180px;
		margin-left: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
		height: 25px;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#searchForm select {
		padding: 0;
		border-radius: 4px;
	}

	#searchForm .form-group {
		margin-right: 5px;
	}

	#searchForm * {
		font-size: 13px;
	}

	.record-table {
		width: 100%;
		border-collapse: collapse;
	}

	.record-table thead {
		background-color: #0097df;
		color: white;
	}

	.record-table th,
	.record-table td {
		padding: 3px;
		border: 1px solid #454545;
	}

	.record-table th {
		text-align: center;
	}
</style>
<div id="saleApproved">
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="record-table" v-if="sales.length > 0">
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Employee Name</th>
							<th>Saved By</th>
							<th>Sub Total</th>
							<th>VAT</th>
							<th>Discount</th>
							<th>Transport Cost</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Due</th>
							<th>Note</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="sale in sales">
							<tr tr :style="{background: sale.Status == 'p' ? 'rgb(255 251 220)' : ''}">
								<td>{{ sale.SaleMaster_InvoiceNo }}</td>
								<td>{{ sale.SaleMaster_SaleDate }}</td>
								<td>{{ sale.Customer_Name }}</td>
								<td>{{ sale.Employee_Name }}</td>
								<td>{{ sale.AddBy }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_SubTotalAmount }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_TaxAmount }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_TotalDiscountAmount }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_Freight }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_TotalSaleAmount }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_PaidAmount }}</td>
								<td style="text-align:right;">{{ sale.SaleMaster_DueAmount }}</td>
								<td style="text-align:left;">{{ sale.SaleMaster_Description }}</td>
								<td style="text-align:center;">
									<a href="" title="Sale Invoice" v-bind:href="`/sale_invoice_print/${sale.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-file"></i></a>
									<a href="" title="Chalan" v-bind:href="`/chalan/${sale.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-file-o"></i></a>
									<a v-if="sale.isEditEnable == 'yes'" href="javascript:" title="Edit Sale" @click="checkReturnAndEdit(sale)"><i class="fa fa-edit"></i></a>
									<?php if ($this->session->userdata('accountType') != 'u') { ?>
										<a v-if="sale.Status == 'p'" href="" title="Approve" @click.prevent="approveItem(sale.SaleMaster_SlNo)"><i style="font-size:18px" class="fa fa-check-square"></i></a>
										<a href="" title="Delete Sale" @click.prevent="deleteSale(sale.SaleMaster_SlNo)"><i class="fa fa-trash"></i></a>
									<?php } ?>
								</td>
							</tr>
						</template>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="5" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_SubTotalAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TaxAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalDiscountAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_Freight)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalSaleAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_PaidAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_DueAmount)}, 0) }}</td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
				<p class="text-center" v-else>Not Found Data</p>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#saleApproved',
		data() {
			return {
				sales: [],
				userType: '<?= $this->session->userdata('accountType'); ?>',
			}
		},
		created() {
			this.getSalesRecord();
		},
		methods: {
			getSalesRecord() {
				let url = '/get_sales';
				axios.post(url, {id: ""})
					.then(res => {
						this.sales = res.data.sales.filter(sale => sale.Status == 'p');
					})
			},
			deleteSale(saleId) {
				let deleteConf = confirm('Are you sure?');
				if (deleteConf == false) {
					return;
				}
				axios.post('/delete_sales', {
						saleId: saleId
					})
					.then(res => {
						let r = res.data;
						alert(r.message);
						if (r.success) {
							this.getSalesRecord();
						}
					})
			},
			approveItem(saleId) {
				let deleteConf = confirm('Are you sure to approve?');
				if (deleteConf == false) {
					return;
				}
				axios.post('/approve_sales', {
						saleId: saleId
					})
					.then(res => {
						let r = res.data;
						alert(r.message);
						if (r.success) {
							this.getSalesRecord();
						}
					})
			},
		}
	})
</script>