<template>
  <div v-if="$store.getters.getAdminAccess">
      <div class="card bg-light mb-3 shadow mt-4">
      <div class="card-header h4">
        <div class="float-left">Last Customers</div>
        <nuxt-link to="/admin/customers"  class="btn btn-outline-secondary my-2 my-sm-0 float-right" tag="a">All Customers</nuxt-link>
        <nuxt-link to="/admin/customers/new" class="btn btn-outline-secondary my-2 my-sm-0 float-right mr-2" tag="a"><i class="fa fa-plus" aria-hidden="true"></i> New</nuxt-link>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center p-4" v-if="!customerDatas.data && customerDatas != 'loading'">There is no customer.</p>
        <p class="h3 text-center p-4" v-if="customerDatas == 'loading'">Loading...</p>
        <table class="table" v-if="customerDatas.data">
          <thead>
            <th scope="row">Name</th>
            <th>E-Mail</th>
            <th>Created At</th>
            <th>District</th>
            <th>City</th>
            <th>Country</th>
            <th style="width: 150px;">&nbsp;</th>
          </thead>
          <tbody>
            <CustomerItem v-for="costumer in customerDatas.data" :key="costumer.id" :costumer-datas="costumer"/>
          </tbody>
        </table>

      </div>

    </div>

    <div class="card bg-light mb-3 shadow mt-4">
      <div class="card-header h4">
        <div class="float-left">Last Orders</div>
        <nuxt-link to="/admin/orders"  class="btn btn-outline-secondary my-2 my-sm-0 float-right" tag="a">All Orders</nuxt-link>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center p-4" v-if="!orderDatas.data && orderDatas != 'loading'">There is no order.</p>
        <p class="h3 text-center p-4" v-if="orderDatas == 'loading'">Loading...</p>
        <table class="table" v-if="orderDatas.data">
          <tbody>
            <Order  v-for="order in orderDatas.data" :key="order.id" :order-datas="order" :statuses="statuses" :isAdmin="true" />
          </tbody>
        </table>

      </div>
    </div>
  </div>
</template>

<script>
import Order from '@/components/orders/order'
import CustomerItem from '@/components/customer/CustomerItem'

export default {
  layout:'AdminLayout',
  components:{
    Order,
    CustomerItem
  },
  data(){
    return {
       pagination : {
        page : 1,
        search : null,
        perPage : 5
      }
    }
  },
  beforeMount(){
      this.$store.dispatch("getCustomers",this.pagination);
      this.$store.dispatch("getOrders",this.pagination);
    if (this.$store.state.Statuses == null) {
      this.$store.dispatch("getDatas",{url:"/admin/status",mutationName:"setStatuses"});
    }
  },
  computed:{
    customerDatas()
    {
      return this.$store.getters.getCustomer;
    },
    orderDatas() {
      return this.$store.getters.getOrder;
    },
    statuses() {
      return this.$store.getters.getStatuses;
    }
  }
}
</script>

<style>

</style>
