<template>
  <tr>
    <th scope="row" class="align-middle">{{costumerDatas.name+" "+costumerDatas.surname}}</th>
    <td class="align-middle">{{costumerDatas.email}}</td>
    <td class="align-middle">{{costumerDatas.phone}}</td>
    <td class="align-middle">{{costumerDatas.district}}</td>
    <td class="align-middle">{{costumerDatas.city}}</td>
    <td class="align-middle">{{costumerDatas.country}}</td>
    <td class="align-middle text-right" style="width: 150px;">
      <a href="javascript:void(0)" class="btn btn-danger float-right" @click="deleteCustomer(costumerDatas.id)">
        <i class="fa fa-remove" aria-hidden="true"></i>
      </a>
      <nuxt-link :to="'/admin/customers/'+costumerDatas.id" tag="a" class="btn btn-secondary float-right mr-2">
        <i class="fa fa-edit" aria-hidden="true"></i>
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
export default {
  data(){
    return {
      pagination : {
        page : 1,
        search : null
      }
    }
  },
   props:{
    costumerDatas : {
      required : true,
      type : Object
    }
  },
  methods:{
    deleteCustomer(customerID)
    {
      this.$store.dispatch("deleteCustomer",customerID)
      .then(response =>{
        this.$store.dispatch("getCustomers",this.pagination);
        this.showAlert(
          "success",
          "Alert",
          "Customer removed successfully"
        );
      })
      .catch((err) => { console.log(err);});
    }
  }
};
</script>

<style>
</style>
