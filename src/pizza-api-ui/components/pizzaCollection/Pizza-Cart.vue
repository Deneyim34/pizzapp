<template>
  <div class="col-md-3 mt-4">
            <div class="card shadow">
              <img class="card-img-top" :src="'http://api.pizzapp.test/uploads/products/'+productDatas.image" :alt="productDatas.name">
              <div class="card-body">
                <h5 class="card-title">{{productDatas.name}}</h5>
                <p class="card-text">{{productDatas.description}}</p>
              </div>
              <ul class="list-group list-group-flush border-top">
                <li class="list-group-item text-center"> ${{productDatas.price}}</li>
                <li class="list-group-item"><a href="javascript:void(0)" class="btn btn-secondary btn-block" v-b-modal.modal-lg data-toggle="modal"
                    data-target="#exampleModalLong" @click="setModelData()"><i class="fa fa-cart-plus" aria-hidden="true" ></i> Add To Cart</a>
                </li>
              </ul>
            </div>
          </div>
</template>

<script>
export default {
   props:{
    productDatas : {
      required : true,
      type : Object
    },
    productIndex : {
      required : true,
      type : Number
    }
  },
  methods:{
    setModelData(){

      this.$store.dispatch("setModelData",this.productDatas);
    }
  }

}
</script>

<style>

</style>
