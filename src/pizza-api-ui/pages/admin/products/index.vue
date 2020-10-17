<template>
  <div class="card bg-light mb-3 shadow mt-4" v-if="$store.getters.getAdminAccess">
      <div class="card-header h4">
        <div class="float-left">Pizza Collection</div>
        <SearchComponent :data-set="'product'" />
        <nuxt-link to="/admin/products/new" tag="a" class="btn btn-outline-secondary my-2 my-sm-0 float-right mr-2"><i class="fa fa-plus" aria-hidden="true"></i> New</nuxt-link>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center p-4" v-if="!productDatas.data && productDatas != 'loading'">There is no pizza.</p>
        <p class="h3 text-center p-4" v-if="productDatas == 'loading'">Loading...</p>

        <table class="table" v-if="productDatas.data">
          <thead>
            <th scope="row">Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th style="width: 150px;">&nbsp;</th>
          </thead>
          <tbody>

            <PizzaItem v-for="product in productDatas.data" :key="product.id" :product-datas="product" />
          </tbody>
        </table>

      </div>

      <div class="card-footer text-muted" v-if="productDatas.data">
        <PaginationComponent :pagination-data="productDatas.meta" :data-set="'product'" />
      </div>
    </div>
</template>

<script>
import PizzaItem from '@/components/pizzaCollection/PizzaItem';
import PaginationComponent from "@/components/PaginationComponent";
import SearchComponent from "@/components/SearchComponent";

export default {
  layout:'AdminLayout',
  components:{
    PizzaItem,
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
      this.$store.dispatch("getProducts",this.pagination);
  },
  computed:{
    productDatas()
    {
      console.log(this.$store.getters.getProduct)
      return this.$store.getters.getProduct;
    }
  }

}
</script>

<style>

</style>
