<template>
  <form class="form-inline my-2 my-lg-0 float-right" @submit.prevent="getDatas({page: 1, perPage: perPage, search: $refs.search.value, searchType: searchType})">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" ref="search" aria-label="Search">
        <a href="javascript:void(0)" class="btn btn-outline-secondary my-2 my-sm-0" @click="getDatas({page: 1, perPage: perPage, search: $refs.search.value, searchType: searchType})" >Search</a>
    </form>
</template>

<script>
export default {
  data(){
    return {
      pagination : {},
      search: null,
      searchType : null,
      searchTypeCommit: ""
    }
  },
  props:{
    dataSet: {
      required : true,
      type: String
    },
    perPage:{
      default: 10,
      type: Number
    },
    searchBy:{
      default:null,
      type:String
    }
  },
  created(){
    this.dataPrepare(this.dataSet);
  },
  methods : {
    dataPrepare(dataSet)
    {
      if(dataSet == "customer"){
        this.actionName = "getCustomers";
        this.searchTypeCommit = "setCustomerSearchType";
      }
      else if(dataSet == "order"){
        this.actionName = "getOrders";
        this.searchTypeCommit = "setOrderSearchType";
      }else if(dataSet == "product"){

        this.actionName = "getProducts";
        this.searchTypeCommit = "setProductSearchType";
      }
    },
    getDatas(pageData){

      console.log(pageData)
      this.$store.dispatch(this.actionName, pageData);
    }
  },
  watch:{
    searchBy(){
      this.$store.commit(this.searchTypeCommit,this.searchBy)
      this.searchType = this.searchBy;
    }
  }
}
</script>

<style>

</style>
