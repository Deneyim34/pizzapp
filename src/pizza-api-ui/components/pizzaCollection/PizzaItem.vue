<template>
  <tr>
    <th scope="row" class="align-middle">
      <img :src="'http://api.pizzapp.test/uploads/products/'+productDatas.image" alt width="64" height="64" class="border rounded" />
    </th>
    <td class="align-middle">{{productDatas.name}}</td>
    <td
      class="align-middle"
    >{{productDatas.description}}</td>
    <td class="align-middle">${{productDatas.price}}</td>
    <td class="align-middle text-right" style="width: 150px;">
      <a href="javascript:void(0)" class="btn btn-danger float-right" @click="deleteProduct(productDatas.id)">
        <i class="fa fa-remove" aria-hidden="true"></i>
      </a>
      <nuxt-link :to="'/admin/products/'+productDatas.id" tag="a" class="btn btn-secondary float-right mr-2">
        <i class="fa fa-edit" aria-hidden="true"></i>
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
export default {
   props:{
    productDatas : {
      required : true,
      type : Object
    }
  },
  data(){
    return {
       pagination : {
        page : 1,
        search : null
      }
    }
  },
  methods:{
    deleteProduct(productID)
    {
      this.$store.dispatch("deleteProduct",productID)
      .then(response =>{
        this.$store.dispatch("getProducts",this.pagination);
        this.showAlert(
          "success",
          "Alert",
          "Pizza removed successfully"
        );
      })
      .catch((err) => { console.log(err);});
    }
  }
};
</script>

<style>
</style>
