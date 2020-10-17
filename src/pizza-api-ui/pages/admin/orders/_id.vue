<template>
  <div v-if="$store.getters.getAdminAccess">
    <div class="card mb-3 shadow mt-4" v-if="orderDatas.customer">
      <div class="card-header">
        <span class="float-left mt-1 mr-2">Order Details</span>
        <strong class="float-left mt-1">{{orderDatas.order_id}} - {{orderDatas.order_time}}</strong>
        <span class="float-right">
          <select class="form-control custom-select roundless-input" id="inputCountry" @change="updateStatus($event)" >
            <option :value="status.id" :selected="status.id == orderDatas.status.id" v-for="status in statuses" :key="status.id">{{ status.name }}</option>
          </select>
        </span>
        <span class="float-right mr-2 mt-1">
          <strong>Status:</strong>
        </span>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-sm-6">
            <h6 class="mb-3">Customer: </h6>
            <div>
              <strong>#{{orderDatas.customer.id}} {{orderDatas.customer.name + " " + orderDatas.customer.surname}}</strong>
            </div>
            <div>{{orderDatas.customer.address}}</div>
            <div>{{orderDatas.customer.district+", "+orderDatas.customer.city+" / "+orderDatas.customer.country}}</div>
            <div>Email: {{orderDatas.customer.email}}</div>
            <div>Phone: {{orderDatas.customer.phone}}</div>
          </div>

          <div class="col-sm-6">
          </div>
        </div>

        <div class="table-responsive-sm">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="center">#</th>
                <th>Image</th>
                <th style="width: 150px;">Item</th>
                <th>Description</th>

                <th class="right" style="width: 100px;">Unit Cost</th>
                <th class="center">Qty</th>
                <th class="right">Total</th>
              </tr>
            </thead>
            <tbody>
              <OrderDetailItem v-for="product in orderDatas.products" :key="product.id" :order-datas="product" />
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-lg-4 col-sm-5"></div>

          <div class="col-lg-4 col-sm-5 ml-auto">
            <table class="table table-clear">
              <tbody>
                <tr>
                  <td class="left">
                    <strong>Subtotal</strong>
                  </td>
                  <td class="right">${{parseFloat(orderDatas.total_price) - (parseFloat(orderDatas.total_price) * parseFloat(orderDatas.vat)) | numberFormat}}</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>VAT ({{parseFloat(orderDatas.vat) * 100}}%)</strong>
                  </td>
                  <td class="right">${{(parseFloat(orderDatas.total_price) * parseFloat(orderDatas.vat)) | numberFormat}}</td>
                </tr>
                <tr>
                  <td class="left">
                    <strong>Total</strong>
                  </td>
                  <td class="right">
                    <strong>${{parseFloat(orderDatas.total_price) | numberFormat}}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OrderDetailItem from "@/components/orders/order-detail-item";

export default {
  layout: "AdminLayout",
  components: {
    OrderDetailItem
  },
  data(){
    return{
      order : {}
    }
  },
  beforeMount(){
      this.$store.dispatch("getOrderData",this.$route.params.id);
      if(this.$store.state.Statuses == null)
      {
        this.$store.dispatch("getDatas",{url:"/status",mutationName:"setStatuses"});
      }
  },
  methods:{
    updateStatus(e){
      this.$store.dispatch("updateOrder",{status_id:e.target.value, id:this.orderDatas.id});
    }
  },
  computed:{
    orderDatas()
    {
      return this.$store.getters.getOrder;
    },
    statuses()
    {
      return this.$store.getters.getStatuses;
    }
  }
};

</script>

<style>
</style>
