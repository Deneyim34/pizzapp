<template>
  <div v-if="$store.getters.getAdminAccess">
      <div class="card bg-light mb-3 shadow mt-4">
      <div class="card-header h4">
        <div class="float-left">Customers</div>
        <SearchComponent :data-set="'customer'" />
        <nuxt-link to="/admin/customers/new" tag="a" class="btn btn-outline-secondary my-2 my-sm-0 float-right mr-2"><i class="fa fa-plus" aria-hidden="true"></i> New</nuxt-link>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center p-4" v-if="!customerDatas.data && customerDatas != 'loading'">There is no customer.</p>
        <p class="h3 text-center p-4" v-if="customerDatas == 'loading'">Loading...</p>
        <table class="table" v-if="customerDatas.data">
          <thead>
            <th scope="row">Name</th>
            <th>E-Mail</th>
            <th>Phone</th>
            <th>District</th>
            <th>City</th>
            <th>Country</th>
            <th style="width: 150px;">&nbsp;</th>
          </thead>
          <tbody>
            <CustomerItem v-for="costumer in customerDatas.data" :key="costumer.id" :costumer-datas="costumer" />
          </tbody>
        </table>

      </div>

      <div class="card-footer text-muted" v-if="customerDatas.data">
        <PaginationComponent :pagination-data="customerDatas.meta" :data-set="'customer'" />
      </div>

    </div>


  </div>
</template>

<script>
import CustomerItem from '@/components/customer/CustomerItem';
import PaginationComponent from "@/components/PaginationComponent";
import SearchComponent from "@/components/SearchComponent";

export default {
  layout:'AdminLayout',
  components:{
    CustomerItem,
    PaginationComponent,
    SearchComponent,
  },
  data(){
    return {
       pagination : {
        page : 1,
        search : null
      }
    }
  },
  beforeMount(){
      this.$store.dispatch("getCustomers",this.pagination);
  },
  computed:{
    customerDatas()
    {
      return this.$store.getters.getCustomer;
    }
  }

}
</script>

<style>

</style>
