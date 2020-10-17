<template>
  <div class="card bg-light mb-3 shadow mt-4" v-if="$store.getters.getAdminAccess && product">
      <div class="card-header h4">
        <div class="float-left" v-if="$route.params.id == 'new'">Add New Pizza</div>
        <div class="float-left" v-if="$route.params.id > 0">Edit Pizza</div>

      </div>
      <div class="card-body pt-1">
        <div id="form">
          <div class="row">
            <div class="col-md-2 mt-2">
              <div class="col-md-12">
                <img :src="'http://api.pizzapp.test/uploads/products/'+product.image" alt="" class="border rounded img-fluid" v-if="product.image !='' && previewFile ==''" >
                <img :src="previewFile" alt="" class="border rounded img-fluid" v-if="previewFile !=''">
              </div>
              <div class="col-md-12">
                <button class="btn btn-sm btn-secondary btn-block mt-1" style="height: 120px;" v-if="product.image ==''" @click="$refs.file.click()">Set Image</button>
                <button class="btn btn-sm btn-secondary btn-block mt-4" v-if="product.image !=''" @click="$refs.file.click()" >Edit Image</button>
                <input type="file" class="d-none mt-0"   required ref="file" @change="showImage($event)" :class="{'is-invalid' : $v.product.image.$error}" @blur="$v.product.image.$touch()"/>
              </div>

            </div>
            <div class="col-md-10">
              <label for="inputName" class="sr-only">Name</label>
              <input type="text" id="inputName" class="form-control top-input" placeholder="Name" required v-model="product.name" :class="{'is-invalid' : $v.product.name.$error}" @blur="$v.product.name.$touch()">
              <label for="inputPrice" class="sr-only">Price</label>
              <input type="text" id="inputPrice" class="form-control roundless-input" placeholder="Price" required v-model="product.price" :class="{'is-invalid' : $v.product.price.$error}" @blur="$v.product.price.$touch()">
              <label for="inputDesription" class="sr-only">Status</label>
              <select class="form-control custom-select roundless-input  mr-sm-2" id="inputCountry" required v-model="product.active" :class="{'is-invalid' : $v.product.active.$error}" @blur="$v.product.active.$touch()">
                <option value="1">Active</option>
                <option value="0">Pasive</option>
              </select>
              <label for="inputDesription" class="sr-only">Description</label>
              <textarea type="text" id="inputDesription" class="form-control roundless-input" style="height: 200px;" placeholder="Description" required v-model="product.description" :class="{'is-invalid' : $v.product.description.$error}" @blur="$v.product.description.$touch()"></textarea>
              <input type="hidden" v-model="product.id" >
            </div>
          </div>
          <button class="btn btn-lg btn-primary btn-block mt-4" type="submit" @click.prevent="saveProduct" :disabled="$v.$invalid">Save</button>
        </div>


      </div>
    </div>
</template>

<script>
import {required, minLength, maxLength, decimal} from 'vuelidate/lib/validators'

export default {
  layout:'AdminLayout',
  data(){
    return{
      product : {
        id:0,
        name: "",
        description : "",
        price : "",
        active : 1,
        image: ""
      },
      previewFile : ''
    }
  },
  validations:{
    product:{
      name:{
        required
      },
      description : {
        required
      },
      price : {
        required,
        decimal
      },
      active : {
        required
      },
      image : {
        required
      }

    }
  },
  beforeMount(){
    if(this.$route.params.id > 0)
    {
      this.$store.dispatch("getProductData",this.$route.params.id);
    }

  },
  methods:{
    showImage(e)
    {
      this.preview = true;
      let image = e.target.files[0];
      this.previewFile = URL.createObjectURL(image);
      this.product.image = image;
    },
    saveProduct()
    {
      let formData = new FormData();
      formData.append('name', this.product.name);
      formData.append('price', this.product.price);
      formData.append('description', this.product.description);
      formData.append('active', this.product.active);
      if(this.previewFile != '')
      {
        formData.append('image', this.product.image);
      }

      if(this.$route.params.id > 0)
      {

        formData.append('id', this.$route.params.id);
        formData.append('_method', 'PUT');
        this.$store.dispatch("updateProduct",formData)
        .then(response =>{
          this.showAlert(
            "success",
            "Alert",
            "Pizza updated successfully"
          );
        })
        .catch((err) => { console.log(err);});
      }
      else
      {

        this.$store.dispatch("addProduct",formData)
        .then(response =>{
          this.showAlert(
            "success",
            "Alert",
            "New pizza added successfully"
          );
        })
        .catch((err) => { console.log(err);});
      }
    }
  },
  computed:{
    ProductDatas()
    {
      return this.$store.getters.getProduct.data;
    }
  },
  watch:{
    ProductDatas()
    {
      this.product = this.ProductDatas;
    }
  }
}
</script>

<style>
#form input, #form textarea, #form select{ margin-top : 10px;}
</style>
