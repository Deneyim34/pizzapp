<template>
  <div v-if="$store.getters.getAdminAccess">
    <div class="card bg-light mb-3 shadow mt-4" >
      <div class="card-header h4">
        <div class="float-left">Orders</div>
        <SearchComponent  :data-set="'order'" :search-by="searchType"/>
        <div class="float-right my-2 my-lg-0">
          <select class="form-control custom-select roundless-input  mr-sm-2" id="inputCountry" required v-model="searchType">
            <option value="ID">By Order ID</option>
            <option value="Customer">By Custumer ID</option>
          </select>
        </div>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center p-4" v-if="!orderDatas.data && orderDatas != 'loading'">There is no order.</p>
        <p class="h3 text-center p-4" v-if="orderDatas == 'loading'">Loading...</p>
        <table class="table" v-if="orderDatas.data">
          <tbody>
            <Order v-for="order in orderDatas.data" :key="order.id" :order-datas="order" :statuses="statuses" :isAdmin="true" />
          </tbody>
        </table>
      </div>

      <div class="card-footer text-muted" v-if="orderDatas.data">
        <PaginationComponent :pagination-data="orderDatas.meta" :data-set="'order'" />
      </div>
    </div>
  </div>
</template>

<script>
import Order from "@/components/orders/order";
import PaginationComponent from "@/components/PaginationComponent";
import SearchComponent from "@/components/SearchComponent";

export default {
  layout: "AdminLayout",
  components: {
    Order,
    PaginationComponent,
    SearchComponent
  },
  data(){
    return {
       pagination : {
        page : 1,
        search : null,
        searchType : null
      },
      searchType : "ID"
    }
  },
  beforeMount() {
    this.$store.dispatch("getOrders",this.pagination);
    if (this.$store.state.Statuses == null) {
      this.$store.dispatch("getDatas",{url:"/admin/status",mutationName:"setStatuses"});
      //this.$store.dispatch("getStatusData");
    }
  },
  computed: {
    orderDatas() {
      return this.$store.getters.getOrder;
    },
    statuses() {
      return this.$store.getters.getStatuses;
    }
  }
};
</script>

<style>
</style>
