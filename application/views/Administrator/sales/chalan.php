<div id="chalan">
    <div class="row" style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>

            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            Chalan
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom:25px;">
                    <div class="col-xs-7">
                        <strong>Sales by:</strong> {{ sales.AddBy }}<br>
                        <strong>Marketing Officer:</strong> {{ sales.Employee_Name }}
                    </div>
                    <div class="col-xs-5 text-right">
                        <strong>Invoice No.:</strong> {{ sales.SaleMaster_InvoiceNo }}<br>
                        <strong>Sales Date:</strong> {{ sales.SaleMaster_SaleDate }}
                        {{ sales.AddTime | formatDateTime('h:mm a') }}
                    </div>
                </div>

                <div class="row" style="border-bottom:2px solid;width: 100%;margin: 0 auto;">
                    <div class="col-xs-6" style="padding-left: 0px;">
                        <p style="margin: 0px;font-weight:bold">BILL TO</p>
                    </div>
                    <div class="col-xs-6">
                        <p style="margin: 0px;font-weight:bold">SHIP TO</p>
                    </div>
                </div>


                <div class="row" style="display:flex;padding-top:10px;">
                    <div class="col-xs-6">
                        <b>Name: </b>{{ sales.Customer_Name }}<br>
                        <b>Address:</b> {{ sales.Customer_Address }}<br />
                        <b>Contact:</b> {{ sales.Customer_Mobile }}
                    </div>
                    <div class="col-xs-6">
                        <b>Name:</b> {{ sales.shipping_cus_name }}<br>
                        <b>Address:</b> {{ sales.shipping_address }}<br />
                        <b>Contact:</b> {{ sales.shipping_mobile }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Sl.</td>
                                    <td>Description</td>
                                    <td>Qnty</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td>{{ sl + 1 }}</td>
                                    <td style="text-align:left">{{ product.Product_Name }}</td>
                                    <td>{{ product.SaleDetails_TotalQuantity }} {{ product.Unit_Name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row"
                    style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px; margin-top:100px;">
                    <div class="col-xs-6">
                        <span style="text-decoration:overline;">Received by</span><br><br>
                        ** THANK YOU FOR YOUR BUSINESS **
                    </div>
                    <div class="col-xs-6 text-right">
                        <span style="text-decoration:overline;">Authorized by</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
new Vue({
    el: '#chalan',
    data() {
        return {
            sales: {
                SaleMaster_SlNo: parseInt('<?php echo $saleId;?>'),
                SaleMaster_InvoiceNo: null,
                SalseCustomer_IDNo: null,
                SaleMaster_SaleDate: null,
                Customer_Name: null,
                Customer_Address: null,
                Customer_Mobile: null,
                SaleMaster_TotalSaleAmount: null,
                SaleMaster_TotalDiscountAmount: null,
                SaleMaster_TaxAmount: null,
                SaleMaster_Freight: null,
                SaleMaster_SubTotalAmount: null,
                SaleMaster_PaidAmount: null,
                SaleMaster_DueAmount: null,
                SaleMaster_Previous_Due: null,
                SaleMaster_Description: null,
                AddBy: null
            },
            cart: [],
            style: null,
            companyProfile: null,
            currentBranch: null
        }
    },
    created() {
        this.setStyle();
        this.getSales();
        this.getCompanyProfile();
        this.getCurrentBranch();
    },
    methods: {
        getSales() {
            axios.post('/get_sales', {
                salesId: this.sales.SaleMaster_SlNo
            }).then(res => {
                this.sales = res.data.sales[0];
                this.cart = res.data.saleDetails;
            })
        },
        getCompanyProfile() {
            axios.get('/get_company_profile').then(res => {
                this.companyProfile = res.data;
            })
        },
        getCurrentBranch() {
            axios.get('/get_current_branch').then(res => {
                this.currentBranch = res.data;
            })
        },
        formatDateTime(datetime, format) {
            return moment(datetime).format(format);
        },
        setStyle() {
            this.style = document.createElement('style');
            this.style.innerHTML = `
                div[_h098asdh]{
                    background-color:#e0e0e0;
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:10px;
                    border-bottom: 1px solid #ccc;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 3px;
                    border: 1px solid #ccc;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
            document.head.appendChild(this.style);
        },
        async print() {
            let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
					</div>
				`;

            var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
            reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

            reportWindow.document.body.innerHTML += reportContent;

            if (this.searchType == '' || this.searchType == 'user') {
                let rows = reportWindow.document.querySelectorAll('.record-table tr');
                rows.forEach(row => {
                    row.lastChild.remove();
                })
            }

            let invoiceStyle = reportWindow.document.createElement('style');
            invoiceStyle.innerHTML = this.style.innerHTML;
            reportWindow.document.head.appendChild(invoiceStyle);

            reportWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            reportWindow.print();
            reportWindow.close();
        }
    }
})
</script>